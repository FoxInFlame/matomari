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
use Matomari\Collections\MangaRankingCollection;

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
   * @OA\Get(
   *   path="/manga/{mangaId}/info",
   *   tags={"Manga"},
   *   summary="Get manga information",
   *   description="Returns the overall general data for an manga.",
   *   operationId="getMangaInfo",
   *   @OA\Parameter(
   *     name="mangaId",
   *     in="path",
   *     description="The database ID of the manga. This is the ID that is displayed in the URL when you visit the manga page.",
   *     required=true,
   *     @OA\Schema(
   *       type="integer"
   *     )
   *   ),
   *   @OA\Response(
   *     response=200,
   *     description="Detailed information about the manga found with the provided ID",
   *     @OA\MediaType(
   *       mediaType="application/json",
   *       @OA\Schema(
   *         ref="#/components/schemas/MangaInfoModel"
   *       )
   *     ),
   *     @OA\MediaType(
   *       mediaType="application/xml",
   *       @OA\Schema(
   *         ref="#/components/schemas/MangaInfoModel"
   *       )
   *     )
   *   ),
   *   @OA\Response(
   *     response=400,
   *     description="Invalid manga ID"
   *   ),
   *   @OA\Response(
   *     response=404,
   *     description="No matching manga found"
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
 * @OA\Get(
 *   path="/manga/search/{searchQuery}",
 *   tags={"Manga"},
 *   summary="Search for manga",
 *   description="Returns the top results for a general search for manga. It uses the manga.php page. While the top few results will most likely be consistent throughout various attempts, second pages onwards may have shuffling results depending on the occasion. This is due to MAL's algorithm, which seemingly looks completely random. Therefore, don't request each page assuming that you will have a consecutive list.",
 *   operationId="searchManga",
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
 *     description="The media types of manga to filter for",
 *     @OA\Schema(
 *       type="string",
 *       enum={"manga", "novel", "one-shot", "doujinshi", "manhwa", "manhua", "oel"}
 *     )
 *   ),
 *   @OA\Parameter(
 *     name="score",
 *     in="query",
 *     description="The least base integer of the community score of manga to filter for",
 *     @OA\Schema(
 *       type="integer",
 *       minimum="1",
 *       maximum="10"
 *     )
 *   ),
 *   @OA\Parameter(
 *     name="air_status",
 *     in="query",
 *     description="The publishing status of manga to filter for",
 *     @OA\Schema(
 *       type="string",
 *       enum={"publishing", "finished", "not_yet_publishing"}
 *     )
 *   ),
 *   @OA\Parameter(
 *     name="magazine",
 *     in="query",
 *     description="The owner magazine of manga to filter for (TBD)",
 *     @OA\Schema(
 *       type="string"
 *     )
 *   ),
 *   @OA\Parameter(
 *     name="publish_dates.from.year",
 *     in="query",
 *     description="The earliest publish start year to filter out for",
 *     @OA\Schema(
 *       type="string",
 *       example="2014",
 *       pattern="^\d$"
 *     )
 *   ),
 *   @OA\Parameter(
 *     name="publish_dates.from.month",
 *     in="query",
 *     description="The earliest publish start month to filter out for",
 *     @OA\Schema(
 *       type="string",
 *       example="8",
 *       pattern="^\d$"
 *     )
 *   ),
 *   @OA\Parameter(
 *     name="publish_dates.from.day",
 *     in="query",
 *     description="The earliest publish start day to filter out for",
 *     @OA\Schema(
 *       type="string",
 *       example="31",
 *       pattern="^\d$"
 *     )
 *   ),
 *   @OA\Parameter(
 *     name="publish_dates.to.year",
 *     in="query",
 *     description="The latest publish end year to filter out for",
 *     @OA\Schema(
 *       type="string",
 *       example="2014",
 *       pattern="^\d$"
 *     )
 *   ),
 *   @OA\Parameter(
 *     name="publish_dates.to.month",
 *     in="query",
 *     description="The latest publish end month to filter out for",
 *     @OA\Schema(
 *       type="string",
 *       example="8",
 *       pattern="^\d$"
 *     )
 *   ),
 *   @OA\Parameter(
 *     name="publish_dates.to.day",
 *     in="query",
 *     description="The latest publish end day to filter out for",
 *     @OA\Schema(
 *       type="string",
 *       example="31",
 *       pattern="^\d$"
 *     )
 *   ),
 *   @OA\Parameter(
 *     name="letter",
 *     in="query",
 *     description="An alphabetical letter to filter manga starting with it",
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
 *       enum={"action", "adventure", "cars", "comedy", "dementia", "demons", "mystery", "drama", "ecchi", "fantasy", "game", "hentai", "historical", "horror", "kids", "magic", "martialarts", "mecha", "music", "parody", "samurai", "romance", "school", "scifi", "shoujo", "shoujoai", "shounen", "shounenai", "space", "sports", "superpower", "vampire", "yaoi", "yuri", "harem", "sliceoflife", "supernatural", "military", "police", "psychological", "seinen", "josei", "doujinshi", "genderbender", "thriller"},
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
 *     description="Detailed list of manga returned by MAL",
 *     @OA\MediaType(
 *       mediaType="application/json",
 *       @OA\Schema(
 *         title="Results",
 *         @OA\Property(
 *           property="items",
 *           type="array",
 *           description="Array of results",
 *           @OA\Items(
 *             ref="#/components/schemas/MangaSearchModel"
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
 *             ref="#/components/schemas/MangaSearchModel"
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
        'magazine' => $get_variables['magazine'] ?? '',
        'publish_dates' => [
          'from' => [
            'year' => $get_variables['publish_dates_from_year'] ?? '', // PHP converts variables with dots to underscores
            'month' => $get_variables['publish_dates_from_month'] ?? '',
            'day' => $get_variables['publish_dates_from_day'] ?? ''
          ],
          'to' => [
            'year' => $get_variables['publish_dates_to_year'] ?? '',
            'month' => $get_variables['publish_dates_to_month'] ?? '',
            'day' => $get_variables['publish_dates_to_day'] ?? ''
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
   * Get the top ranking list for manga with optional sort parameters.
   * 
   * @param Array $get_variables The associative array for additional GET variables
   * @param Array $post_variables The associative array for POST variables
   * @OA\Get(
   *   path="/manga/ranking",
   *   tags={"Manga"},
   *   summary="Get the top ranking list for manga",
   *   description="Returns 50 manga according to the ranking list provided by MyAnimeList, which depends on the weighted community score.",
   *   operationId="getMangaRanking",
   *   @OA\Parameter(
   *     name="sort",
   *     in="query",
   *     description="The main query to search for. Can be blank if filters are set",
   *     @OA\Schema(
   *       type="string",
   *       enum={"all","manga","novels","one-shots","doujinshi","manhwa","manhua","bypopularity","byfavorites"},
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
   *     description="Detailed list of manga returned by MAL",
   *     @OA\MediaType(
   *       mediaType="application/json",
   *       @OA\Schema(
   *         title="Ranking",
   *         @OA\Property(
   *           property="items",
   *           type="array",
   *           description="Array of ranking results",
   *           @OA\Items(
   *             ref="#/components/schemas/MangaRankingModel"
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
   *             ref="#/components/schemas/MangaRankingModel"
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

    $collection = new MangaRankingCollection(
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