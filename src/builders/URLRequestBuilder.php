<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.0.1
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.0.1
 */

namespace Matomari\Builders;

use Matomari\Components\URLRequest;

/**
 * Class URLRequestBuilder
 * Construct a relative URL request from the direct URL request.
 * 
 * @since 0.0.1
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class URLRequestBuilder
{

  /** 
   * Instance of URLRequest constructed from the URL.
   * @var URLRequest
   */
  private $URLRequest;

  /**
   * Build URLRequest
   * 
   * @param String $url The raw dump of the server request URL.
   * @since 0.0.1
   */  
  public function build(String $url) {
    $urlrequest = new URLRequest($url);
    $this->URLRequest = $urlrequest;
  }

  /**
   * Retrieve the built URLRequest.
   * 
   * @return URLRequest
   * @since 0.0.1
   */  
  public function getURLRequest() {
    return $this->URLRequest;
  }

  

}