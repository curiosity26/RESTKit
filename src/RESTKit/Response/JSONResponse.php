<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 3:45 PM
 */

namespace RESTKit\Response;

class JSONResponse extends CollectionResponse {

  public function processResponse() {
    return $this->getResponseCode() != 201
      ?   json_decode($this->body)
      :   json_decode($this->getResponse()->getRawResponse());
  }
}