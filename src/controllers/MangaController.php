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
  private $response_array;

  /**
   * Get the overall manga information in detail.
   * 
   * @param Array $get_variables The associative array for additional GET variables
   * @param Array $post_variables The associative array for POST variables
   * @param Integer $manga_id The Manga ID on MAL
   * @OAS\Get(
   *   path="/manga/{mangaId}/info",
   *   tags={"Manga"},
   *   summary="Get manga information",
   *   description="Returns the overall general data for an manga.",
   *   operationId="getMangaInfo",
   *   @OAS\Parameter(
   *     name="mangaId",
   *     in="path",
   *     description="The database ID of the manga. This is the ID that is displayed in the URL when you visit the manga page.",
   *     required=true,
   *     @OAS\Schema(
   *       type="integer"
   *     )
   *   ),
   *   @OAS\Response(
   *     response=200,
   *     description="Detailed information about the manga found with the provided ID",
   *     @OAS\MediaType(
   *       mediaType="application/json",
   *       @OAS\Schema(
   *         ref="#/components/schemas/MangaInfoModel"
   *       )
   *     ),
   *     @OAS\MediaType(
   *       mediaType="application/xml",
   *       @OAS\Schema(
   *         ref="#/components/schemas/MangaInfoModel"
   *       )
   *     )
   *   ),
   *   @OAS\Response(
   *     response=400,
   *     description="Invalid manga ID"
   *   ),
   *   @OAS\Response(
   *     response=404,
   *     description="No matching manga found"
   *   ),
   *   @OAS\Response(
   *     response=429,
   *     description="Too many requests"
   *   ),
   *   @OAS\Response(
   *     response=500,
   *     description="Unknown error fetching the data"
   *   ),
   *   @OAS\Response(
   *     response=502,
   *     description="Invalid markup on the MyAnimeList server"
   *   ),
   *   @OAS\Response(
   *     response=503,
   *     description="MyAnimeList is currently under maintenance"
   *   )
   * )
   * @since 0.5
   */
  public function info($get_variables, $post_variables, $manga_id) {

    $collection = new MangaInfoCollection(
      $manga_id
    );
    $this->response_array = $collection->getArray();

  }

  /**
   * Search for manga with optional filters.
   * 
   * @param Array $get_variables The associative array for additional GET variables
   * @param Array $post_variables The associative array for POST variables
   * @param String $query The main query to search for. Can be blank if filters are set.
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
          'from' => [
            'year' => $get_variables['publish_dates_from_year'] ?? '', // PHP converts variables with dots to underscores
            'month' => $get_variables['publish_dates_from_month'] ?? '',
            'day' => $get_variables['publish_dates_from_date'] ?? ''
          ],
          'to' => [
            'year' => $get_variables['publish_dates_to_year'] ?? '',
            'month' => $get_variables['publish_dates_to_month'] ?? '',
            'day' => $get_variables['publish_dates_to_date'] ?? ''
          ]
        ],
        'letter' => $get_variables['letter'] ?? '',
        'genres' => explode(',', ($get_variables['genres'] ?? '')),
        'exclude_genres' => $get_variables['exclude_genres'] ?? ''
      ]
    );
    $this->response_array = $collection->getArray();

  }

  /**
   * Retrieve the response.
   * 
   * @return Array
   * @since 0.5
   */
  public function getResponseArray() {

    return $this->response_array;
    
  }

}