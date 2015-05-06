<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 4:26 PM
 */

namespace RESTKit\Client;


use RESTKit\Request\HTTPRequest;

/**
 * Class OAuthClient
 * @package RESTKit\Client
 * @description
 * This Client is for use with OAuth calls AFTER the
 * client_id and client_secret have been sent to the Authentication rest point
 * and a code has been provided. Use that code as the AccessToken with this client
 */
class OAuthClient extends TokenClient {

  public function getAuthType() {
    return HTTPRequest::AUTH_OAUTH;
  }
}