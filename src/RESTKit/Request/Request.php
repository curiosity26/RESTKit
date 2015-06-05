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

  public function __construct($url = null, $method = "GET", $data = null, array $headers = array(), $port = null) {
    $this->request = new HTTPRequest($url, $method, $data, $headers, $port);
  }

  public function setResponseClass($class = null) {
    if (null !== $class
      && in_array('RESTKit\\Response\\Response', class_parents($class))) {
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

  public function setUrl($url) {
    $this->request->setUrl($url);

    return $this;
  }

  public function getUrl() {
    return $this->request->getUrl();
  }

  public function setMethod($method) {
    $this->request->setMethod($method);

    return $this;
  }

  public function getMethod() {
    return $this->request->getMethod();
  }

  public function setPort($port) {
    $this->request->setPort($port);

    return $this;
  }

  public function getPort() {
    return $this->request->getPort();
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
    $this->request->setAuthentication($authType, $username, $password);
  }

  public function setAuthorization($authMethod, $token) {
    $this->request->setAuthorization($authMethod, $token);
  }

  static public function getAuthenticationTypes() {
    return HTTPRequest::getAuthenticationTypes();
  }

  static public function getAuthorizationTypes() {
    return HTTPRequest::getAuthorizationTypes();
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