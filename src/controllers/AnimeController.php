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
   *   summary="Get anime information",
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
   *   summary="Search for anime",
   *   @OAS\Parameter(
   *     name="searchQuery",
   *     in="path",
   *     description="The main query to search for. Can be blank if filters are set",
   *     @OAS\Schema(
   *       type="string"
   *     )
   *   ),
   *   @OAS\Parameter(
   *     name="page",
   *     in="query",
   *     description="The results page",
   *     @OAS\Schema(
   *       type="integer"
   *     )
   *   ),
   *   @OAS\Parameter(
   *     name="sort",
   *     in="query",
   *     description="The sorting algorithm to use (TBD)",
   *     @OAS\Schema(
   *       type="string"
   *     )
   *   ),
   *   @OAS\Parameter(
   *     name="type",
   *     in="query",
   *     description="The media types of anime to filter for",
   *     @OAS\Schema(
   *       type="string",
   *       enum={"tv", "ova", "movie", "special", "ona", "music"}
   *     )
   *   ),
   *   @OAS\Parameter(
   *     name="score",
   *     in="query",
   *     description="The base integer of the community score of anime to filter for",
   *     @OAS\Schema(
   *       type="integer",
   *       minimum="1",
   *       maximum="10"
   *     )
   *   ),
   *   @OAS\Parameter(
   *     name="air_status",
   *     in="query",
   *     description="The airing status of anime to filter for",
   *     @OAS\Schema(
   *       type="string",
   *       enum={"currently_airing", "finished_airing", "not_yet_aired"}
   *     )
   *   ),
   *   @OAS\Parameter(
   *     name="producer",
   *     in="query",
   *     description="The producer company of anime to filter for (TBD)",
   *     @OAS\Schema(
   *       type="string"
   *     )
   *   ),
   *   @OAS\Parameter(
   *     name="classification",
   *     in="query",
   *     description="The classification of anime to filter for",
   *     @OAS\Schema(
   *       type="string",
   *       enum={"g", "pg", "pg-13", "r", "r+", "rx"}
   *     )
   *   ),
   *   @OAS\Parameter(
   *     name="air_dates.from",
   *     in="query",
   *     description="The air start date to filter for (YYYY-MM-DD), with unspecified parts represented as '-' (hyphen)",
   *     @OAS\Schema(
   *       type="string",
   *       example="2014----",
   *       pattern="^\d{1,4}-\d{1,2}-\d{1,2}$"
   *     )
   *   ),
   *   @OAS\Parameter(
   *     name="air_dates.to",
   *     in="query",
   *     description="The air end date to filter for (YYYY-MM-DD), with unspecified parts represented as '-' (hyphen)",
   *     @OAS\Schema(
   *       type="string",
   *       example="2015-10--",
   *       pattern="^\d{1,4}-\d{1,2}-\d{1,2}$"
   *     )
   *   ),
   *   @OAS\Parameter(
   *     name="letter",
   *     in="query",
   *     description="An alphabetical letter to filter anime starting with it",
   *     @OAS\Schema(
   *       type="string",
   *       example="b",
   *       pattern="^[A-z]$"
   *     )
   *   ),
   *   @OAS\Parameter(
   *     name="genres",
   *     in="query",
   *     description="Comma separated genres to include or exclude from filters",
   *     @OAS\Schema(
   *       type="string",
   *       enum={"action","adventure","cars","comedy","dementia","demons","mystery","drama","ecchi","fantasy","game","hentai","historical","horror","kids","magic","martialarts","mecha","music","parody","samurai","romance","school","scifi","shoujo","shoujoai","shounen","shounenai","space","sports","superpower","vampire","yaoi","yuri","harem","sliceoflife","supernatural","military","police","psychological","thriller","seinen","josei","default"},
   *       example="fantasy,action,mecha"
   *     )
   *   ),
   *   @OAS\Parameter(
   *     name="exclude_genres",
   *     in="query",
   *     description="Include (1) or exclude (0) the specified genres in the filter",
   *     @OAS\Schema(
   *       type="integer",
   *       minimum="0",
   *       maximum="1"
   *     )
   *   ),
   *   @OAS\Response(
   *     response=200,
   *     description="Detailed list of anime returned by MAL",
   *     @OAS\MediaType(
   *       mediaType="application/json",
   *       @OAS\Schema(
   *         type="object",
   *         description="Array of results",
   *         @OAS\Property(
   *           property="items",
   *           ref="#/components/schemas/AnimeSearchModel"
   *         )
   *       )
   *     ),
   *     @OAS\MediaType(
   *       mediaType="application/xml",
   *       @OAS\Schema(
   *         type="object",
   *         description="Array of results",
   *         @OAS\Property(
   *           property="items",
   *           ref="#/components/schemas/AnimeSearchModel"
   *         )
   *       )
   *     )
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

    // Use the 'q' URL parameter if that's present
    // This will allow some clients to construct queries easier from arrays of parameters 
    $query = $get_variables['q'] ?? $query;

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