<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/7/15
 * Time: 9:10 AM
 */

namespace RESTKit\Request;


use RESTKit\Client\RESTClientInterface;

class ClientRequest extends Request {

  private $client;

  public function __construct(RESTClientInterface $client = null, $url = null,
    $method = "GET", $data = null, array $headers = array(), $port = null) {
    parent::__construct($url, $method, $data, $headers, $port);

    if (null !== $client) {
      $this->setClient($client);
    }
  }

  public function setClient(RESTClientInterface $client) {
    $this->client = $client;
    $authType = $this->client->getAuthType();

    if (in_array($authType, $this->request->getAuthenticationTypes())) {
      $this->setAuthentication($authType, $this->client->getAccessToken());
    }
    elseif (in_array($authType, $this->request->getAuthorizationTypes())) {
      $this->setAuthorization($authType, $this->client->getAccessToken());
    }

    return $this;
  }

  public function getClient() {
    return $this->client;
  }

  public function send() {
    $client = $this->getClient();
    $response = parent::send();
    if (null !== $client && method_exists($response, 'setClient')) {
      $response->setClient($this->getClient());
    }

    return $response;
  }
}