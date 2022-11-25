<?php

namespace Drupal\lucky;
//require 'vendor/autoload.php';

use Drupal\Core\Controller\ControllerBase;
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
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\media\Entity\Media;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Core\Entity\Query\QueryFactory;



/**
 * Controller to get JSON for all search results.
 */
class UtichooseDataJsonController extends ControllerBase
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
  
    /**
   * Load PostSchemeMaster Lot 1 Data to JSON.
   */
  public function PostSchemeMaster() {
    $getcategorymaster = $_POST['getcategorymaster'];
    // echo '<pre>';print_r($getcategorymaster);die;
    $response = [];
    $base_url = Request::createFromGlobals()->getSchemeAndHttpHost();  
    $client = \Drupal::httpClient();
    
    $url = 'https://utiservicesuat.kfintech.com/Genesis/api/Genesis/GetSchemeMaster';
    $method = 'POST';
    $options = [
    'form_params' => [
      'Adminusername'=>'KCPLZNMQAMR4',
      'Adminpassword'=>'W3laJmM1ZjtxW0BP-aW1QQMt2fBQ=',
      'AppVersion'=>'5.0',
      'DeviceDetails'=>'116.50.59.180',
      'Source'=>'Mobile',
      'ModuleName'=>'Purchase',
      'TransactionType'=>'SIN',
      'CategoryType'=>$getcategorymaster

    ]
    ];

    $client = \Drupal::httpClient();

    $response = $client->request($method, $url, $options);
    $code = $response->getStatusCode();
    
    if ($code == 200) {
    $body = $response->getBody()->getContents();
    $content = json_decode($body);
    $data = array();
    $dest_count = 0;
   //echo '<pre>';print_r($body);die;
    foreach($content->GetSchemeMaster as $v){
 
    $SCHEME[] = $v->SCHEME;
    $SCHDESC[] = $v->SCHDESC;
 
    } 
  
    }
    echo json_encode($SCHDESC);exit;
    // return  new JsonResponse($response);     
  }

    /**
   * Load PostPlanOptions Lot 1 Data to JSON.
   */
  public function PostPlanOptions() {
    $getplanoption = $_POST['getplanoption'];
    
    $response = [];
    $base_url = Request::createFromGlobals()->getSchemeAndHttpHost();  
    $client = \Drupal::httpClient();
    
    $url = 'https://utiservicesuat.kfintech.com/Genesis/api/Genesis/GetPlanOptions';
    $method = 'POST';
    $options = [
      'form_params' => [
      'Adminusername'=>'KCPLZNMQAMR4',
      'Adminpassword'=>'W3laJmM1ZjtxW0BP-aW1QQMt2fBQ=',
      'AppVersion'=>'5.0',
      'DeviceDetails'=>'116.50.59.180',
      'Source'=>'Mobile',
      'ModuleName'=>'STP',
      'TransactionType'=>'SIP',
      'Category'=>'DEBT',
      'Mode'=>'direct',
      'Folio'=>'502288418797',
      'Scheme'=>'CO',
      'TraceID'=>null,
      'DeviceMode'=> null,
      'IP'=>null
    ]
    ];

    $client = \Drupal::httpClient();

    $response = $client->request($method, $url, $options);
    $code = $response->getStatusCode();
    
    if ($code == 200) {
    $body = $response->getBody()->getContents();
    $content = json_decode($body);
    $data = array();
    $dest_count = 0;
  //  echo '<pre>';print_r($body);die;
    foreach($content->GetPlanOptions as $v){
 
    $Option[] = $v->Option;
    $OptionDescription[] = $v->OptionDescription;
 
    } 
  
    }
    echo json_encode($OptionDescription);exit;
    // return  new JsonResponse($response);     
  }

}
