<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 1:11 PM
 */

namespace RESTKit\Properties;


class IntegerProperty extends AbstractProperty {

  public function __construct($default = null) {
    $this->setDefault($default);
  }

  public function getType() {
    return 'int';
  }

  public function setDefault($value = null) {
    return parent::setDefault(self::condition($value));
  }

  public function set($value = null) {
    return parent::set(self::condition($value));
  }

  static protected function condition($value = null) {
    if (null !== $value) {
      return (int)$value;
    }

    return $value;
  }
}