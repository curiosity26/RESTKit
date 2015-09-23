<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 9/22/15
 * Time: 9:20 PM
 */

namespace RESTKit\Request;


interface HTTPRequestInterface extends BaseRequestInterface
{
  /* Method Constants */
  const METHOD_GET      = 'GET';
  const METHOD_POST     = 'POST';
  const METHOD_PUT      = 'PUT';
  const METHOD_PATCH    = 'PATCH';
  const METHOD_DELETE   = 'DELETE';
  const METHOD_HEAD     = 'HEAD';
  const METHOD_CONNECT  = 'CONNECT';
  const METHOD_JSON     = 'JSON';
}