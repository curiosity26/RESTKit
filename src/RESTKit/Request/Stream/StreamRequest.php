<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 9/23/15
 * Time: 7:33 PM
 */

namespace RESTKit\Request\Stream;


use RESTKit\Request\AbstractHTTPRequest;
use RESTKit\Request\Exception\SocketInterruptException;
use RESTKit\Response\Stream\StreamResponse;

class StreamRequest extends AbstractHTTPRequest
{
  protected $raw_header;

  public function compileHeader() {
    $method = $this->getMethod();

    if ($method === self::METHOD_JSON) {
      $method = 'POST';
      $this->addHeader('Content-Type', 'application/json');
    }

    $uri = parse_url($this->getUrl(), PHP_URL_PATH);
    $host = parse_url($this->getUrl(), PHP_URL_HOST);

    $this->raw_header = "$method $uri HTTP/1.1".'\r\n'."Host: $host".'\r\n'.$this->buildHeaders();
  }

  /**
   * @return resource
   */
  protected function build() {
    $url = $this->getUrl();
    $matches = array();

    if ($this->authMethod && $this->authCredentials) {
      $creds = $this->authCredentials;
      if ($this->authMethod === self::AUTH_BASIC) {
        $creds = base64_encode($this->authCredentials);
      }

      $this->addHeader('Authentication', "{$this->authMethod} $creds");
    }

    $method = $this->getMethod();

    if ($method === self::METHOD_JSON) {
      $method = 'POST';
      $this->addHeader('Content-Type', 'application/json');
    }

    $context = array(
      'http' => array(
        'method' => $method,
        'follow_location' => $this->maxRedirects > 0,
        'max_redirects' => $this->maxRedirects,
        'protocol_version' => '1.1',
        'header' => $this->buildHeaders()
      )
    );

    $this->compileHeader();

    $body = $this->getBody();
    if (is_array($body)) {
      $body = http_build_query($body);
    }

    $context['http']['content'] = $body;

    if (preg_match('/^https:\/\/(?<host>[^:\/]+)/', $url, $matches) !== FALSE) {
      $context['ssl'] = array(
        'CN_match' => $matches['host'],
        'verify_peer' => TRUE,
        'verify_peer_name' => TRUE,
        'allow_self_signed' => TRUE
      );
    }

    return stream_context_create($context);
  }

  public function send() {
    $context = $this->build();

    $content = "";
    if ($fp = @fopen($this->getUrl(), 'r+b', false, $context)) {
      $body = $this->getBody();

      if ($body) {
        for ($written = 0; $written < strlen($body); $written += $fwrite) {
          $fwrite = fwrite($fp, substr($body, $written));
          if ($fwrite == false) {
            break;
          }
        }

        if (!strcmp($body, $written)) {
          throw new SocketInterruptException();
        }
      }

      while(!feof($fp)) {
        $line = @fgets($fp);
        $content .= $line;
      }

      fclose($fp);
    }

    $response = new StreamResponse($content);
    $response->setRequestHeader($this->raw_header);

    return $response;
  }
}