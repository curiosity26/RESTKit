<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 9/22/15
 * Time: 8:27 PM
 */

namespace RESTKit\Request;


interface BaseRequestInterface
{
  public function setUrl($url);
  public function getUrl();
  public function setMethod($method);
  public function getMethod();
  public function setPort($port);
  public function getPort();
  public function setHeaders(array $headers);
  public function getHeaders();
  public function addHeader($name, $value);
  public function removeHeader($name);
  public function setBody($data);
  public function getBody();
  public function send();
}