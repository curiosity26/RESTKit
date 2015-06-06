<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 6/6/15
 * Time: 12:08 AM
 */

namespace RESTKit\Properties;


class ArrayProperty extends AbstractProperty {

  protected $delimiter = ",";

  public function getType() {
    return 'array';
  }

  public function setDelimiter($d) {
    $this->delimiter = $d;

    return $this;
  }

  public function getDelimiter() {
    return $this->delimiter;
  }

  public function setDefault($default = array()) {
    $this->default = (array)$default;
  }

  public function set($value = array()) {
    $this->value = (array)$value;
  }

  public function __toString() {
    return implode($this->getDelimiter(), $this->get());
  }
}