<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 4:30 PM
 */

namespace RESTKit\Request;


use RESTKit\Client\RESTClientInterface;

class JSONClientRequest extends JSONRequest {

  /**
   * @var \RESTKit\Client\RESTClientInterface
   */
  protected $client;

  public function __construct(RESTClientInterface $client = null, $url = null,
    $data = null, array $headers = array(), $port = 80) {
    parent::__construct($url, $data, $headers, $port);
    if (null !== $client) {
      $this->setClient($client);
    }
  }

  public function setClient(RESTClientInterface $client) {
    $this->client = $client;

    $this->setAuth($this->client->getAuthType(), $this->client->getAccessToken());

    return $this;
  }

  public function getClient() {
    return $this->client;
  }
}