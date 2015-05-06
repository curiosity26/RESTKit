<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 1:05 PM
 */

namespace RESTKit\Properties;


abstract class AbstractProperty implements PropertyInterface {

  protected $value;
  protected $default;

  public function setDefault($value = null) {
    $this->default = $value;

    return $this;
  }

  public function getDefault() {
    return $this->default;
  }

  public function get() {
    return isset($this->value) ? $this->value : $this->getDefault();
  }

  public function set($value = null) {
    $this->value = $value;

    return $this;
  }

  public function __toString() {
    return $this->get();
  }
}