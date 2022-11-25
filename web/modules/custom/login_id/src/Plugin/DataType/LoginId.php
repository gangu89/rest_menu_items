<?php

namespace Drupal\login_id\Plugin\DataType;
 
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
class LoginId extends StringData implements StringInterface {

}
