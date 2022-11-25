<?php

namespace Drupal\field_create;

/**
 * Service to automatically create fields.
 */
interface FieldCreateManagerInterface {

  /**
   * Retrieve schemas data.
   *
   * @param string $entity_type_id
   *   A given entity type ID to retrieve definitions for.
   *
   * @return array
   *   The list of fields to be created, keyed by the entity type ID.
   */
  public function getFieldsDefinitions(string $entity_type_id = NULL);

  /**
   * Provides field storage definitions for a content entity type.
   *
   * @param string $entity_type_id
   *   A given entity type ID.
   * @param array $list
   *   A given list of field definitions.
   *
   * @return \Drupal\Core\Field\FieldStorageDefinitionInterface[]
   *   An array of field storage definitions, keyed by field name.
   */
  public function createEntityFieldStorages(string $entity_type_id, array $list = []);

  /**
   * Create fields on entity types on-the-fly.
   *
   * @param string $entity_type_id
   *   A given entity type ID.
   * @param array $list
   *   A given list of field definitions.
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface[]
   *   An array of bundle field definitions, keyed by bundle and field_name.
   */
  public function createEntityFields(string $entity_type_id, array $list = []);

}
