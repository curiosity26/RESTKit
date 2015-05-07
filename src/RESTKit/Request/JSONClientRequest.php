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

  public function __construct(RESTClientInterface $client = null, $url = null,
    $data = null, array $headers = array(), $port = null) {
    parent::__construct($url, $data, $headers, $port);
    if (null !== $client) {
      $this->setClient($client);
    }
  }

}