<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/7/15
 * Time: 10:03 AM
 */

class HTTPRequestTest extends PHPUnit_Framework_TestCase {

  public function testConnection() {
    $request = new \RESTKit\Request\HTTPRequest($_ENV['restpoint']);

    if (!empty($_ENV['authtype'])) {
      if (in_array(
        constant('\RESTKit\Request\HTTPRequest::' . $_ENV['authtype']),
        $request->getAuthenticationTypes()
      )) {
        $request->setAuthentication(
          constant('\RESTKit\Request\HTTPRequest::' . $_ENV['authtype']),
          $_ENV['username'],
          !empty($_ENV['password']) ? $_ENV['password'] : NULL
        );
      }
      else {
        $request->setAuthorization(
          constant('\RESTKit\Request\HTTPRequest::' . $_ENV['authtype']),
          $_ENV['accesstoken']
        );
      }
    }

    $response = $request->send();

    $this->assertTrue($response->isSuccess());
  }

}
