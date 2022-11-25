<?php

namespace Drupal\ajaxData\Controller;
 
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
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
 * Controller to get JSON for all search results.
 */
class AjaxDataController extends ControllerBase
{
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

  public function load_ajax_data(){
   

    // echo 'hi';die;
     $Actionmethod = \Drupal::request()->request->get("action"); 
	 $Viewcounter = \Drupal::request()->request->get("count"); 
     $method = htmlspecialchars($Actionmethod);
     $path= empty($type) ?  \Drupal::theme()->getActiveTheme()->getPath() :  drupal_get_path('module', $type);
     $url_file= $path."/load_ajax_data.php";
      require $url_file; // import file from Orange theme
   // dsm($method) ;
	 $method($Viewcounter);
      exit(); // 
    
     }
 
  
}

