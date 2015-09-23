<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 9/22/15
 * Time: 9:06 PM
 */

namespace RESTKit\Request;


interface AuthRequestInterface extends BaseRequestInterface
{
  /* Authorization Constants */
  const AUTH_TOKEN = 'Token';
  const AUTH_OAUTH = 'OAuth';
  const AUTH_BEARER = 'Bearer';
  const AUTH_XAUTHTOKEN = 'X-Auth-Token';

  public function setAuthentication($authType, $username, $password);
  public function getAuthenticationTypes();
  public function setAuthorization($authMethod, $token);
  public function getAuthorizationTypes();
}