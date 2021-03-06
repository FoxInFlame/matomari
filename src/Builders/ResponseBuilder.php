<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Builders;

use Matomari\Components\Request;
use Matomari\Components\Response;

/**
 * Construct a Response object from the responses.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class ResponseBuilder
{

  /**
   * Instance of Response constructed from responses.
   * @var Response
   */
  private $response;

  /**
   * Build Response.
   * 
   * @param Request $request The Request for the request.
   * @param Array $response_array The response associative array.
   * @since 0.5 
   */
  public function build($request, $response_array) {
    
    $response = new Response($request, $response_array);
    $this->response = $response;

  }

  /**
   * Retrieve the built Response.
   * 
   * @return Response
   * @since 0.5
   */
  public function getResponse() {

    return $this->response;

  }
  
}