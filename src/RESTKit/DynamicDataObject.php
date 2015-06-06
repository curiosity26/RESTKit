<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/7/15
 * Time: 3:10 PM
 */

namespace RESTKit;


use RESTKit\Properties\ArrayProperty;
use RESTKit\Properties\BooleanProperty;
use RESTKit\Properties\ClassProperty;
use RESTKit\Properties\DateTimeProperty;
use RESTKit\Properties\DoubleProperty;
use RESTKit\Properties\IntegerProperty;
use RESTKit\Properties\StringProperty;

class DynamicDataObject extends JSONDataObject {

  private $collection_class;

  public function setCollectionClass($class = null) {
    $this->collection_class = $class;

    return $this;
  }

  public function getCollectionClass() {
    return isset($this->collection_class) ? $this->collection_class :
      '\\RESTKit\\Collection\\Collection';
  }

  protected function handleArrayData(array &$data, $default = null) {
    if (!empty($data) && !is_integer(key($data))) {

      $property = new ClassProperty(__CLASS__, $default,
        array($data, $this->getClient()), true);

      $data = $property->get();
    }
    elseif (!empty($data) && ($current = current($data))
      && (is_array($current) || $current instanceof \stdClass)) {

      $property = new ClassProperty($this->getCollectionClass(),
        $default, array((array)$data), true);

      $data = $property->get();
    }
    else {
      $property = new ArrayProperty($default);
    }

    if (($client = $this->getClient()) !== null
      && method_exists($data, 'setClient')) {
      $data->setClient($client);
    }

    return $property;
  }

  public function createPropertyByGuessing($name, $value, $default = null) {
    $type = gettype($value);

    switch ($type) {
      case 'integer':
        $property = new IntegerProperty($default);
        break;
      case 'boolean':
        $property = new BooleanProperty($default);
        break;
      case 'string':
        if (preg_match('/\d{4}-\d{2}-\d{2}(T|\s)\d{1,2}:\d{2}(:\d{2})?/', $value) != FALSE) {
          try {
            $value = new \DateTime($value);
            $property = new DateTimeProperty($default);
          } catch(\Exception $e) {
            $property = new StringProperty($default);
          }
        }
        else {
          $property = new StringProperty($default);
        }
        break;
      case 'double':
        $property = new DoubleProperty($default);
        break;
      case 'array':
        $property = $this->handleArrayData($value, $default);
        break;
      default:
        if ($value instanceof \DateTimeInterface) {
          $property = new DateTimeProperty($default);
        }
        elseif ($value instanceof \stdClass) {
          $value = (array)$value;
          $property = $this->handleArrayData($value, $default);
        }
        else {
          $property = new ClassProperty(__CLASS__, $default,
            array($value, $this->getClient()), true);
        }
        break;
    }
    $property->set($value);
    $this->createProperty($name, $property);
  }

  public function __set($name, $value = null) {
    if (empty($this->_properties[$name])
      || $this->_properties[$name] === null) {

      if ($value !== null) {
        $this->createPropertyByGuessing($name, $value);
      }
      else {
        $this->_properties[$name] = null;
      }
    }
    else {
      parent::__set($name, $value);
    }
  }
}