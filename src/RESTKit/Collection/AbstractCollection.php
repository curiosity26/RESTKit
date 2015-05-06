<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 9:37 AM
 */

namespace RESTKit\Collection;


abstract class AbstractCollection implements CollectionInterface {

  protected $collection = array();

  public function count()
  {
    return count($this->collection);
  }

  public function item($id)
  {
    if (is_numeric($id) && !empty($this->collection[$id])) {
      return $this->collection[$id];
    }

    return null;
  }

  /**
   * @return $this
   */
  public function append($item) {
    $object = $this->formatItem($item);

    if (property_exists($object, 'id') && isset($object->id)) {
      $this->collection[$object->id] = $object;
    }
    else {
      $this->collection[] = $object;
    }
    return $this;
  }

  /**
   * @return $this
   */
  public function remove($item) {
    $object = $this->formatItem($item);
    if (property_exists($object, 'id')) {
      unset($this->collection[$object->id]);
    }
    else {
      if (($index = array_search($object, $this->collection)) != FALSE) {
        unset($this->collection[$index]);
      }
    }
    return $this;
  }


  public function JsonSerialize()
  {
    return array_values($this->collection);
  }

  /**
   * @return bool
   */
  public function valid()
  {
    $key = $this->key();

    return isset($this->collection[$key]);
  }

  public function key()
  {
    return key($this->collection);
  }

  public function current()
  {
    return current($this->collection);
  }

  /**
   * @return $this
   */
  public function next()
  {
    next($this->collection);

    return $this;
  }

  /**
   * @return $this
   */
  public function prev()
  {
    prev($this->collection);

    return $this;
  }

  /**
   * @return $this
   */
  public function rewind()
  {
    reset($this->collection);

    return $this;
  }

  /**
   * @return $this
   */
  public function clear()
  {
    $this->collection = array();

    return $this;
  }

  protected function formatItem($value) {
    if ($class = $this->getItemClass()) {
      if ($value instanceof $class) {
        return $value;
      }
      return new $class((array)$value);
    }
    return $value;
  }

  public function offsetSet($offset, $value)
  {
    if (!is_null($offset) && is_numeric($offset)) {
      if (isset($this->collection[$offset])) {
        $this->collection[$offset] = $this->formatItem($value);
      }
      else {
        $this->append($value);
      }
    }
  }

  public function offsetExists($offset)
  {
    return isset($this->collection[$offset]);
  }

  public function offsetUnset($offset)
  {
    unset($this->collection[$offset]);
  }

  public function offsetGet($offset)
  {
    return $this->item($offset);
  }

  public function __set($name, $value) {
    if ($name == 'collection' && is_array($value)) {
      foreach ($value as $item) {
        $this->append($item);
      }
    }
  }

  public function __get($name) {
    if ($name == 'collection') {
      return $this->collection;
    }
    return NULL;
  }

}