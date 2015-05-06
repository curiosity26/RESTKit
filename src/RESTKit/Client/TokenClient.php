<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 4:16 PM
 */

namespace RESTKit\Client;


use RESTKit\Request\HTTPRequest;

class TokenClient implements RESTClientInterface {

  private $token;

  public function getAuthType() {
    return HTTPRequest::AUTH_TOKEN;
  }

  public function setAccessToken($token) {
    $this->token = $token;

    return $this;
  }

  public function getAccessToken() {
    return $this->token;
  }
}