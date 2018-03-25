<?php


/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Controllers;

use Matomari\Collections\MangaInfoCollection;

/**
 * Controller for manga details. 
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class MangaController
{

   /**
   * Contains the response from the specifiers.
   * 
   * @var Array
   */
  private $responsearray;

  /**
   * Get the overall manga information in detail.
   * 
   * @param Array $get_variables The associative array for additional GET variables
   * @param Array $post_variables The associative array for POST variables
   * @param Integer $manga_id The Manga ID on MAL
   * @since 0.5
   */
  public function info($get_variables, $post_variables, $manga_id) {
    $collection = new MangaInfoCollection(
      $manga_id
    );
    $this->responsearray = $collection->getArray();
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