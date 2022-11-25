<?php 

namespace Drupal\MODULENAME\Plugin\Field\FieldType;
     
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
     
/**
 * @FieldType(
 *   id = "entity_user_access",
 *   label = @Translation("Entity User Access"),
 *   description = @Translation("This field stores a reference to a user and a password for this user on the entity."),
 * )
*/
     
class EntityUserAccessField extends FieldItemBase {
  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    //ToDo: Implement this.
    $properties['uid'] = DataDefinition::create('integer')
      ->setLabel(t('User ID Reference'))
      ->setDescription(t('The ID of the referenced user.'))
      ->setSetting('unsigned', TRUE);

  $properties['password'] = DataDefinition::create('string')
      ->setLabel(t('Password'))
      ->setDescription(t('A password saved in plain text. That is not safe dude!'));

  $properties['created'] = DataDefinition::create('timestamp')
    ->setLabel(t('Created Time'))
    ->setDescription(t('The time that the entry was created'));

    // ToDo: Add more Properties.
 
    return $properties;
  }
     
  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    //ToDo: Implement this.
    $columns = array(
      'uid' => array(
        'description' => 'The ID of the referenced user.',
        'type' => 'int',
        'unsigned' => TRUE,
      ),
      'password' => array(
        'description' => 'A plain text password.',
        'type' => 'varchar',
        'length' => 255,
      ),
      'created' => array(
        'description' => 'A timestamp of when this entry has been created.',
        'type' => 'int',
      ),
  
      // ToDo: Add more columns.
    );
   
    $schema = array(
      'columns' => $columns,
      'indexes' => array(),
      'foreign keys' => array(),
    );
  
    return $schema;
  }

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    // Some fields above.
   
    $fields['entity_user_access'] = BaseFieldDefinition::create('entity_user_access')
      ->setLabel(t('Entity User Access'))
      ->setDescription(t('Specify passwords for any user that want to see this entity.'))
      ->setCardinality(-1); // Ensures that you can have more than just one member
   
    // Even more fields below.
   
    return $fields;
  }
  
}
