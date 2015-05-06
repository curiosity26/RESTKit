<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 4:04 PM
 */

namespace RESTKit\Request;


class JSONRequest extends Request {

  public function __construct($url = null, $data = null, array $headers = array(), $port = 80) {
    $headers['Accept'] = 'application/json';
    parent::__construct($url, HTTPRequest::METHOD_JSON, $data, $headers, $port);
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
}