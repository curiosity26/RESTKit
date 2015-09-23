<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 3:03 PM
 */

namespace RESTKit\Response;


use RESTKit\Client\RESTClientInterface;

class Response implements ResponseInterface {

  protected $code;
  protected $headers;

  /**
   * @var HTTPResponseInterface
   */
  protected $response;
  protected $body;

  /**
   * @var \RESTKit\Client\RESTClientInterface
   */
  protected $client;

  public function __construct(HTTPResponseInterface $response = null)
  {
    if (isset($response)) {
      $this->setResponse($response);
    }
  }

  public function setResponse(HTTPResponseInterface $response)
  {
    $this->response = $response;
    $this->setResponseCode($response->getResponseCode());
    $this->setHeaders($response->getHeaders());
    $this->setBody($response->getResponseBody());

    return $this;
  }

  /**
   * @return \RESTKit\Response\HTTPResponseInterface
   */
  public function getResponse()
  {
    return $this->response;
  }

  public function setResponseCode($code)
  {
    $this->code = $code;

    return $this;
  }

  public function getResponseCode()
  {
    return $this->code;
  }

  public function setHeaders($headers)
  {
    $this->headers = $headers;

    return $this;
  }

  public function getHeaders()
  {
    return $this->headers;
  }

  public function setBody($body)
  {
    $this->body = $body;

    return $this;
  }

  public function getBody()
  {
    return $this->body;
  }

  public function setClient(RESTClientInterface $client) {
    $this->client = $client;

    return $this;
  }

  /**
   * @return \RESTKit\Client\RESTClientInterface
   */
  public function getClient() {
    return $this->client;
  }

  /**
   * @return bool
   */
  public function isSuccess()
  {
    return $this->getResponse()->isSuccess();
  }
}