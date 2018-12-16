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
use Matomari\Components\URLRequest;
use Matomari\Exceptions\MatomariError;

/**
 * Construct a Request object from relative URL request.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class RequestBuilder
{

  /**
   * Instance of Request constructed from URLRequest.
   * @var Request
   */
  private $request;

  /**
   * Files and controllers to route to. (Primary Routing)
   * ```[CheckMethod, ControllerName, SpecifierName]```
   * 
   * @var Array
   */
  private $routes = [
    '\/' => ['GET', 'ApiController', 'specs'],
    '\/anime\/([0-9]*)' => ['GET', 'AnimeController', 'info'],
    '\/anime\/([0-9]*)\/info' => ['GET', 'AnimeController', 'info'],
    '\/anime\/search' => ['GET', 'AnimeController', 'search'],
    '\/anime\/search\/(.*)' => ['GET', 'AnimeController', 'search'],
    '\/anime\/ranking' => ['GET', 'AnimeController', 'ranking'],
    '\/manga\/([0-9]*)' => ['GET', 'MangaController', 'info'],
    '\/manga\/([0-9]*)\/info' => ['GET', 'MangaController', 'info'],
    '\/manga\/search' => ['GET', 'MangaController', 'search'],
    '\/manga\/search\/(.*)' => ['GET', 'MangaController', 'search'],
    '\/manga\/ranking' => ['GET', 'MangaController', 'ranking'],
  ];

  /**
   * Array that will fill up with possible methods, when matching endpoints are foud with
   * found with no matching methods
   * 
   * @var Array
   */
  private $accepted_methods = [];

  /**
   * Turn query string into variable array, and return an empty array if no query.
   * 
   * @param String $query The part after '?'
   * @since 0.5
   */
  private function queryToGetVariables($query) {
    if($query) {
      parse_str($query, $get_variables);
      return $get_variables;
    }
    return [];
  }

  /**
   * Turn POST input JSON into variable array, and return an empty array if no query.
   * 
   * @param String $post_data Direct content of php://input
   * @since 0.5
   */
  private function inputToPostVariables($post_data) {
    if($post_data) {
      return json_decode($post_data, true);
    }
    return [];
  }

  /**
   * Parse the SERVER variable for Accept headers, and return the type. This will be used for
   * determining which type of data to respond with.
   * 
   * @param Array $server The raw dump of $_SERVER
   * @since 0.5
   */
  private function parseAcceptHeaders($server) {
    if($server['HTTP_ACCEPT'] === 'application/json') {
      return 'json';
    }
    if($server['HTTP_ACCEPT'] === 'application/xml') {
      return 'xml';
    }
    return 'json';
  }


  /**
   * Build Request, only if the request format fits into one of the predefined $routes
   * 
   * @param Array $server The raw dump of $_SERVER.
   * @since 0.5 
   */
  public function build($server) {

    $request_uri = $server['REQUEST_URI'];
    $path = str_replace('/api/0.5', '', explode('?', $request_uri)[0]);
    $query = explode('?', $request_uri)[1] ?? ''; // Set to empty string even if ? doesn't exist
    $get_variables = $this->queryToGetVariables($query);

    $post_data = file_get_contents('php://input');
    $post_variables = $this->inputToPostVariables($post_data);

    $type = $this->parseAcceptHeaders($server);

    foreach($this->routes as $key => $route) {
      // Only match exact matches (so not first match)
      // Also considered \b but that is for word boundaries, and things with slashes are not
      // considered as one word.
      // https://stackoverflow.com/questions/4026115/regex-word-boundary-but-for-white-space-beginning-of-line-or-end-of-line-only
      if(!preg_match('/(?:^|\s|$)' . $key . '(?:^|\s|$)/', $path, $matches)) {
        continue;
      }

      // Check requests method.
      if($server['REQUEST_METHOD'] !== $route[0]) {
        // The route existed, but the method was different, so tell user with Allow Header.
        array_push($this->accepted_methods, $route[0]);
        continue;
      }

      // Slice away the first match because that is the whole match (not needed here)
      $request = new Request($type, $route[1], $route[2], (array_slice($matches, 1) ?? []), $get_variables, $post_variables);
      $this->request = $request;
      break;
    }

    if(!$this->request) {
      if($this->accepted_methods) {
        header('Allow: ' . implode(', ', $this->accepted_methods));
        throw new MatomariError('No such method.', 405);
      } else {
        throw new MatomariError('No such method.', 404);
      }
    }
    
  }

  /**
   * Retrieve the built Request.
   * 
   * @return Request
   * @since 0.5
   */
  public function getRequest() {
    
    return $this->request;

  }
  
}