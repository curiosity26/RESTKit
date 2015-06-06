<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 9:30 AM
 */

namespace RESTKit;


use RESTKit\Properties\PropertyInterface;

abstract class AbstractDataObject {
  protected $_properties = array();
  protected $uniqueId;

  public function __construct() {
    $this->initiate();
  }

  public function createProperty($name, PropertyInterface $property) {
    $this->_properties[$name] = $property;

    return $this;
  }

  public function getType($propertyName)
  {
    $property = $this->_properties[$propertyName];
    if (isset($property) && $property instanceof PropertyInterface) {
      return $property->getType();
    }

    return null;
  }

  public function __set($name, $value = null)
  {
    $property = $this->_properties[$name];
    if (null !== $property && $property instanceof PropertyInterface) {
      $property->set($value);
    }
  }

  public function __get($name)
  {
    if ($name == 'id' && empty($this->_properties['id'])) {
      $property = $this->_properties[$this->uniqueId];
    }
    elseif (!empty($this->_properties[$name])){
      $property = $this->_properties[$name];
    }
    else {
      return null;
    }

    if (null !== $property && $property instanceof PropertyInterface) {
      return $property->get();
    }

    return null;
  }

  public function initiate() {}
}