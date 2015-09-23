<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 9/22/15
 * Time: 8:32 PM
 */

namespace RESTKit\Response;


interface HTTPResponseInterface
{
  public function parse($rawResponse, $headerLength = 0);
  public function getResponseCode();
  static public function getResponseStatus($responseCode);
  public function getResponseBody();
  public function getRawHeader();
  public function getHeaders();
  public function getRawResponse();
  public function getRequestHeader();
  public function isSuccess();
}