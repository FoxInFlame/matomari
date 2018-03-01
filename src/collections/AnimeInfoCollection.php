<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Collections;

use GuzzleHttp\Client;
use Matomari\Exceptions\MatomariError;
use Matomari\Collections\Collection;
use Matomari\Builders\DataBuilder;
use Matomari\Parsers\AnimeInfoParser;

/**
 * Collection for anime info - grabs information from cache or URL, and creates Models.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class AnimeInfoCollection extends Collection
{

  /**
   * Create a DataBuilder for the request, and store the result of Parsing that data
   * 
   * @param Integer $anime_id The Anime ID on MAL
   * @since 0.5
   */
  public function __construct($anime_id) {

    // Set the cache key that will be used to find the cache
    $cache_key = 'anime/' . $anime_id . '/info';

    // Initiate a DataBuilder.
    $data_builder = new DataBuilder();

    // Retrieve the data from the cache (using $cache_key), or from MAL using the fallback.
    $data_builder->build($cache_key, function() use ($anime_id) {

      $guzzle_client = new Client();
      $response = $guzzle_client->request('GET', 'https://myanimelist.net/anime/' . $anime_id, [
        'http_errors' => false
      ]);
      

      if($response->getStatusCode() === 429) {
        throw new MatomariError('Too many frequent requests. Please wait and retry.', 429);
      } else if($response->getStatusCode() === 404) {
        throw new MatomariError('Anime with specified ID could not be found.', 404);
      } else if($response->getStatusCode() !== 200) {
        throw new MatomariError('There was an unknown error with MAL. Contact FoxInFlame.', 500);
      }

      // Check if MAL is currently under maintenance (in which case the status code is 200)
      $body = $response->getBody();
      if(!preg_match('/<body [a-zA-Z0-9!@#$&()\\-`.+,\/\"= ]*class="[a-zA-Z0-9!@#$&()\\-`.+,\/\"= ]*page-common/', $body)) {
        throw new MatomariError('MAL is currently under maintenance. Please wait and retry.', 503);
      }

      // Return the Data Arary and the Cache Timeout in seconds.
      return [
        AnimeInfoParser::parse($body),
        3600
      ];

    });

    $this->array = $data_builder->getArray();

  }

}