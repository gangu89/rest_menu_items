<?php

namespace Drupal\login_id\Entity;

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

    $fields['login_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Login ID'))
      ->setDescription(t('The ID used for the login credentials.'))
      ->setRequired(TRUE)
      ->setConstraints([
        'LoginId' => [],
        'LoginIdUnique' => [],
    ]);

    $fields['name']->getItemDefinition()
      ->setClass('\\Drupal\\login_id\\LoginIdItem');

    return $fields;
  }

}