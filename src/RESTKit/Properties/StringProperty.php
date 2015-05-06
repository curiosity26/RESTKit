<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 1:22 PM
 */

namespace RESTKit\Properties;


class StringProperty extends AbstractProperty {

  private $length;

  public function __construct($default = null, $length = null) {
    $this->setDefault($default);
    $this->setLength($length);
  }

  public function getType() {
    return 'string';
  }

  public function setLength($length = null) {
    $this->length = $length;

    return $this;
  }

  public function getLength() {
    return $this->length;
  }

  public function setDefault($value = null) {
    return parent::setDefault(self::condition($value));
  }

  public function set($value = null) {
    return parent::set(self::condition($value));
  }

  public function get() {
    if (($length = $this->getLength()) !== null) {
      return substr(parent::get(), 0, $length);
    }
    return parent::get();
  }

  static protected function condition($value = null) {
    if (null !== $value && !is_string($value)) {
      $value = (string)$value;
    }

    return $value;
  }
}