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

  /**
   * @var \RESTKit\Client\RESTClientInterface
   */
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

    return $this;
  }

  /**
   * @return RESTClientInterface
   */
  public function getClient() {
    return $this->client;
  }

  public function send() {
    $client = $this->getClient();

    if (null !== $client) {
      $authType = $client->getAuthType();

      if (in_array($authType, HTTPRequest::getAuthenticationTypes())) {
        $this->setAuthentication($authType, $client->getAccessToken());
      }
      elseif (in_array($authType, HTTPRequest::getAuthorizationTypes())) {
        $this->setAuthorization($authType, $client->getAccessToken());
      }
    }

    $response = parent::send();

    if (null !== $client && method_exists($response, 'setClient')) {
      $response->setClient($this->getClient());
    }

    return $response;
  }
}