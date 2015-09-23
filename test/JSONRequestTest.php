<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/7/15
 * Time: 1:44 PM
 */

class JSONRequestTest extends PHPUnit_Framework_TestCase {

  public function testConnection() {
    $request = new \RESTKit\Request\JSON\JSONRequest($_ENV['restpoint']);

    if (!empty($_ENV['authtype'])) {
      if (in_array(
        constant('\RESTKit\Request\HTTPRequestInterface::' . $_ENV['authtype']),
        $request->getAuthenticationTypes()
      )) {
        $request->setAuthentication(
          constant('\RESTKit\Request\HTTPRequestInterface::' . $_ENV['authtype']),
          $_ENV['username'],
          !empty($_ENV['password']) ? $_ENV['password'] : NULL
        );
      }
      else {
        $request->setAuthorization(
          constant('\RESTKit\Request\HTTPRequestInterface::' . $_ENV['authtype']),
          $_ENV['accesstoken']
        );
      }
    }

    $response = $request->send();
    $this->assertTrue($response->isSuccess());
  }

  public function testTokenClient() {
    $client = new \RESTKit\Client\TokenClient('token="mytoken"');
    $request = new \RESTKit\Request\JSON\JSONRequest();
    $request->setClient($client);

    // For basic testing, CallRail uses a token auth method
    $request->setUrl("https://api.callrail.com/v1/companies.json");
    $request->send(); // Auth headers aren't generated until the request is sent

    $this->assertContains('Token token="mytoken"', $request->getHeaders());
  }
}
