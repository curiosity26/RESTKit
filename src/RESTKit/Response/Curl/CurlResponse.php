<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 9:51 AM
 */

namespace RESTKit\Response\Curl;


use RESTKit\Response\AbstractHTTPResponse;

class CurlResponse extends AbstractHTTPResponse {
  protected $requestInfo = array();

  public function __construct($rawResponse = null, $requestInfo = null)
  {
    if (isset($requestInfo)) {
      $this->setRequestInfo($requestInfo);
    }

    if (isset($rawResponse)) {
      $this->parse($rawResponse,
        isset($this->requestInfo['header_size']) && $this->getResponseCode(
        ) != 201 ? $this->requestInfo['header_size'] : 0
      );
    }

  }

  public function setRequestInfo($requestInfo)
  {
    if (is_resource($requestInfo) && get_resource_type($requestInfo) == 'curl') {
      $this->requestInfo = curl_getinfo($requestInfo);
    } else {
      $this->requestInfo = (array)$requestInfo;
    }

    return $this;
  }

  public function getRequestInfo()
  {
    return $this->requestInfo;
  }

  public function parse($rawResponse, $headerLength = 0)
  {
    $this->rawResponse = $rawResponse;
    $this->rawHeader = substr($rawResponse, 0, $headerLength);
    $this->response = substr($rawResponse, $headerLength);
    $header_lines = explode('\r\n', $this->rawHeader);
    $headers = array();
    if (!empty($header_lines)) {
      foreach ($header_lines as $line) {
        if (strpos($line, ':') != false) {
          $header = explode(':', $line);
          $headers[trim($header[0])] = trim($header[1]);
        }
      }
    }
    $this->headers = $headers;
  }

  public function getResponseCode()
  {
    return isset($this->requestInfo['http_code']) ? $this->requestInfo['http_code'] : false;
  }


  public function getRequestHeader()
  {
    return isset($this->requestInfo['request_header']) ? $this->requestInfo['request_header'] : false;
  }
}