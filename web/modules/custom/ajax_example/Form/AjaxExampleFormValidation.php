<?php


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
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\Entity\User;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

 function checkUserEmailValidation(array $form, FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();
  
   // Check if User or email exists or not
    if (user_load_by_name($form_state->getValue(user_email)) || user_load_by_mail($form_state->getValue(user_email))) {
      $text = 'User or Email is exists';
    } else {
      $text = 'User or Email does not exists';
    }
    $ajax_response->addCommand(new HtmlCommand('#user-email-result', $text));
    return $ajax_response;
}

