<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 1:52 PM
 */

namespace RESTKit\Properties;


class DateTimeProperty extends AbstractProperty {

  private $format = 'c';

  public function __construct($default = null, $format = 'c') {
    $this->setDefault($default);
    $this->setFormat($format);
  }

  public function getType() {
    return '\\DateTime';
  }

  public function setFormat($format = 'c') {
    $this->format = $format;

    return $this;
  }

  public function getFormat() {
    return $this->format;
  }

  public function setDefault($value = null) {
    return parent::setDefault(self::condition($value));
  }

  public function set($value = null) {
    return parent::set(self::condition($value));
  }

  static protected function condition($value = null) {
    if (null !== $value) {
      return $value instanceof \DateTimeInterface ? $value : new \DateTime($value);
    }
    return $value;
  }

  public function __toString() {
    $value = $this->get();
    if (null !== $value && $value instanceof \DateTimeInterface) {
      return $value->format($this->getFormat());
    }

    return $value;
  }
}