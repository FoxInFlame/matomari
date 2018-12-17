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
use Matomari\Collections\AnimeRankingCollection;

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
   * @OA\Get(
   *   path="/anime/{animeId}/info",
   *   tags={"Anime"},
   *   summary="Get anime information",
   *   description="Returns the overall general data for an anime, from theme songs, image urls, to relationships on other sites.",
   *   operationId="getAnimeInfo",
   *   @OA\Parameter(
   *     name="animeId",
   *     in="path",
   *     description="The database ID of the anime. This is the ID that is displayed in the URL when you visit the anime page.",
   *     required=true,
   *     @OA\Schema(
   *       type="integer"
   *     )
   *   ),
   *   @OA\Response(
   *     response=200,
   *     description="Detailed information about the anime found with the provided ID",
   *     @OA\MediaType(
   *       mediaType="application/json",
   *       @OA\Schema(
   *         ref="#/components/schemas/AnimeInfoModel"
   *       )
   *     ),
   *     @OA\MediaType(
   *       mediaType="application/xml",
   *       @OA\Schema(
   *         ref="#/components/schemas/AnimeInfoModel"
   *       )
   *     )
   *   ),
   *   @OA\Response(
   *     response=400,
   *     description="Invalid anime ID"
   *   ),
   *   @OA\Response(
   *     response=404,
   *     description="No matching anime found"
   *   ),
   *   @OA\Response(
   *     response=429,
   *     description="Too many requests"
   *   ),
   *   @OA\Response(
   *     response=500,
   *     description="Unknown error fetching the data"
   *   ),
   *   @OA\Response(
   *     response=502,
   *     description="Invalid markup on the MyAnimeList server"
   *   ),
   *   @OA\Response(
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
   * @OA\Get(
   *   path="/anime/search/{searchQuery}",
   *   tags={"Anime"},
   *   summary="Search for anime",
   *   description="Returns the top results for a general search for anime. It uses the anime.php page.",
   *   operationId="searchAnime",
   *   @OA\Parameter(
   *     name="searchQuery",
   *     in="path",
   *     description="The main query to search for. Can be blank if filters are set",
   *     @OA\Schema(
   *       type="string"
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="page",
   *     in="query",
   *     description="The results page",
   *     @OA\Schema(
   *       type="integer"
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="sort",
   *     in="query",
   *     description="The sorting algorithm to use (TBD)",
   *     @OA\Schema(
   *       type="string"
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="type",
   *     in="query",
   *     description="The media types of anime to filter for",
   *     @OA\Schema(
   *       type="string",
   *       enum={"tv", "ova", "movie", "special", "ona", "music"}
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="score",
   *     in="query",
   *     description="The base integer of the community score of anime to filter for",
   *     @OA\Schema(
   *       type="integer",
   *       minimum="1",
   *       maximum="10"
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="air_status",
   *     in="query",
   *     description="The airing status of anime to filter for",
   *     @OA\Schema(
   *       type="string",
   *       enum={"currently_airing", "finished_airing", "not_yet_aired"}
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="producer",
   *     in="query",
   *     description="The producer company of anime to filter for (TBD)",
   *     @OA\Schema(
   *       type="string"
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="classification",
   *     in="query",
   *     description="The classification of anime to filter for",
   *     @OA\Schema(
   *       type="string",
   *       enum={"g", "pg", "pg-13", "r", "r+", "rx"}
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="air_dates.from.year",
   *     in="query",
   *     description="The air start year to filter out for",
   *     @OA\Schema(
   *       type="string",
   *       example="2014",
   *       pattern="^\d$"
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="air_dates.from.month",
   *     in="query",
   *     description="The air start month to filter out for",
   *     @OA\Schema(
   *       type="string",
   *       example="8",
   *       pattern="^\d$"
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="air_dates.from.day",
   *     in="query",
   *     description="The air start day to filter out for",
   *     @OA\Schema(
   *       type="string",
   *       example="31",
   *       pattern="^\d$"
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="air_dates.to.year",
   *     in="query",
   *     description="The air end year to filter out for",
   *     @OA\Schema(
   *       type="string",
   *       example="2014",
   *       pattern="^\d$"
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="air_dates.to.month",
   *     in="query",
   *     description="The air end month to filter out for",
   *     @OA\Schema(
   *       type="string",
   *       example="8",
   *       pattern="^\d$"
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="air_dates.to.day",
   *     in="query",
   *     description="The air end day to filter out for",
   *     @OA\Schema(
   *       type="string",
   *       example="31",
   *       pattern="^\d$"
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="letter",
   *     in="query",
   *     description="An alphabetical letter to filter anime starting with it",
   *     @OA\Schema(
   *       type="string",
   *       example="b",
   *       pattern="^[A-z]$"
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="genres",
   *     in="query",
   *     description="Comma separated genres to include or exclude from filters",
   *     @OA\Schema(
   *       type="string",
   *       enum={"action","adventure","cars","comedy","dementia","demons","mystery","drama","ecchi","fantasy","game","hentai","historical","horror","kids","magic","martialarts","mecha","music","parody","samurai","romance","school","scifi","shoujo","shoujoai","shounen","shounenai","space","sports","superpower","vampire","yaoi","yuri","harem","sliceoflife","supernatural","military","police","psychological","thriller","seinen","josei"},
   *       example="fantasy,action,mecha"
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="exclude_genres",
   *     in="query",
   *     description="Include (1) or exclude (0) the specified genres in the filter",
   *     @OA\Schema(
   *       type="integer",
   *       minimum="0",
   *       maximum="1"
   *     )
   *   ),
   *   @OA\Response(
   *     response=200,
   *     description="Detailed list of anime returned by MAL",
   *     @OA\MediaType(
   *       mediaType="application/json",
   *       @OA\Schema(
   *         title="Results",
   *         @OA\Property(
   *           property="items",
   *           type="array",
   *           description="Array of results",
   *           @OA\Items(
   *             ref="#/components/schemas/AnimeSearchModel"
   *           )
   *         )
   *       )
   *     ),
   *     @OA\MediaType(
   *       mediaType="application/xml",
   *       @OA\Schema(
   *         title="Results",
   *         @OA\Property(
   *           property="items",
   *           type="array",
   *           description="Array of results",
   *           @OA\Items(
   *             ref="#/components/schemas/AnimeSearchModel"
   *           )
   *         )
   *       )
   *     )
   *   ),
   *   @OA\Response(
   *     response=404,
   *     description="The search page could not be found"
   *   ),
   *   @OA\Response(
   *     response=429,
   *     description="Too many requests"
   *   ),
   *   @OA\Response(
   *     response=500,
   *     description="Unknown error fetching the data"
   *   ),
   *   @OA\Response(
   *     response=501,
   *     description="Not yet implemented"
   *   ),
   *   @OA\Response(
   *     response=502,
   *     description="Invalid markup on the MyAnimeList server"
   *   ),
   *   @OA\Response(
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
          'from' => [
            'year' => $get_variables['air_dates_from_year'] ?? '', // PHP converts variables with dots to underscores
            'month' => $get_variables['air_dates_from_month'] ?? '',
            'day' => $get_variables['air_dates_from_day'] ?? ''
          ],
          'to' => [
            'year' => $get_variables['air_dates_to_year'] ?? '',
            'month' => $get_variables['air_dates_to_month'] ?? '',
            'day' => $get_variables['air_dates_to_day'] ?? ''
          ]
        ],
        'letter' => $get_variables['letter'] ?? '',
        'genres' => array_filter(explode(',', ($get_variables['genres'] ?? ''))), // array_filter removes the empty one-item array that is the result of an empty string explode
        'exclude_genres' => $get_variables['exclude_genres'] ?? ''
      ]
    );
    $this->response_array = $collection->getArray();

  }

  /**
   * Get the top ranking list for anime with optional sort parameters.
   * 
   * @param Array $get_variables The associative array for additional GET variables
   * @param Array $post_variables The associative array for POST variables
   * @OA\Get(
   *   path="/anime/ranking",
   *   tags={"Anime"},
   *   summary="Get the top ranking list for anime",
   *   description="Returns 50 anime according to the ranking list provided by MyAnimeList, which depends on the weighted community score.",
   *   operationId="getAnimeRanking",
   *   @OA\Parameter(
   *     name="sort",
   *     in="query",
   *     description="The main query to search for. Can be blank if filters are set",
   *     @OA\Schema(
   *       type="string",
   *       enum={"all","airing","upcoming","tv","movie","ova","special","bypopularity","byfavorites"},
   *       example="bypopularity"
   *     )
   *   ),
   *   @OA\Parameter(
   *     name="page",
   *     in="query",
   *     description="The results page",
   *     @OA\Schema(
   *       type="integer"
   *     )
   *   ),
   *   @OA\Response(
   *     response=200,
   *     description="Detailed list of anime returned by MAL",
   *     @OA\MediaType(
   *       mediaType="application/json",
   *       @OA\Schema(
   *         title="Ranking",
   *         @OA\Property(
   *           property="items",
   *           type="array",
   *           description="Array of ranking results",
   *           @OA\Items(
   *             ref="#/components/schemas/AnimeRankingModel"
   *           )
   *         )
   *       )
   *     ),
   *     @OA\MediaType(
   *       mediaType="application/xml",
   *       @OA\Schema(
   *         title="Ranking",
   *         @OA\Property(
   *           property="items",
   *           type="array",
   *           description="Array of ranking results",
   *           @OA\Items(
   *             ref="#/components/schemas/AnimeRankingModel"
   *           )
   *         )
   *       )
   *     )
   *   ),
   *   @OA\Response(
   *     response=404,
   *     description="The search page could not be found"
   *   ),
   *   @OA\Response(
   *     response=429,
   *     description="Too many requests"
   *   ),
   *   @OA\Response(
   *     response=500,
   *     description="Unknown error fetching the data"
   *   ),
   *   @OA\Response(
   *     response=501,
   *     description="Not yet implemented"
   *   ),
   *   @OA\Response(
   *     response=502,
   *     description="Invalid markup on the MyAnimeList server"
   *   ),
   *   @OA\Response(
   *     response=503,
   *     description="MyAnimeList is currently under maintenance"
   *   )
   * )
   * @since 0.5
   */
  public function ranking($get_variables, $post_variables) {

    $collection = new AnimeRankingCollection(
      $get_variables['page'] ?? '1',
      $get_variables['sort'] ?? ''
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