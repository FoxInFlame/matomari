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

use Spatie\ArrayToXml\ArrayToXml;

/**
 * App core class for matomari.
 * The Swagger documentation for the overall API is located inside ApiSpecsCollection.
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
  public function handle($request) {
    $controller_name = $request->getControllerName();

    // Calling dynamic namespaces isn't supported by Composer's 'use' syntax.
    // Thus, the entire path to the namespace has to be provided.
    $class = 'Matomari\\Controllers\\' . $controller_name;
    $controller = new $class();

    $controller->{$request->getSpecifier()}($request->getGetVariables(), $request->getPostVariables(), ...$request->getPathVariables());

    $response_builder = new ResponseBuilder();
    $response_builder->build($request, $controller->getResponseArray());
    $this->finalise($response_builder->getResponse());
  }

  /**
   * Finalise the app flow. (Encode into specific formats)
   * 
   * @param Response $response
   * @since 0.5
   */
  private function finalise($response) {

    $final_response = '';
    if($response->getRequest()->getType() === 'json') {
      header('Content-Type: application/json');
      $final_response = json_encode($response->getResponseArray());
    } else if($response->getRequest()->getType() === 'xml') {
      header('Content-Type: application/xml');
      $final_response = ArrayToXml::convert($response->getResponseArray());
    }

    // Do caching here.

    $this->output($final_response);
    
  }

  /**
   * Echoes the response.
   * 
   * @param String $response
   * @since 0.5
   */
  private function output($response) {

    header('Access-Control-Allow-Origin: *');
    echo $response;

  }

}