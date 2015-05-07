<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 3:03 PM
 */

namespace RESTKit\Request;

class Request {

  protected $request;
  protected $response_class;

  public function __construct($url = null, $method = "GET", $data = null, array $headers = array(), $port = 80) {
    $this->request = new HTTPRequest($url, $method, $data, $headers, $port);

    $this->request->addHeader('Host', isset($_SERVER['HTTP_HOST'])
      ? $_SERVER['HTTP_HOST'] : 'localhost');
  }

  public function setResponseClass($class = null) {
    if (null !== $class
      && in_array('RESTKit\\Response\\Response', class_implements($class))) {
      $this->response_class = $class;
    }
    else {
      $this->response_class = null;
    }

    return $this;
  }

  public function getResponseClass() {
    return isset($this->response_class)
      ? $this->response_class
      : '\\RESTKit\\Response\\Response';
  }

  public function setData($data) {
    $this->request->setBody($data);

    return $this;
  }

  public function getData() {
    return $this->request->getBody();
  }

  public function setCookie($cookie) {
    $this->setCookie($cookie);

    return $this;
  }

  public function getCookie() {
    return $this->request->getCookie();
  }

  public function getHeaders() {
    return $this->request->getHeaders();
  }

  public function setAuthentication($authType, $username, $password = null) {
    $this->request->setHttpAuth($authType, $username, $password);
  }

  public function setAuthorization($authMethod, $token) {
    $this->request->setAuthorization($authMethod, $token);
  }

  /**
   * @return mixed|\RESTKit\Response\Response
   */
  public function send() {
    $response = $this->request->send();
    $class = $this->getResponseClass();
    return new $class($response);
  }

}