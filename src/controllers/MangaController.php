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
use Matomari\Collections\MangaSearchCollection;

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
   * Search for manga with optional filters.
   * 
   * @param Array $get_variables The associative array for additional GET variables
   * @param Array $post_variables The associative array for POST variables
   * @param Integer $query The main query to search for. Can be blank if filters are set.
   * @since 0.5
   */
  public function search($get_variables, $post_variables, $query='') {
    if($query === '') {
      $query = $get_variables['q'] ?? '';
    }
    // Collection gets the data from cache, or calls a Request,
    // then builds a Model out of it, and finally returns that Model
    $collection = new MangaSearchCollection(
      $query,
      $get_variables['page'] ?? '1',
      $get_variables['sort'] ?? '',
      [
        'type' => $get_variables['type'] ?? '',
        'score' => $get_variables['score'] ?? '',
        'publish_status' => $get_variables['publish_status'] ?? '',
        'magazine' => $get_variables['producer'] ?? '',
        'publish_dates' => [
          'from' => $get_variables['publish_dates_from'] ?? '', // PHP converts variables with dots to underscores
          'to' => $get_variables['publish_dates_to'] ?? ''
        ],
        'letter' => $get_variables['letter'] ?? '',
        'genres' => explode(',', ($get_variables['genres'] ?? '')),
        'exclude_genres' => $get_variables['exclude_genres'] ?? ''
      ]
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