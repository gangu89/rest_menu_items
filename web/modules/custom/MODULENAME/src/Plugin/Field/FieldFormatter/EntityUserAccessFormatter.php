<?php
namespace Drupal\MODULENAME\Plugin\Field\FieldFormatter;
     
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
     
/**
 * Plugin implementation of the 'entity_user_access_f' formatter.
 *
 * @FieldFormatter(
 *   id = "entity_user_access_f",
 *   label = @Translation("Entity User Access - Formatter"),
 *   description = @Translation("Entity User Access - Formatter"),
 *   field_types = {
 *     "entity_user_access",
 *   }
 * )
 */
     
class EntityUserAccessFormatter extends FormatterBase {
  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = array();
     
    foreach ($items as $delta => $item) {
      $elements[$delta] = array(
        'uid' => array(
          '#markup' => \Drupal\user\Entity\User::load($item->uid)->getUsername(),
          ),
        // Add more content
      );
    }
     
    return $elements;
  }
}