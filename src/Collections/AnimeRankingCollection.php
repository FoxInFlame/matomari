<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Collections;

use Sunra\PhpSimple\HtmlDomParser;
use GuzzleHttp\Client;
use Matomari\Exceptions\MatomariError;
use Matomari\Collections\Collection;
use Matomari\Builders\DataBuilder;
use Matomari\Parsers\AnimeRankingParser;

/**
 * Collection for anime ranking - grabs information from cache or URL, and returns Models.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class AnimeRankingCollection extends Collection
{

  /**
   * Various mapping between URL and Request.
   * @var Array
   */
  private $mapping = [
    'sort' => [
      'all' => 'all',
      'airing' => 'airing',
      'upcoming' => 'upcoming',
      'tv' => 'tv',
      'movie' => 'movie',
      'ova' => 'ova',
      'special' => 'special',
      'bypopularity' => 'bypopularity',
      'byfavourite' => 'favorite'
    ]
  ];

  /**
   * Create a DataBuilder for the request, and store the result of Parsing that data
   * 
   * @param Integer $page The raw unparsed page number
   * @param String $sort The raw unparsed sorting specifier
   * @since 0.5
   */
  public function __construct($page, $sort) {

    // Set the cache key that will be used to find the cache
    $cache_key = 'anime/ranking/' . ($this->mapping['sort'][$sort] ?? 'all') . '/' . (string)(int)$page;

    // Initiate a DataBuilder.
    $data_builder = new DataBuilder();

    // Retrieve the data from the cache (using $cache_key), or from MAL using the fallback.
    $data_builder->build($cache_key, function() use ($page, $sort) {

      $parameters = $this->prepareParameters($page, $sort);

      $guzzle_client = new Client();
      $response = $guzzle_client->request('GET', 'https://myanimelist.net/topanime.php', [
        'http_errors' => false,
        'query' => $parameters
      ]);
      

      if($response->getStatusCode() === 429) {
        throw new MatomariError('Too many frequent requests. Please wait and retry.', 429);
      } else if($response->getStatusCode() !== 200) {
        throw new MatomariError('There was an unknown error with MAL. Contact FoxInFlame.', 500);
      }

      // Check if MAL is currently under maintenance (in which case the status code is 200)
      $body = $response->getBody();
      if(!preg_match('/<body [a-zA-Z0-9!@#$&()\\-`.+,\/\"= ]*class="[a-zA-Z0-9!@#$&()\\-`.+,\/\"= ]*page-common/', $body)) {
        throw new MatomariError('MAL is currently under maintenance. Please wait and retry.', 503);
      }

      // Parse the body data.
      $body_array = (new AnimeRankingParser(new HtmlDomParser()))->parse($body);

      // Return the Data Array and the Cache Timeout in seconds.
      return [
        $body_array,
        3600
      ];

    });

    $this->array = $data_builder->getArray();

  }

  /**
   * Prepares all the URL parameters that are to be sent in the MAL ranking request.
   * 
   * @param String $page
   * @param String $sort 
   * @return Array
   * @since 0.5
   */
  private function prepareParameters($page, $sort) {    

    $page_parameter = $this->preparePageParameter($page);

    $sort_parameter = $this->prepareSortParameter($sort);

    return $page_parameter + $sort_parameter;

  }

  /**
   * Prepare the page segment of the Request url.
   * 
   * @param String $page
   * @return Array
   * @since 0.5
   */
  private function preparePageParameter($page) {
    
    // The show parameter is a multiple of 50, and it is how much data is has shown before.
    // So, 0 for the first page, 50 for the second page.
    $show_offset = ((int)$page - 1) * 50;

    return [
      'limit' => $show_offset
    ];

  }

  /**
   * Prepare the sort segment of the Request url.
   * 
   * @param String $sort
   * @return Array
   * @since 0.5
   */
  private function prepareSortParameter($sort) {

    $sort = $this->mapping['sort'][$sort] ?? 'all';
   
    return [
      'type' => $sort
    ];
    
  }

}