<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 3:45 PM
 */

namespace RESTKit\Response;


use RESTKit\Collection\CollectionInterface;

class JSONResponse extends Response {

  protected $collectionClass;

  public function setCollectionClass($class = null) {
    if (null !== $class &&
      in_array('RESTKit\Collection\CollectionInterface', class_implements($class))) {
      $this->collectionClass = $class;
    }
    else {
      $this->collectionClass = null;
    }

    return $this;
  }

  public function getCollectionClass() {
    return isset($this->collectionClass)
      ? $this->collectionClass : '\\RESTKit\\Collection\\Collection';
  }

  /**
   * @return mixed|\RESTKit\Collection\CollectionInterface|$this
   */
  public function getBody() {
    if ($this->isSuccess()) {
      $body = $this->getResponseCode() != 201
        ?   json_decode(parent::getBody())
        :   json_decode($this->getResponse()->getRawResponse());

      if (is_object($body)) {
        $body = array((array)$body);
      }

      $class = $this->getCollectionClass();
      $collection = new $class($body);

      if (!($collection instanceof CollectionInterface)) {
        throw new \InvalidArgumentException("The provided Collection Class does not support the \\RESTKit\\Collection\\CollectionInterface");
      }

      $collection->rewind();

      return $collection->count() > 1 ? $collection : $collection->current();
    }

    return parent::getBody();
  }
}