<?php

namespace Drupal\ajaxData\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;


 
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\AppendCommand;
use Drupal\Core\Url;
use Drupal\examples\Utility\DescriptionTemplateTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\Component\Utility\Html;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\Core\Routing;
use Drupal\media\Entity\Media;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Core\Entity\Query\QueryFactory;
/**
 * Implementing a ajax form.
 */
class AjaxSubmitDemo extends FormBase {
/**
   * {@inheritdoc}
   */
  protected $entityTypeManager;
  /**
   * {@inheritdoc}
   */
  protected $cacheBackend;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, CacheBackendInterface $cacheBackend)
  {
    $this->entityTypeManager = $entityTypeManager;
    $this->cacheBackend = $cacheBackend;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('cache.default'),
    );
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ajax_submit_demo1';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // $form['message'] = [
    //   '#type' => 'markup',
    //   '#markup' => '<div class="result_message"></div>',
    // ];

    // $form['number_1'] = [
    //   '#type' => 'textfield',
    //   '#title' => $this->t('Number 1'),
    // ];

    // $form['number_2'] = [
    //   '#type' => 'textfield',
    //   '#title' => $this->t('Number 2'),
    // ];
    // <div id=ViewMoreBlog">
    // <button class="vid-view-more ubuntu white regular text-uppercase" onclick="Load_blog_data(5)">
    // view more</button>
    // </div>
  
    $form['actions'] = [
      '#prefix' => '<div id=ViewMoreBlog>',
      '#type' => 'button',
      '#value' => $this->t('view more'),
      '#ajax' => [
        'callback' => '::Load_blog_data(5)',
      ],
      '#suffix' => '</div>',
    ];

    return $form;
  }

  /**
   * Setting the message in our form.
   */
  public function Load_blog_data(array $form, FormStateInterface $form_state) {

    $response = new AjaxResponse();
    $response->addCommand(
      new HtmlCommand(
        // '.result_message',
        // '<div class="my_top_message">' . t('The results is @result', ['@result' => ($form_state->getValue('number_1') + $form_state->getValue('number_2'))]) . '</div>'
        
        )
        // $nids = \Drupal::entityQuery('node')->range($limitcount,1)->execute();
        // $blogData = \Drupal\node\Entity\Node::loadMultiple($nids);
        // $Pflag =0;
        // foreach($blogData as $key => $value)
        // {
        // echo '<div><h2>'.$value->title->value.'</h2></div>';
        // }
    );
    return $response;
  }

  /**
   * Submitting the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
