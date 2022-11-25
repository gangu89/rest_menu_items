<?php 
namespace Drupal\paragraph_validate\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\Entity\User;

class LoginIdUser extends User {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    /** @var \Drupal\Core\Field\BaseFieldDefinition[] $fields */
    $fields = parent::baseFieldDefinitions();

    $fields['field_first_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Login ID'))
      ->setDescription(t('The ID used for the login credentials.'))
      ->setRequired(TRUE)
      ->setConstraints([
        'field_first_name' => [],
        'LoginIdUnique' => [],
    ]);

    $fields['name']->getItemDefinition()
      ->setClass('\\Drupal\\paragraph_validate\\LoginIdItem');

    return $fields;
  }

}