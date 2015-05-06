<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 9:35 AM
 */

namespace RESTKit\Collection;


interface CollectionInterface extends \JsonSerializable, \Countable, \Iterator, \ArrayAccess {
  public function clear();
  public function item($id);
  public function getItemClass();
}