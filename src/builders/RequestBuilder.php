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
   * ```array(ControllerName, SpecifierName)```
   * 
   * @var Array
   */
  private $routes = [
    '\/anime\/([0-9]*)\/info' => array('AnimeController', 'info')
  ];

  /**
   * Build Request.
   * 
   * @param URLRequest $urlrequest The URLRequest for the request.
   * @since 0.5 
   */
  public function build(URLRequest $urlrequest) {
    $path = $urlrequest->getPath();
    foreach($this->routes as $key => $route) {
      // Only match exact matches (so not first match)
      // Also considered \b but that is for word boundaries, and things with slashes are not
      // considered as one word.
      // https://stackoverflow.com/questions/4026115/regex-word-boundary-but-for-white-space-beginning-of-line-or-end-of-line-only
      if(!preg_match('/(?:^|\s|$)\/(xml|json)' . $key . '(?:^|\s|$)/', $path, $matches)) {
        continue;
      }

      $request = new Request($matches[1], $route[0], $route[1], array_slice($matches, 2));
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