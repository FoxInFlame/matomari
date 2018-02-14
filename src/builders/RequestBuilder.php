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
   * ```[ControllerName, SpecifierName]```
   * 
   * @var Array
   */
  private $routes = [
    '\/anime\/([0-9]*)\/info' => ['AnimeController', 'info']
  ];

  /**
   * Build Request, only if the request format fits into one of the predefined $routes
   * 
   * @param Array $server The raw dump of $_SERVER.
   * @since 0.5 
   */
  public function build(Array $server) {
    $request_uri = $server['REQUEST_URI'];
    $path = str_replace('/api/0.5', '', explode('?', $request_uri)[0]);
    $query = explode('?', $request_uri)[1] ?? [];
    if($query) {
      parse_str($query, $get_variables);
    } else {
      $get_variables = [];
    }

    $post_data = file_get_contents('php://input');
    if($post_data) {
      $post_variables = json_decode($post_data, true);
    } else {
      $post_variables = [];
    }

    foreach($this->routes as $key => $route) {
      // Only match exact matches (so not first match)
      // Also considered \b but that is for word boundaries, and things with slashes are not
      // considered as one word.
      // https://stackoverflow.com/questions/4026115/regex-word-boundary-but-for-white-space-beginning-of-line-or-end-of-line-only
      if(!preg_match('/(?:^|\s|$)' . $key . '(?:^|\s|$)/', $path, $matches)) {
        continue;
      }

      if($server['HTTP_ACCEPT'] === 'application/json') {
        $type = 'json';
      } else if($server['HTTP_ACCEPT'] === 'application/xml') {
        $type = 'xml';
      } else {
        $type = 'json';
      }

      // Slice away the first match because that is the whole match (not needed here)
      $request = new Request($type, $route[0], $route[1], array_slice($matches, 1), $get_variables, $post_variables);
      $this->request = $request;
    }

    if(!$this->request) {
      echo 'no such method.';
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