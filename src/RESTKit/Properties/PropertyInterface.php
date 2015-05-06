<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 1:02 PM
 */

namespace RESTKit\Properties;


interface PropertyInterface {
  public function getType();
  public function setDefault($value = null);
  public function getDefault();
  public function get();
  public function set($value = null);
  public function __toString();
}