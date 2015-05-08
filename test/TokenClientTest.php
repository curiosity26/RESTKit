<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/7/15
 * Time: 10:39 PM
 */

class TokenClientTest extends PHPUnit_Framework_TestCase {

  public function testHeaders() {
    $client = new \RESTKit\Client\TokenClient();
    $client->setAccessToken("myaccesstoken");

    $this->assertEquals(\RESTKit\Request\HTTPRequest::AUTH_TOKEN, $client->getAuthType());
    $this->assertEquals("myaccesstoken", $client->getAccessToken());
  }
}
