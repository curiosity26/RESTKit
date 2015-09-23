<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 9/22/15
 * Time: 9:54 PM
 */

namespace RESTKit\Request;


abstract class AbstractHTTPRequest implements HTTPRequestInterface, AuthRequestInterface
{
  protected $url;
  protected $port;
  protected $method;
  protected $headers = array();
  protected $body;
  protected $cookies;
  protected $maxRedirects = 10;
  protected $timeout = 10;
  protected $authMethod;
  protected $authCredentials;

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
        self::METHOD_PATCH,
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

  public function buildHeaders()
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

  /**
   * @param $cookie
   * @return $this
   * @deprecated
   */
  public function setCookie($cookie) {
    return $this->setCookies($cookie);
  }

  /**
   * @param $cookie
   * @return $this
   */
  public function setCookies($cookie)
  {
    $this->cookies = $cookie;

    return $this;
  }

  public function getCookies()
  {
    return $this->cookies;
  }

  /**
   * @param      $authType
   * @param      $username
   * @param null $password
   *
   * @description
   * Authorization and Authentication are two separate operations.
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
   * OAuth and Token Authentication methods are typically more secure, however,
   * if a token is captured, the service provider usually has a limited window
   * for its use (such as OAuth provides) or the token can be retracted once the
   * breach is detected.
   */
  public function setAuthentication($authType, $username, $password = null)
  {
    if (in_array(
      $authType,
      $this->getAuthenticationTypes()
    )) {
      $this->authMethod = $authType;
      $this->authCredentials = $username.(null !== $password ? ':' . $password : null);
    }
  }

  public function setAuthorization($authMethod, $token) {
    if (in_array($authMethod, array(self::AUTH_TOKEN, self::AUTH_OAUTH, self::AUTH_BEARER))) {
      $this->authMethod = null;
      $this->removeHeader('X-Auth-Token')
        ->addHeader('Authorization', "$authMethod $token");
    }
    elseif ($authMethod === self::AUTH_XAUTHTOKEN) {
      $this->authMethod = null;
      $this->removeHeader('Authorization')
        ->addHeader('X-Auth-Token', $token);
    }
  }

  public function getAuthorizationTypes() {
    return array(
      self::AUTH_TOKEN,
      self::AUTH_OAUTH,
      self::AUTH_BEARER,
      self::AUTH_XAUTHTOKEN
    );
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
}