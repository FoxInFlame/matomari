<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Controllers;

/**
 * Controller for anime details. 
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class AnimeController
{

  /**
   * Contains the response of the specifiers.
   * 
   * @var Array
   */
  private $responsearray;

  /**
   * Get the overall anime information in detail.
   * 
   * @param Integer $anime_id The Anime ID on MAL
   * @since 0.5
   */
  public function info($anime_id) {
    $this->responsearray = array(
      'hi there'
    );
  }

  /**
   * Retrieve the response.
   * 
   * @return Array
   * @since 0.5
   */
  public function getResponseArray() {
    return $this->responsearray;
  }

}