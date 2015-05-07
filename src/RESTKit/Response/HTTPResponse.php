<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 9:51 AM
 */

namespace RESTKit\Response;


class HTTPResponse {
  protected $rawResponse;
  protected $rawHeader;
  protected $requestInfo = array();
  protected $response;
  protected $headers = array();

  static $HTTP_RESPONSE_CODES = array(
    100 => "Continue",
    101 => "Switching Protocols",
    200 => "OK",
    201 => "Created",
    202 => "Accepted",
    203 => "Non-Authoritative Information",
    204 => "No Content",
    205 => "Reset Content",
    206 => "Partial Content",
    300 => "Multiple Choices",
    301 => "Moved Permanently",
    302 => "Found",
    303 => "See Other",
    304 => "Not Modified",
    305 => "Use Proxy",
    306 => "(Unused)",
    307 => "Temporary Redirect",
    400 => "Bad Request",
    401 => "Unauthorized",
    402 => "Payment Required",
    403 => "Forbidden",
    404 => "Not Found",
    405 => "Method Not Allowed",
    406 => "Not Acceptable",
    407 => "Proxy Authentication Required",
    408 => "Request Timeout",
    409 => "Conflict",
    410 => "Gone",
    411 => "Length Required",
    412 => "Precondition Failed",
    413 => "Request Entity Too Large",
    414 => "Request-URI Too Long",
    415 => "Unsupported Media Type",
    416 => "Requested Range Not Satisfiable",
    417 => "Expectation Failed",
    500 => "Internal Server Error",
    501 => "Not Implemented",
    502 => "Bad Gateway",
    503 => "Service Unavailable",
    504 => "Gateway Timeout",
    505 => "HTTP Version Not Supported"
  );


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
    if (is_resource($requestInfo) && get_resource_type($requestInfo, 'curl')) {
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

  public function parse($rawResonse, $headerLength = 0)
  {
    $this->rawResponse = $rawResonse;
    $this->rawHeader = substr($rawResonse, 0, $headerLength);
    $this->response = substr($rawResonse, $headerLength);
    $header_lines = explode(PHP_EOL, $this->rawHeader);
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

  static public function getResponseStatus($responseCode)
  {
    return isset(self::$HTTP_RESPONSE_CODES[$responseCode]) ? self::$HTTP_RESPONSE_CODES[$responseCode] : false;
  }

  public function getResonseBody()
  {
    return $this->response;
  }

  public function getRawHeader()
  {
    return $this->rawHeader;
  }

  public function getHeaders()
  {
    return $this->headers;
  }

  public function getRawResponse()
  {
    return $this->rawResponse;
  }

  public function getRequestHeader()
  {
    return isset($this->requestInfo['request_header']) ? $this->requestInfo['request_header'] : false;
  }

  /**
   * @return bool
   */
  public function isSuccess()
  {
    return in_array(
      $this->getResponseCode(),
      array(200, 201, 202, 206, 302, 304)
    ); // 201 is reserved for successful posts
  }
}