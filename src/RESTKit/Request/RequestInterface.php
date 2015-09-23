<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 9/22/15
 * Time: 9:27 PM
 */

namespace RESTKit\Request;


interface RequestInterface extends BaseRequestInterface
{
  public function setResponseClass($class);
  public function getResponseClass();
}