<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 2:48 PM
 */

namespace RESTKit\Collection;


use RESTKit\Client\RESTClientInterface;

class Collection extends AbstractCollection {

  private $itemClass;

  /**
   * @var \RESTKit\Client\RESTClientInterface
   */
  private $client;

  public function __construct(array $values = array()) {
    if (!empty($values)) {
      foreach ($values as $value) {
        $this->append($value);
      }
    }
  }

  public function getItemClass() {
    return isset($this->itemClass) ?
      $this->itemClass : '\\RESTKit\\JSONDataObject';
  }

  public function setItemClass($class = null) {
    $this->itemClass = $class;

    return $this;
  }

  /**
   * @param \RESTKit\Client\RESTClientInterface $client
   * @return $this
   */
  public function setClient(RESTClientInterface $client) {
    $this->client = $client;

    foreach ($this->collection as $item) {
      if (method_exists($item, 'setClient')) {
        $item->setClient($this->getClient());
      }
    }

    return $this;
  }

  /**
   * @return \RESTKit\Client\RESTClientInterface
   */
  public function getClient() {
    return $this->client;
  }

  public function append($item) {
    $object = $this->formatItem($item);
    if (($client = $this->getClient()) !== null
      && method_exists($object, 'setClient')) {
      $object->setClient($client);
    }

    return parent::append($object);
  }

  public function offsetSet($offset, $value) {
    $object = $this->formatItem($value);
    if (($client = $this->getClient()) !== null
      && method_exists($object, 'setClient')) {
      $object->setClient($client);
    }

    parent::offsetSet($offset, $object);
  }
}