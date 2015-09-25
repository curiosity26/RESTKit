<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 9:50 AM
 */

namespace RESTKit\Request\Curl;


use RESTKit\Request\AbstractHTTPRequest;
use RESTKit\Response\Curl\CurlResponse;

class CurlRequest extends AbstractHTTPRequest {
  protected $ch;

  /* Authentication Constants */
  const HTTP_AUTH_ANY = CURLAUTH_ANY;
  const HTTP_AUTH_ANYSAFE = CURLAUTH_ANYSAFE;
  const HTTP_AUTH_BASIC = CURLAUTH_BASIC;
  const HTTP_AUTH_DIGEST = CURLAUTH_DIGEST;
  const HTTP_AUTH_NTLM = CURLAUTH_NTLM;
  const HTTP_AUTH_GSSNEGOTIATE = CURLAUTH_GSSNEGOTIATE;


  public function __construct($url = null, $method = 'GET', $data = null, array $headers = null, $port = null)
  {

    if (isset($url)) {
      $this->setUrl($url);
    }

    $this->setMethod($method);
    $this->cookies = tempnam('/tmp', "RKCOOKIE");

    if (isset($data)) {
      $this->setBody($data);
    }

    if (isset($headers)) {
      $this->setHeaders($headers);
    }

    if (isset($port)) {
      $this->setPort($port);
    }
  }

  public function getAuthenticationTypes() {
    return  array(
      self::HTTP_AUTH_ANY,
      self::HTTP_AUTH_ANYSAFE,
      self::HTTP_AUTH_BASIC,
      self::HTTP_AUTH_DIGEST,
      self::HTTP_AUTH_NTLM,
      self::HTTP_AUTH_GSSNEGOTIATE
    );
  }

  protected function build()
  {
    if (null === $this->ch) {
      $this->ch = curl_init(); // Initialize before building
    }

    curl_setopt_array(
      $this->ch,
      array(
        CURLOPT_URL => $this->getUrl(),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_VERBOSE => true,
        CURLOPT_COOKIEJAR => $this->getCookies(),
        CURLOPT_PORT => isset($this->port) ? $this->getPort() : 80,
        CURLOPT_FAILONERROR => false,
        CURLOPT_TIMEOUT => $this->timeout,
        CURLOPT_MAXREDIRS => $this->maxRedirects,
        CURLOPT_AUTOREFERER => true,
        CURLINFO_HEADER_OUT => true
      )
    );

    if (preg_match('/^https:/', $this->url) !== false) {
      curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
      if (!isset($this->port)) {
        $this->port = 443;
        curl_setopt($this->ch, CURLOPT_PORT, $this->port);
      }
    }

    if (is_string($this->cookies)) {
      if (file_exists($this->cookies)) {
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookies);
      }
      else {
        curl_setopt($this->ch, CURLOPT_COOKIE, $this->cookies);
      }
    }

    if ($this->method == self::METHOD_PUT && get_resource_type($this->body) == 'file') {
      curl_setopt($this->ch, CURLOPT_INFILE, $this->body);
    } elseif (is_string($this->body) || is_array($this->body)) {
      curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->body);
      if (is_string($this->body)) {
        $this->addHeader('Content-Length', strlen($this->body));
      }
    }

    if (isset($this->authMethod)) {
      curl_setopt($this->ch, CURLOPT_HTTPAUTH, $this->authMethod);
      curl_setopt($this->ch, CURLOPT_USERPWD, $this->authCredentials);
    }

    switch ($this->method) {
      case self::METHOD_POST:
        curl_setopt($this->ch, CURLOPT_POST, true);
        break;
      case self::METHOD_PUT:
        ;
        curl_setopt($this->ch, CURLOPT_PUT, true);
        break;
      case self::METHOD_HEAD:
      case self::METHOD_DELETE:
      case self::METHOD_CONNECT:
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $this->method);
        break;
      case self::METHOD_JSON:
        curl_setopt(
          $this->ch,
          CURLOPT_CUSTOMREQUEST,
          "POST"
        ); // Posting JSON Data needs to POST while sidestepping CURLOPT_POST
        $this->addHeader('Content-Type', 'application/json');
        break;
      default:
        curl_setopt($this->ch, CURLOPT_HTTPGET, true);
        curl_setopt($this->ch, CURLOPT_HEADER, $this->buildHeaders());
    }

    curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->buildHeaders());
  }

  /**
   * @return \RESTKit\Response\Curl\CurlResponse
   */
  public function send()
  {
    if ($this->getUrl() === null) {
      throw new \RuntimeException("REST URL has not been provided");
    }

    $this->ch = curl_init();
    $this->build();
    $body = curl_exec($this->ch);
    $response = curl_getinfo($this->ch);
    curl_close($this->ch);

    return new CurlResponse($body, $response);
  }
}