<?php

/**
 * A part of the matomari API.
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Components;

/**
 * Class Response
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class Response
{
  
  /**
   * Contains the Request.
   * @var Request
   */
  private $request;


  /**
   * Contains the associative array of the response.
   * @var Array
   */
  private $response_array;

  /**
   * Constructor to build Response.
   * 
   * @param Request $request The Request constructed from the request URL.
   * @param Array $response_array The response array created from Controllers.
   * @since 0.5
   */
  public function __construct($request, $response_array) {

    $this->request = $request;
    $this->response_array = $response_array;

  }

  /** 
   * @return Request
   * @since 0.5
   */
  public function getRequest() {

    return $this->request;

  }

  /** 
   * @return Array
   * @since 0.5
   */
  public function getResponseArray() {

    return $this->response_array;

  }

}