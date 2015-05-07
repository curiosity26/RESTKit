<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 4:04 PM
 */

namespace RESTKit\Request;


class JSONRequest extends ClientRequest {

  public function __construct($url = null, $data = null, array $headers = array(), $port = null) {
    $headers['Accept'] = 'application/json';
    $headers['Content-Type'] = 'application/json';
    parent::__construct(null, $url, HTTPRequest::METHOD_GET, $data, $headers, $port);
  }

  public function setResponseClass($class = null) {
    if (null !== $class
      && in_array('RESTKit\\Response\\JSONResponse', class_implements($class))) {
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
      : '\\RESTKit\\Response\\JSONResponse';
  }

  public function send() {
    $data = $this->getData();
    if (null !== $data) {
      $this->request->setMethod(HTTPRequest::METHOD_JSON);
    }

    return parent::send();
  }
}