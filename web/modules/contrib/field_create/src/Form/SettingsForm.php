<?php

namespace Drupal\field_create\Form;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Serialization\Yaml;
use Drupal\Core\Url;
use Drupal\field\Entity\FieldConfig;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provide settings for Fields From JSON module.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * The existing entity type IDs on this site.
   *
   * @var \Drupal\Core\Entity\EntityTypeInferca[]
   */
  protected $entityTypes = [];

  /**
   * The field_create service.
   *
   * @var \Drupal\field_createg\FieldCreateManagerInterface
   */
  protected $manager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);

    $instance->entityTypes = $container->get('entity_type.manager')->getDefinitions();
    $instance->manager = $container->get('field_create.manager');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'field_create_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    $config_names = [];

    foreach ($this->entityTypes as $entity_type) {
      if (!$entity_type->entityClassImplements(FieldableEntityInterface::class)) {
        continue;
      }
      $config_names[] = 'field_create.' . $entity_type->id() . '.settings';
    }

    return $config_names;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $field_type = NULL) {
    $description = $this->t('If you need help to use this module, feel free to visit @link.', [
      '@link' => Link::fromTextAndUrl($this->t('the help page'), Url::fromRoute('help.page', [
        'name' => 'field_create',
      ]))->toString(),
    ]);
    $description .= '<br>';
    $description .= $this->t('Copy/Paste your field definitions as YAML content in the boxes below.') . ' ';
    $description .= Link::fromTextAndUrl($this->t('See an example here'), Url::fromUri(
      file_create_url(drupal_get_path('module', 'field_create') . '/example/field_create.node.settings.yml'),
      ['attributes' => ['target' => '_blank']]
    ))->toString();

    $form['container'] = [
      '#type' => 'item',
      '#title' => $this->t('Field definitions'),
      '#markup' => $description,
    ];

    $entity_type_ids = [];
    foreach ($this->entityTypes as $entity_type) {
      if (!$entity_type->entityClassImplements(FieldableEntityInterface::class)) {
        continue;
      }

      $entity_type_id = $entity_type->id();

      $entity_type_ids[] = $entity_type_id;

      $config = $this->config('field_create.' . $entity_type_id . '.settings');

      $form['container'][$entity_type_id] = [
        '#type' => 'details',
        '#title' => $entity_type->get('label'),
        '#open' => FALSE,
      ];

      $config_data = $config->getRawData() ?? [];
      $field_prefix = $config_data['_settings']['field_prefix'] ??
      \Drupal::service('field_create.manager')->getFieldPrefix();
      unset($config_data['_settings']);

      foreach ($config_data as $field_name => $field_info) {
        // Field info.
        $form['container'][$entity_type_id][$field_name] = [
          '#type' => 'details',
          '#title' => $field_info['label'] ?? $field_name,
          '#open' => FALSE,
        ];

        // Check field info by bundle.
        foreach (array_keys($field_info['bundles'] ?? []) as $bundle_id) {
          $field = FieldConfig::load($entity_type_id . '.' . $bundle_id . '.' . $field_prefix . $field_name);

          $field_status = $field instanceof FieldConfig ? 'status' : 'warning';

          $bundle_field_info = !$field instanceof FieldConfig ? [] : [
            'type' => $field->getType(),
            'name' => $field->getName(),
            'label' => $field->getLabel(),
          ];

          $form['container'][$entity_type_id][$field_name][$bundle_id] = [
            '#type' => 'item',
            '#title' => $bundle_id . ' : ' . implode(' | ', array_filter($bundle_field_info, function ($value) {
              return \is_string($value);
            })),
            '#wrapper_attributes' => ['class' => ['messages', 'messages--' . $field_status]],
          ];
        }
      }

      $form['container'][$entity_type_id][$entity_type_id . '_content'] = [
        '#type' => 'textarea',
        '#title' => $this->t('Config'),
        '#placeholder' => $this->t('Copy/Paste YAML here'),
        '#default_value' => Yaml::encode($config->getRawData()),
      ];
    }

    $form_state->set('entity_type_ids', $entity_type_ids);

    // Legend
    $legend_ok = '<div class="messages messages--status">' . $this->t('This box describes a field that was created correctly.') . '</div>';
    $legend_warning = '<div class="messages messages--warning">' . $this->t('This box describes a field that was not created yet.') . '</div>';
    $legend_ko = '<div class="messages messages--error">' . $this->t('This box describes a field that is not possible to create.') . '</div>';

    $form['legend'] = [
      '#prefix' => '<hr>',
      '#type' => 'details',
      '#title' => $this->t('Legend'),
      '#weight' => 99,
    ];
    $form['legend']['details'] = [
      '#type' => 'item',
      '#markup' => $legend_ok . ' ' . $legend_warning . ' ' . $legend_ko,
      '#attributes' => ['id' => ['field-status-legend']],
    ];

    // Run the create field manager.
    $form['run'] = [
      '#prefix' => '<hr>',
      '#type' => 'details',
      '#title' => $this->t('Actions'),
      '#open' => TRUE,
    ];
    $form['run']['run_entity_type_id'] = [
      '#type' => 'select',
      '#options' => array_combine($entity_type_ids, $entity_type_ids),
      '#empty_option' => $this->t('- Select -'),
      '#title' => $this->t('Create fields by Entity Type'),
    ];
    $form['run']['run_manager'] = [
      '#type' => 'submit',
      '#value' => $this->t('Create fields now'),
      '#weight' => 1,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->get('entity_type_ids') ?? [] as $entity_type_id) {
      if (!empty($yaml = $form_state->getValue($entity_type_id . '_content'))) {
        if (!empty($decoded = Yaml::decode($yaml)) && !$decoded) {
          $form_state->setErrorByName(
            $entity_type_id . '_content',
            $this->t('Could not decode content properly for @entity_type_id.', [
              '@entity_type_id' => $entity_type_id,
            ])
          );
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->get('entity_type_ids') ?? [] as $entity_type_id) {
      $config = $this->config('field_create.' . $entity_type_id . '.settings');
      $content = Yaml::decode($form_state->getValue($entity_type_id . '_content'));
      foreach ($content as $key => $value) {
        $config->set($key, $value);
      }
      $config->save();
    }

    $this->messenger()->addMessage($this->t('Settings have been saved.'));

    $parents = $form_state->getTriggeringElement()['#parents'] ?? [];
    $trigger = reset($parents);

    // Do the job now.
    if ($trigger == 'run_manager') {
      $this->createFieldsNow($form_state->getValue('run_entity_type_id'));
    }
  }

  /**
   * Do your job now wonderful module!
   *
   * @param string $entity_type_id
   *   A given entity type ID to filter definitions list.
   */
  private function createFieldsNow(string $entity_type_id = NULL) {
    $definitions = $this->manager->getFieldsDefinitions($entity_type_id);

    foreach ($definitions as $entity_type_id => $list) {
      $this->manager->createEntityFields($entity_type_id, $list);

      $this->messenger()->addStatus($this->t('Processed @count fields for @entity_type_id.', [
        '@count' => count($list),
        '@entity_type_id' => $entity_type_id,
      ]));
    }
  }
}
