<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/7/15
 * Time: 3:15 PM
 */

namespace RESTKit\Properties;


class DoubleProperty extends AbstractProperty {

  public function __construct($default = null) {
    $this->setDefault($default);
  }

  public function getType() {
    return 'double';
  }

  public function setDefault($value = null) {
    return parent::setDefault(self::condition($value));
  }

  public function set($value = null) {
    return parent::set(self::condition($value));
  }

  static protected function condition($value = null) {
    if (null !== $value) {
      return doubleval($value);
    }

    return $value;
  }

}