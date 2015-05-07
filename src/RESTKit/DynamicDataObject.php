<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/7/15
 * Time: 3:10 PM
 */

namespace RESTKit;


use RESTKit\Properties\BooleanProperty;
use RESTKit\Properties\ClassProperty;
use RESTKit\Properties\DateTimeProperty;
use RESTKit\Properties\DoubleProperty;
use RESTKit\Properties\IntegerProperty;
use RESTKit\Properties\StringProperty;

class DynamicDataObject extends JSONDataObject {

  protected function createPropertyByGuessing($name, $value, $default = null) {
    $type = gettype($value);

    switch ($type) {
      case 'integer':
        $property = new IntegerProperty($default);
        break;
      case 'boolean':
        $property = new BooleanProperty($default);
        break;
      case 'string':
        $property = new StringProperty($default);
        break;
      case 'double':
        $property = new DoubleProperty($default);
        break;
      case 'array':
        $property = new ClassProperty(__CLASS__, $default);
        break;
      default:
        if ($value instanceof \DateTimeInterface) {
          $property = new DateTimeProperty($default);
        }
        else {
          $property = new ClassProperty(__CLASS__, $default);
        }

        break;
    }
    $property->set($value);
    $this->createProperty($name, $property);
  }

  public function __set($name, $value = null) {
    if (null !== $value && empty($this->_properties[$name])) {
      $this->createPropertyByGuessing($name, $value);
    }
    else {
      parent::__set($name, $value);
    }
  }
}