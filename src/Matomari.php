<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari;

use Matomari\Builders\ResponseBuilder;
use Matomari\Components\Request;
use Matomari\Components\Response;

/**
 * App core class for matomari.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class Matomari
{

  /**
   * Route the Request to specific controllers. (Secondary Routing)
   * Then create a Response instance and send away to finalisation.
   * 
   * @param Request $request The Request constructed from URLRequest
   * @since 0.5
   */
  public function handle(Request $request) {
    $controller_name = $request->getControllerName();

    $class = 'Matomari\\Controllers\\' . $controller_name;
    $controller = new $class();
    $controller->{$request->getSpecifier()}(...$request->getPathVariables());

    $response_builder = new ResponseBuilder();
    $response_builder->build($request, $controller->getResponseArray());
    $this->finalise($response_builder->getResponse());
  }

  /**
   * Finalise the app flow. (Encode into specific formats and Cache)
   * 
   * @param Response $response
   * @since 0.5
   */
  private function finalise(Response $response) {
    $final_response = '';
    if($response->getRequest()->getType() === 'json') {
      header('Content-Type: application/json');
      $final_response = json_encode($response->getResponseArray());
    }

    // Do caching ehre.

    $this->output($final_response);
  }

  /**
   * Echoes the response.
   * 
   * @param Response $response
   * @since 0.5
   */
  private function output(String $response) {
    header('Access-Control-Allow-Origin: *');
    echo $response;
  }

}