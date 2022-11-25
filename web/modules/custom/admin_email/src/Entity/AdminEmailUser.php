<?php

namespace Drupal\admin_email\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\Entity\User;

class AdminEmailUser extends User {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    /** @var \Drupal\Core\Field\BaseFieldDefinition[] $fields */
    $fields = parent::baseFieldDefinitions();

    $fields['admin_mail'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Admin email'))
      ->setDescription(t('The email used from administrator users to contact the user.'))
      ->setDefaultValue('')
      ->addConstraint('UserMailUnique')
      ->addConstraint('UserMailRequired')
      ->addConstraint('ProtectedUserField');

    return $fields;
  }

}