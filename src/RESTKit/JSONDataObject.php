<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 9:00 AM
 */

namespace RESTKit;


use RESTKit\Client\RESTClientInterface;
use RESTKit\Properties\PropertyInterface;

class JSONDataObject extends AbstractDataObject implements \JsonSerializable {

  /**
   * @var \RESTKit\Client\RESTClientInterface
   */
  protected $client;

  public function __construct(array $values = null,
    RESTClientInterface $client = null)
  {

    parent::__construct();

    if (isset($client)) {
      $this->setClient($client);
    }

    if (isset($values)) {
      foreach ($values as $key => $value) {
        $this->__set($key, $value);
      }
    }
  }

  /**
   * @param \RESTKit\Client\RESTClientInterface $client
   * @return $this
   */
  public function setClient(RESTClientInterface $client)
  {
    $this->client = $client;

    return $this;
  }

  /**
   * @return \RESTKit\Client\RESTClientInterface
   */
  public function getClient()
  {
    return $this->client;
  }

  public function JsonSerialize()
  {
    $data = array();

    foreach ($this->_properties as $name => $property) {
      if ($property === null || !($property instanceof PropertyInterface)) {
        continue;
      }

      $data[$name] = $property->__toString();
    }

    return $data;
  }
}