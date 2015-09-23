<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 9/22/15
 * Time: 8:28 PM
 */

namespace RESTKit\Response;


interface ResponseInterface
{
  public function setResponse(HTTPResponseInterface $response);
  public function getResponse();
  public function setResponseCode($code);
  public function getResponseCode();
  public function setHeaders($headers);
  public function getHeaders();
  public function setBody($data);
  public function getBody();
  public function isSuccess();
}