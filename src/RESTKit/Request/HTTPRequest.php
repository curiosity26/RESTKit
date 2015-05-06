<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 9:50 AM
 */

namespace RESTKit\Request;


use RESTKit\Response\HTTPResponse;

class HTTPRequest {
  protected $ch;
  protected $url;
  protected $port;
  protected $method;
  protected $headers = array();
  protected $body;
  protected $cookie;
  protected $maxRedirects = 10;
  protected $timeout = 10;
  protected $authMethod;
  protected $authCredentials;

  /* Method Constants */
  const METHOD_GET = 'GET';
  const METHOD_POST = 'POST';
  const METHOD_PUT = 'PUT';
  const METHOD_DELETE = 'DELETE';
  const METHOD_HEAD = 'HEAD';
  const METHOD_CONNECT = 'CONNECT';
  const METHOD_JSON = 'JSON';

  /* Authorization Constants */
  const AUTH_ANY = CURLAUTH_ANY;
  const AUTH_ANYSAFE = CURLAUTH_ANYSAFE;
  const AUTH_BASIC = CURLAUTH_BASIC;
  const AUTH_DIGEST = CURLAUTH_DIGEST;
  const AUTH_NTLM = CURLAUTH_NTLM;
  const AUTH_GSSNEGOTIATE = CURLAUTH_GSSNEGOTIATE;
  const AUTH_TOKEN = 'Token';
  const AUTH_OAUTH = 'OAuth';
  const AUTH_BEARER = 'Bearer';
  const AUTH_XAUTHTOKEN = 'X-Auth-Token';


  public function __construct($url = null, $method = 'GET', $data = null, array $headers = null, $port = null)
  {

    if (isset($url)) {
      $this->setUrl($url);
    }

    $this->setMethod($method);
    $this->cookie = tempnam('/tmp', "CURLCOOKIE");

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

  public function setUrl($url)
  {
    $this->url = $url;

    return $this;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setMethod($method)
  {
    if (in_array(
      $method,
      array(
        self::METHOD_CONNECT,
        self::METHOD_DELETE,
        self::METHOD_GET,
        self::METHOD_HEAD,
        self::METHOD_POST,
        self::METHOD_PUT,
        self::METHOD_JSON
      )
    )) {
      $this->method = $method;
    }

    return $this;
  }

  public function getMethod() {
    return $this->method;
  }

  public function setPort($port)
  {
    $this->port = $port;

    return $this;
  }

  public function getPort()
  {
    return $this->port;
  }

  protected function buildHeaders()
  {
    $headers = array();
    foreach ($this->headers as $name => $value) {
      $headers[] = "$name: $value";
    }

    return $headers;
  }

  public function addHeader($name, $value)
  {
    $this->headers[$name] = $value;

    return $this;
  }

  public function removeHeader($name)
  {
    unset($this->headers[$name]);

    return $this;
  }

  public function setHeaders(array $headers)
  {
    $this->headers = $headers;

    return $this;
  }

  public function getHeaders() {
    return $this->headers;
  }

  public function setCookie($cookie)
  {
    $this->cookie = $cookie;

    return $this;
  }

  public function getCookie()
  {
    return $this->cookie;
  }

  /**
   * @param      $authType
   * @param      $username
   * @param null $password
   *
   * @description
   * Authorization and Authentication are two separate operations but the setup
   * is shared under one method because they can't happen at the same time.
   * Authorization must happen before Authentication. Sometimes Authorization
   * happens at the time a token is generated and displayed in a user account on
   * a website. Then the request just needs authorized with the provided token.
   * In an OAuth situation, a client_id and client_secret must be provided with
   * a given set of scopes to a site separate from your application. Once the
   * third party authorizes the user account, a code is generated and passed
   * back to your application via a query string parameter (in most cases). That
   * code would become your token to use for Authorizing your application.
   *
   * In some, more simplistic, methods. The Authorization step doesn't happen at
   * all. Instead, Authentication via an HTTP request with standard HTTP
   * Authentication methods provides the required access. These Authentication
   * credentials usually are passed to along with each request. Though a cookie
   * jar can be used to hold session data in some cases. The nature of this
   * method makes HTTP Authentication vulnerable to attacks where the username
   * and password can be captured and used without your knowledge.
   *
   * OAuth and
   * Token Authentication methods are typically more secure, however, if a token
   * is captured, the service provider usually has a limited window for its use
   * (such as OAuth provides) or the token can be retracted once the breach is
   * deteced.
   */
  public function setHttpAuth($authType, $username, $password = null)
  {
    if (in_array(
      $authType,
      array(
        self::AUTH_ANY,
        self::AUTH_ANYSAFE,
        self::AUTH_BASIC,
        self::AUTH_DIGEST,
        self::AUTH_NTLM,
        self::AUTH_GSSNEGOTIATE
      )
    )) {
      $this->authMethod = $authType;
      $this->authCredentials = $username .(null !== $password ?: ':' . $password);
    }
    elseif (in_array($authType, array(self::AUTH_TOKEN, self::AUTH_OAUTH, self::AUTH_BEARER))) {
      $this->authMethod = null;
      $this->removeHeader('Authentication')
        ->removeHeader('X-Auth-Token')
        ->addHeader('Authorization', "$authType $username"); // The token will be in place of the username
    }
    elseif ($authType === self::AUTH_XAUTHTOKEN) {
      $this->authMethod = null;
      $this->removeHeader('Authentication')
        ->removeHeader('Authorization')
        ->addHeader('X-Auth-Token', $username);
    }
  }

  public function setBody($data)
  {
    $this->body = $data;

    return $this;
  }

  public function getBody()
  {
    return $this->body;
  }

  protected function build()
  {
    if (null === $this->ch) {
      $this->ch = curl_init(); // Initialize before building
    }

    curl_setopt_array(
      $this->ch,
      array(
        CURLOPT_URL => $this->url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_VERBOSE => true,
        CURLOPT_COOKIEJAR => $this->cookie,
        CURLOPT_PORT => isset($this->port) ? $this->port : 80,
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


    if (is_string($this->cookie)) {
      curl_setopt($this->ch, CURLOPT_COOKIE, $this->cookie);
    } else {
      if (is_resource($this->cookie) && get_resource_type($this->cookie) == 'file') {
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookie);
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
   * @return \RESTKit\Response\HTTPResponse
   */
  public function send()
  {
    if ($this->getUrl() === null) {
      throw new \ErrorException("REST URL has not been provided");
    }

    $this->ch = curl_init();
    $this->build();
    $body = curl_exec($this->ch);
    $response = curl_getinfo($this->ch);
    curl_close($this->ch);

    return new HTTPResponse($body, $response);
  }
}