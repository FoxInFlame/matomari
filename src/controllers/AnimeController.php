<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Controllers;

use Matomari\Collections\AnimeInfoCollection;
use Matomari\Collections\AnimeSearchCollection;

/**
 * Controller for anime details. 
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class AnimeController
{

  /**
   * Contains the response from the specifiers.
   * 
   * @var Array
   */
  private $response_array;

  /**
   * Get the overall anime information in detail.
   * 
   * @param Array $get_variables The associative array for additional GET variables
   * @param Array $post_variables The associative array for POST variables
   * @param Integer $anime_id The Anime ID on MAL
   * @OAS\Get(
   *   path="/anime/{animeId}/info",
   *   tags={"Anime"},
   *   summary="Get information about a specific anime using the anime ID",
   *   @OAS\Parameter(
   *     name="animeId",
   *     in="path",
   *     description="The database ID of the anime. This is the ID that is displayed in the URL when you visit the anime page.",
   *     required=true,
   *     @OAS\Schema(
   *       type="integer"
   *     )
   *   ),
   *   @OAS\Response(
   *     response=200,
   *     description="Detailed information about the anime found with the provided ID",
   *     @OAS\MediaType(
   *       mediaType="application/json",
   *       @OAS\Schema(
   *         ref="#/components/schemas/AnimeInfoModel"
   *       )
   *     ),
   *     @OAS\MediaType(
   *       mediaType="application/xml",
   *       @OAS\Schema(
   *         ref="#/components/schemas/AnimeInfoModel"
   *       )
   *     )
   *   ),
   *   @OAS\Response(
   *     response=400,
   *     description="Invalid anime ID"
   *   ),
   *   @OAS\Response(
   *     response=404,
   *     description="No matching anime found"
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
  public function info($get_variables, $post_variables, $anime_id) {
    
    $collection = new AnimeInfoCollection(
      $anime_id
    );
    $this->response_array = $collection->getArray();

  }

  /**
   * Search for anime with optional filters.
   * 
   * @param Array $get_variables The associative array for additional GET variables
   * @param Array $post_variables The associative array for POST variables
   * @param String $query The main query to search for. Can be blank if filters are set.
   * @OAS\Get(
   *   path="/anime/search/{searchQuery}",
   *   tags={"Anime"},
   *   summary="Search for anime by query and optional filters",
   *   @OAS\Parameter(
   *     name="searchQuery",
   *     in="path",
   *     description="The main query to search for. Can be blank if filters are set.",
   *     @OAS\Schema(
   *       type="string"
   *     )
   *   ),
   *   @OAS\Response(
   *     response=200,
   *     description="Detailed list of anime returned by MAL",
   *   ),
   *   @OAS\Response(
   *     response=404,
   *     description="The search page could not be found"
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
   *     response=501,
   *     description="Not yet implemented"
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
  public function search($get_variables, $post_variables, $query='') {

    if($query === '') {
      $query = $get_variables['q'] ?? '';
    }
    // Collection gets the data from cache, or calls a Request,
    // then builds a Model out of it, and finally returns that Model
    $collection = new AnimeSearchCollection(
      $query,
      $get_variables['page'] ?? '1',
      $get_variables['sort'] ?? '',
      [
        'type' => $get_variables['type'] ?? '',
        'score' => $get_variables['score'] ?? '',
        'air_status' => $get_variables['air_status'] ?? '',
        'producer' => $get_variables['producer'] ?? '',
        'classification' => $get_variables['classification'] ?? '',
        'air_dates' => [
          'from' => $get_variables['air_dates_from'] ?? '', // PHP converts variables with dots to underscores
          'to' => $get_variables['air_dates_to'] ?? ''
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