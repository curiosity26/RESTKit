<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 1:33 PM
 */

namespace RESTKit\Properties;


class ClassProperty extends AbstractProperty {

  protected $type;
  private $construct_args = array();
  private $default_construct_flag = false;

  public function __construct($class, $default = null,
    array $construct_args = array(), $default_construct_flag = false) {
    $this->setType($class);
    $this->setConstructArgs($construct_args);
    $this->setConstructDefaultFlag($default_construct_flag);
    $this->setDefault($default);
  }

  public function setType($type) {
    $this->type = $type;

    return $this;
  }

  public function getType() {
    return $this->type;
  }

  public function setConstructArgs(array $args = array()) {
    $this->construct_args = $args;

    return $this;
  }

  public function getConstructArgs() {
    return $this->construct_args;
  }

  public function setConstructDefaultFlag($flag = false) {
    $this->default_construct_flag = $flag === true;
  }

  public function getConstructDefaultFlag() {
    return $this->default_construct_flag;
  }

  public function setDefault($value = null) {
    return parent::setDefault($this->condition($value));
  }

  public function set($value = null) {
    return parent::set($this->condition($value));
  }

  public function get() {
    $value = parent::get();
    if (null === $value && $this->getConstructDefaultFlag()) {
      $value = $this->createObject();
    }

    return $value;
  }

  protected function condition($value = null) {
    $type = $this->getType();

    if (null !== $value && $value instanceof $type) {
      return $this->createObject();
    }

    return $value;
  }

  protected function createObject() {
    $type = $this->getType();
    $class = new \ReflectionClass($type);
    return $class->newInstanceArgs($this->getConstructArgs());
  }

  public function __toString() {
    $value = $this->get();
    if (null !== $value && method_exists($value, '__toString')) {
      return (string)$value;
    }
    return !is_object($value) ?: $value;
  }

}