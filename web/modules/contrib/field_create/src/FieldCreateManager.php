<?php

namespace Drupal\field_create;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\Render\Markup;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field_create\FieldCreateManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Main service to communicate with field_create API.
 */
class FieldCreateManager implements FieldCreateManagerInterface {

  use StringTranslationTrait;
  use MessengerTrait;

  /**
   * A logger instance.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The user storage.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity display repository.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs our object.
   */
  public function __construct(
    LoggerInterface $logger,
    ModuleHandlerInterface $module_handler,
    EntityTypeManagerInterface $entity_type_manager,
    EntityDisplayRepositoryInterface $entity_display_repository,
    ConfigFactoryInterface $config_factory
  ) {
    $this->logger = $logger;
    $this->moduleHandler = $module_handler;
    $this->entityTypeManager = $entity_type_manager;
    $this->entityDisplayRepository = $entity_display_repository;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritDoc}
   */
  public function getFieldsDefinitions(string $entity_type_id = NULL) {
    $definitions = [];
    foreach ($this->entityTypeManager->getDefinitions() as $entity_type) {
      $id = $entity_type->id();
      if ($config = $this->configFactory->get('field_create.' . $id . '.settings')) {
        $definitions[$id] = $config->getRawData();
      }
    }

    $definitions = array_filter($definitions);

    // Allow other modules to inject definitions.
    $this->moduleHandler->alter('field_create_definitions', $definitions);

    return !$entity_type_id ? $definitions : [
      $entity_type_id => $definitions[$entity_type_id] ?? []
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function createEntityFieldStorages(string $entity_type_id, array $list = []) {
    // Prepare custom field prefix, if possible.
    $field_prefix = $list['_settings']['field_prefix'] ?? $this->getFieldPrefix();
    unset($list['_settings']);

    $field_storages = [];
    foreach ($list as $field_name => $field_info) {
      try {
        // Check required field information.
        if (!\is_string($field_name)) {
          throw new \Exception('Missing field name');
        }
        if (!($field_type = $field_info['type'] ?? NULL)) {
          throw new \Exception('Missing field type for @field_name on @entity_type', [
            '@field_name' => $field_name,
            '@entity_type' => $entity_type_id,
          ]);
        }

        // Prepend custom field prefix.
        $field_name = $field_prefix . $field_name;

        // Check if field exists.
        if ($field_storage = FieldStorageConfig::loadByName($entity_type_id, $field_name)) {
          $field_storages[$field_name] = $field_storage;
          continue;
        }

        // @todo Check $field_name length.

        // Create new field storage.
        $field_storage = FieldStorageConfig::create([
          'field_name' => $field_name,
          'entity_type' => $entity_type_id,
          'type' => $field_type,
          'cardinality' => -1,
          'provider' => 'field_create',
        ]);

        if ($cardinality = $field_info['cardinality'] ?? NULL) {
          $field_storage->setCardinality($cardinality);
        }

        if (isset($field_info['revisionable'])) {
          $field_storage->setRevisionable((bool) $field_info['revisionable']);
        }

        $storage_settings = $field_info['storage'] ?? [];
        if (!empty($storage_settings)) {
          $field_storage->setSettings($storage_settings);
        }

        $field_storage->save();

        $field_storages[$field_name] = $field_storage;
      } catch (\Exception $e) {
        $this->logger->error($this->t('Error creating field storage @field_name on @entity_type:' . PHP_EOL . '@error', [
          '@field_name' => $field_name ?? $this->t('unknown field'),
          '@entity_type' => $entity_type_id,
          '@error' => Markup::create($e->getMessage()),
        ]));
      }
    }

    return $field_storages;
  }

  /**
   * {@inheritDoc}
   */
  public function createEntityFields(string $entity_type_id, array $list = []) {
    // Create or load field storages.
    $field_storages = $this->createEntityFieldStorages($entity_type_id, $list);

    // If set, use a custom field prefix for this list of fields.
    $field_prefix = $list['_settings']['field_prefix'] ?? $this->getFieldPrefix();

    // If set, mark all fields as required or not.
    $field_required = $list['_settings']['field_required'] ?? FALSE;

    $fields = [];
    foreach ($field_storages as $field_name => $field_storage) {
      $original_field_name = $this->removeFieldPrefix($field_name, $field_prefix);
      $field_info = $list[$original_field_name] ?? [];

      // Set custom settings by bundle.
      foreach ($field_info['bundles'] ?? [] as $bundle_id => $bundle_settings) {
        $force_update = $field_info['force'] ?? FALSE;

        try {
          // Load or create field.
          $field = FieldConfig::load($entity_type_id . '.' . $bundle_id . '.' . $field_name);

          if (!$field instanceof FieldConfig) {
            $field = FieldConfig::create([
              'field_storage' => $field_storage,
              'bundle' => $bundle_id,
              'label' => $bundle_settings['label'] ?? $field_info['label'] ?? $field_name,
            ]);
          }

          // Do not override existing fields.
          if (!$field->isNew() && !$force_update) {
            throw new \Exception($this->t('Field already exists.'));
          }

          // Mark field as required.
          $field->setRequired($bundle_settings['required'] ?? $field_info['required'] ?? $field_required);

          // Custom field settings.
          // Priority to those defined at the bundle-level.
          // Otherwise use settings defined at the field level.
          $fields_settings = $bundle_settings['settings'] ?? $field_info['settings'] ?? [];
          if (!empty($fields_settings)) {
            $field->setSettings($fields_settings);
          }

          // Update field values.
          if ($field->isNew() || $force_update) {
            $field->setLabel($settings['label'] ?? $field_info['label'] ?? $field_name);
            $field->save();
          }

          $field_definition = $field->getItemDefinition()->getFieldDefinition();

          // Set configurable display options.
          foreach ($bundle_settings['displays'] ?? [] as $display_type => $config) {
            if (!\is_array($config)) {
              continue;
            }
            foreach ($config as $view_mode_id => $options) {
              $this->setDisplay(
                $display_type,
                $field_definition,
                $field_name,
                $entity_type_id,
                $bundle_id,
                $view_mode_id,
                $options
              );
            }
          }

          $fields[$bundle_id][$field_name] = $field;
        } catch (\Exception $e) {
          $this->logger->error($this->t('Error instanciating @field_name on @entity_type:@bundle' . PHP_EOL . ' => @error', [
            '@field_name' => $field_name,
            '@entity_type' => $entity_type_id,
            '@bundle' => $bundle_id,
            '@error' => Markup::create($e->getMessage()),
          ]));
        }
      }
    }

    return $fields;
  }

  /**
   * Helper function to remove prefix from field names.
   *
   * @param string $str
   *   A given string.
   * @param string $prefix
   *   A given string.
   *
   * @return string
   *   The name without the prefix.
   */
  public function removeFieldPrefix(string $str, string $prefix = NULL) {
    $prefix = $prefix ?: $this->getFieldPrefix();
    return strpos($str, $prefix) === 0 ? substr($str, strlen($prefix)) : $str;
  }

  /**
   * Dynamically get the field prefix.
   *
   * @return string|null
   *   The field prefix or nothing
   */
  public function getFieldPrefix() {
    return \Drupal::config('field_ui.settings')->get('field_prefix');
  }

  /**
   * Helper function to set entity displays.
   *
   * @return bool|int
   *   The return of component save operation or FALSE.
   */
  public function setDisplay($type, $field_definition, $field_name, $entity_type_id, $bundle_id, $view_mode_id, $custom_options) {
    switch ($type) {
      case 'form':
        $display = $this->entityDisplayRepository->getFormDisplay($entity_type_id, $bundle_id, $view_mode_id);
        $storage = $this->entityTypeManager->getStorage('entity_form_display');
        break;
      case 'view':
        $display = $this->entityDisplayRepository->getViewDisplay($entity_type_id, $bundle_id, $view_mode_id);
        $storage = $this->entityTypeManager->getStorage('entity_view_display');
        break;
      default:
        return FALSE;
    }

    if (!$display) {
      return FALSE;
    }

    $key = $entity_type_id . '.' . $bundle_id . '.' . $view_mode_id;

    $component = $storage->load($key)->getRenderer($field_name);

    $default_settings = $component ? $component->getSettings() : ($field_definition->getDisplayOptions($type) ?? [
      'label' => 'hidden',
      'region' => 'hidden',
    ]);

    $display->setComponent($field_name, $default_settings + $custom_options);
    return $display->save();
  }

}
