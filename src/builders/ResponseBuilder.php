<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.0.1
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.0.1
 */

namespace Matomari\Builders;

use Matomari\Components\Request;
use Matomari\Components\Response;

/**
 * Construct a Response object from the responses.
 * 
 * @since 0.0.1
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
   * @param Array $responsearray The response associative array.
   * @since 0.0.1 
   */
  public function build(Request $request, Array $responsearray) {
    
    $response = new Response($request, $responsearray);
    $this->response = $response;

  }

  /**
   * Retrieve the built Response.
   * 
   * @return Response
   * @since 0.0.1
   */
  public function getResponse() {
    return $this->response;
  }
  
}