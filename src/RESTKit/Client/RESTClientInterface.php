<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 9:02 AM
 */

namespace RESTKit\Client;


interface RESTClientInterface {

  public function setAccessToken($token);
  public function getAccessToken();
  public function getAuthType();

}