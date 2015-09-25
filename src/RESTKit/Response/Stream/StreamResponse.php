<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 9/24/15
 * Time: 7:16 PM
 */

namespace RESTKit\Response\Stream;


use RESTKit\Response\AbstractHTTPResponse;

class StreamResponse extends AbstractHTTPResponse
{
  protected $response_code = 400; // If this is not set in parsing, then it's a bad request, M'kay?
  protected $request_header;

  public function __construct($raw_response = null) {
    if ($raw_response) {
      $this->parse($raw_response);
    }
  }

  public function parse($content) {
    $parts = preg_split('/\n\n/', $content);
    $body = array_pop($parts);
    $head = array_pop($parts);
    $raw_headers = explode('\r\n', $head);
    $http = array_shift($raw_headers);
    $code = 400; // If we can't parse the response code, then it's a bad request
    $matches = array();

    if (preg_match('/HTTP\/\d.\d\s(?<code>\d{3})\s(?<message>.*)$/', $http, $matches) !== false) {
      $code = $matches['code'];
    }

    $headers = array();
    foreach ($raw_headers as $header) {
      $split = explode(':', $header);
      $headers[trim($split[0])] = trim($split[1]);
    }

    $this->rawResponse = $content;
    $this->response_code = $code;
    $this->rawHeader = $head;
    $this->headers = $headers;
    $this->response = $body;
  }

  public function getResponseCode() {
    return $this->response_code;
  }

  public function setRequestHeader($header) {
    $this->request_header = $header;

    return $this;
  }

  public function getRequestHeader() {
    return $this->request_header;
  }

}