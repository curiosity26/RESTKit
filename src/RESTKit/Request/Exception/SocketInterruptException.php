<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 9/24/15
 * Time: 9:03 PM
 */

namespace RESTKit\Request\Exception;


class SocketInterruptException extends \UnderflowException
{
  public function __construct($code = 0, \Exception $previous = null) {
    parent::__construct("The socket connection was interrupted before all the write had completed.", $code, $previous);
  }
}