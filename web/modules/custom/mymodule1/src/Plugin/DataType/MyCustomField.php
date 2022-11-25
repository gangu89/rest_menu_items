<?php

namespace Drupal\mymodule1\Plugin\DataType;
 
use Drupal\Core\TypedData\Plugin\DataType\StringData;
use Drupal\Core\TypedData\Type\StringInterface;
 
/**
 * The MyCustomField data type.
 *
 * The plain value of a MyCustomField for an entity.
 *
 * @DataType(
 * id = "my_custom_field",
 * label = @Translation("My Custom Field")
 * )
 */
class MyCustomField extends StringData implements StringInterface {

}
