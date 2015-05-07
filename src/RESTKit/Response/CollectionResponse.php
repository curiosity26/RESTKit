<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/7/15
 * Time: 2:06 PM
 */

namespace RESTKit\Response;


use RESTKit\Collection\CollectionInterface;

abstract class CollectionResponse extends Response {
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
   * @return mixed
   */
  abstract protected function processResponse();

  /**
   * @return mixed|\RESTKit\Collection\CollectionInterface|$this
   */
  public function getBody() {
    if ($this->isSuccess()) {
      $body = $this->processResponse();

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

    return $this->body;
  }
}