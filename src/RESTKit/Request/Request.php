<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 3:03 PM
 */

namespace RESTKit\Request;

use RESTKit\Request\Curl\CurlRequest;

class Request implements RequestInterface, AuthRequestInterface {

  /**
   * @var HTTPRequestInterface
   */
  protected $request;
  protected $response_class;

  public function __construct($url = null, $method = "GET", $data = null, array $headers = array(), $port = null) {
    if ($url instanceof HTTPRequestInterface) {
      $this->request = $url;
    }
    elseif (function_exists('curl_init')) {
      $this->request = new CurlRequest($url, $method, $data, $headers, $port);
    }
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

  /**
   * @param HTTPRequestInterface $request
   * @return $this
   */
  public function setRequest(HTTPRequestInterface $request) {
    $this->request = $request;

    return $this;
  }

  /**
   * @return HTTPRequestInterface
   */
  public function getRequest() {
    return $this->request;
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

  /**
   * @param $data
   * @return $this|Request
   * @deprecated
   */
  public function setData($data) {
    return $this->setBody($data);
  }

  /**
   * @return mixed
   * @deprecated
   */
  public function getData() {
    return $this->getBody();
  }

  public function setBody($data) {
    $this->request->setBody($data);

    return $this;
  }

  public function getBody() {
    return $this->request->getBody();
  }

  /**
   * @param $cookie
   * @return mixed
   * @deprecated
   */
  public function setCookie($cookie) {
    return $this->setCookies($cookie);
  }

  public function setCookies($cookie) {
    $this->request->setCookies($cookie);

    return $this;
  }

  /**
   * @deprecated
   */
  public function getCookie() {
    return $this->getCookies();
  }

  public function getCookies() {
    return $this->request->getCookies();
  }

  public function setHeaders(array $headers = array()) {
    $this->request->setHeaders($headers);

    return $this;
  }

  public function getHeaders() {
    return $this->request->getHeaders();
  }

  public function addHeader($name, $value) {
    $this->request->addHeader($name, $value);

    return $this;
  }

  public function removeHeader($name) {
    $this->request->removeHeader($name);

    return $this;
  }

  public function setAuthentication($authType, $username, $password = null) {
    $this->request->setAuthentication($authType, $username, $password);
  }

  public function setAuthorization($authMethod, $token) {
    $this->request->setAuthorization($authMethod, $token);
  }

  public function getAuthenticationTypes() {
    return $this->request->getAuthenticationTypes();
  }

  public function getAuthorizationTypes() {
    return $this->request->getAuthorizationTypes();
  }

  /**
   * @return mixed|\RESTKit\Response\ResponseInterface
   */
  public function send() {
    $response = $this->request->send();
    $class = $this->getResponseClass();
    return new $class($response);
  }

}