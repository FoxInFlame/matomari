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
use Matomari\Parsers\MangaSearchParser;

/**
 * Collection for manga search - grabs information from cache or URL, and returns Models.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class MangaSearchCollection extends Collection
{

  /**
   * Various mapping between URL and Request.
   * @var Array
   */
  private $mapping = [
    'type' => [
      'manga' => '1',
      'novel' => '2',
      'one-shot' => '3',
      'doujinshi' => '4',
      'manhwa' => '5',
      'manhua' => '6',
      'oel' => '7',
      'default' => '0'
    ],
    'publish_status' => [
      'publishing' => '1',
      'finished' => '2',
      'not_yet_published' => '3',
      'default' => '0'
    ],
    'genres' => [
      'action' => '1',
      'adventure' => '2',
      'cars' => '3',
      'comedy' => '4',
      'dementia' => '5',
      'demons' => '6',
      'mystery' => '7',
      'drama' => '8',
      'ecchi' => '9',
      'fantasy' => '10',
      'game' => '11',
      'hentai' => '12',
      'historical' => '13',
      'horror' => '14',
      'kids' => '15',
      'magic' => '16',
      'martialarts' => '17',
      'mecha' => '18',
      'music' => '19',
      'parody' => '20',
      'samurai' => '21',
      'romance' => '22',
      'school' => '23',
      'scifi' => '24',
      'shoujo' => '25',
      'shoujoai' => '26',
      'shounen' => '27',
      'shounenai' => '28',
      'space' => '29',
      'sports' => '30',
      'superpower' => '31',
      'vampire' => '32',
      'yaoi' => '33',
      'yuri' => '34',
      'harem' => '35',
      'sliceoflife' => '36',
      'supernatural' => '37',
      'military' => '38',
      'police' => '39',
      'psychological' => '40',
      'seinen' => '41',
      'josei' => '42',
      'doujinshi' => '43',
      'genderbender' => '44',
      'thriller' => '45',
      'default' => ''
    ]
  ];

  /**
   * Parse the page, sort and filters sent by the client into MAL URL queries.
   * Create a DataBuilder for the request, and store the result of Parsing that data.
   * 
   * @param String $query The query to search for
   * @param String $page The raw unparsed page number 
   * @param String $sort The raw unparsed sorting specifier
   * @param Array $filters The filters, parsed into an associative array
   *  $filter = [
   *    'type' => (string) Show results matching the media type
   *    'score' => (string) Show results with scores above the score
   *    'publish_status' => (string) Show results matching the airing status
   *    'magazine' => (string) TBD
   *    'publish_dates' => [
   *      'from' => (string) Show results which start publishing from the date
   *      'to' => (string) Show results which end publishing on the date
   *    ]
   *    'letter' => (string) Show results that start with the alphabet letter
   *    'genres' => (array) 
   *    'exclude_genres' => (string) Either include or exclude the previous array of genres
   *  ]
   * @since 0.5
   */
  public function __construct($query, $page, $sort, $filters) {

    // Create an array of URL parameters for sending the MAL request.
    $parameters = $this->prepare_parameters($query, $page, $sort, $filters);

    // Generate the cache key using the parameters sent into this function.
    $cache_key = $this->generate_cache_key($query, $page, $sort, $filters);

    // Initiate a DataBuilder.
    $data_builder = new DataBuilder();

    // Retrieve the data from the cache (using $cache_key), or from MAL using the fallback.
    $data_builder->build($cache_key, function() use ($parameters) {

      $guzzle_client = new Client();
      $response = $guzzle_client->request('GET', 'https://myanimelist.net/manga.php', [
        'http_errors' => false,
        'query' => $parameters
      ]);

      if($response->getStatusCode() === 429) {
        throw new MatomariError('Too many frequent requests. Please wait and retry.', 429);
      } else if($response->getStatusCode() === 404) {
        throw new MatomariError('The page could not be found.', 404);
      } else if($response->getStatusCode() !== 200) {
        throw new MatomariError('There was an unknown error with MAL. Contact FoxInFlame.', 500);
      }

      // Check if MAL is currently under maintenance (in which case the status code is 200)
      $body = $response->getBody();
      if(!preg_match('/<body [a-zA-Z0-9!@#$&()\\-`.+,\/\"= ]*class="[a-zA-Z0-9!@#$&()\\-`.+,\/\"= ]*page-common/', $body)) {
        throw new MatomariError('MAL is currently under maintenance. Please wait and retry.', 503);
      }

      // Parse the body data.
      $body_array = (new MangaSearchParser(new HtmlDomParser()))->parse($body);

      // Return the Data Array and the Cache Timeout in seconds.
      return [
        $body_array,
        3600
      ];
      
    });
  
    $this->array = $data_builder->getArray();

  }

  /**
   * Prepares all the URL parameters that are to be sent in the MAL search request.
   * 
   * @param String $query
   * @param String $page
   * @param String $sort 
   * @param Array $filters
   * @return Array
   * @since 0.5
   */
  private function prepare_parameters($query, $page, $sort, $filters) {

    $query_parameter = $this->prepare_query_parameter($query);

    $page_parameter = $this->prepare_page_parameter($page);

    $sort_parameter = $this->prepare_sort_parameter($sort);

    $filter_parameters = $this->prepare_filter_parameters($filters);

    return [
      'c'=> ['a', 'b', 'c', 'd', 'e', 'f', 'g']
    ] + $query_parameter + $page_parameter + $sort_parameter + $filter_parameters;

    // ?q=' . urlencode($query) . $filter_param . '&c[]=a&c[]=b&c[]=c&c[]=d&c[]=e&c[]=f&c[]=g' . $page_param, ['http_errors' => false]); // TODO: add sort
    
  }

  /**
   * Prepare the search query segment of the Request url.
   * 
   * @param String $query
   * @return Array
   * @since 0.5
   */
  private function prepare_query_parameter($query) {
    
    // MAL does not accept search strings less than 3 bytes, but it somehow accepts spaces.
    if(strlen($query) < 3) {
      $query = $query . str_repeat(' ', 3 - strlen($query));
    }

    return [
      'q' => urlencode($query)
    ];

  }

  /**
   * Prepare the page segment of the Request url.
   * 
   * @param String $page
   * @return Array
   * @since 0.5
   */
  private function prepare_page_parameter($page) {
    
    // The show parameter is a multiple of 50, and it is how much data is has shown before.
    // So, 0 for the first page, 50 for the second page.
    $show_offset = ((int)$page - 1) * 50;

    return [
      'show' => $show_offset
    ];

  }

  /**
   * Prepare the sort segment of the Request url.
   * TODO: Complete this.
   * 
   * @param String $sort
   * @return Array
   * @since 0.5
   */
  private function prepare_sort_parameter($sort) {

    return [];

  }

  /**
   * Prepare the filter segment of the Request url.
   * 
   * @param Array $filters
   * @return Array
   * @since 0.5
   */
  private function prepare_filter_parameters($filters) {
    
    $filter_parameters = [];
    foreach($filters as $filter_name => $filter_value) {

      if($filter_value === '') continue;

      if($filter_name === 'type') {
        $filter_parameter = $this->prepare_simple_filter_parameter('type', 'type', $filter_value);
      } else if($filter_name === 'score') {
        $filter_parameter = $this->prepare_score_filter_parameter('score', $filter_value);
      } else if($filter_name === 'publish_status') {
        $filter_parameter = $this->prepare_simple_filter_parameter('status', 'publish_status', $filter_value);
      } else if($filter_name === 'magazine') {
        throw new MatomariError('Not yet implemented.', 501);
      } else if($filter_name === 'publish_dates') {
        $filter_parameter = $this->prepare_dates_filter_parameter($filter_value);
      } else if($filter_name === 'letter') {
        $filter_parameter = $this->prepare_letter_filter_parameter($filter_value);
      } else if($filter_name === 'genres') {
        $filter_parameter = $this->prepare_genre_filter_parameter($filter_value);
      } else if($filter_name ==='exclude_genres') {
        if($filter_value) $filter_param = ['gx' => '1'];
      } else continue;

      $filter_parameters = $filter_parameters + $filter_parameter;
      
    }
    return $filter_parameters;
  }

  /**
   * Prepare a simple filter parameter that reads from the mapping.
   * 
   * @param String $url_key The key for the URL segment to be generated
   * @param String $mapping_key The key to find the key=>value mappings for the URL segment
   * @param String $value The value of the filter that is to be searched in the mapping.
   * @param Boolean $array Return the value as an array, used for genres
   * @return Array
   * @since 0.5
   */
  private function prepare_simple_filter_parameter($url_key, $mapping_key, $value, $array = false) {

    // If the URL mapping for the segment key exists, use it, if not use the 'default' key.
    if(isset($this->mapping[$mapping_key][strtolower($value)])) {
      $url_value = $this->mapping[$mapping_key][strtolower($value)];
    } else {
      $url_value = $this->mapping[$mapping_key]['default'];
    }

    return [
      $url_key => ($array ? [$url_value] : $url_value)
    ];

  }

  /**
   * Prepare the score filter parameter that converts values into integers from 1-10.
   * 
   * @param String $url_key The key for the URL segment to be generated
   * @param String $value The input score
   * @return Array
   * @since 0.5
   */
  private function prepare_score_filter_parameter($url_key, $value) {

    // If the value is numeric, and is between 1 and 10
    if(is_numeric($value) && (int)$value >= 1 && (int)$value <= 10) {
      $url_value = (string)(int)$value; // Just... in case it's a floating number or something.
    } else {
      $url_value = '0';
    }
    return [
      $url_key = $url_value
    ];

  }

  /**
   * Prepare the date filter parameters. Converts dates (full and partial) into MAL parameters.
   * 
   * @param Array $value Array containg 'from' and 'to' keys.
   * @return Array
   * @since 0.5
   */
  private function prepare_dates_filter_parameter($value) {

    // This is the array that will be returned at the end of this function
    $date_parameters = [];

    foreach($value as $key => $date_array) {

      if($key === 'from') {
        $parameter_prefix = 's';
      } else {
        $parameter_prefix = 'e';
      }

      if(is_int($date_array['year'])) {
        $date_parameters[$parameter_prefix . 'y'] = $date_array['year'];
      } else {
        $date_parameters[$parameter_prefix . 'y'] = '0';
      }

      if(is_int($date_array['month'])) {
        $date_parameters[$parameter_prefix . 'm'] = $date_array['month'];
      } else {
        $date_parameters[$parameter_prefix . 'm'] = '0';
      }

      if(is_int($date_array['day'])) {
        $date_parameters[$parameter_prefix . 'd'] = $date_array['day'];
      } else {
        $date_parameters[$parameter_prefix . 'd'] = '0';
      }

    }

    return $date_parameters;

  }

  /**
   * Prepare the letter filter parameter. 
   * 
   * @param String $value Unparsed alphabetical letter to filter out the results.
   * @return Array
   * @since 0.5
   */
  private function prepare_letter_filter_parameter($value) {

    $url_value = [];
    if(strlen($value) === 1) {
      if(preg_match('/^[a-zA-Z]$/', $value)) {
        $url_value = strtolower($value); // MAL does accept capital letters but just in case
      }
    }

    return [
      'letter' => $url_value
    ];

  }

  /**
   * Prepare the genre filters from an array of genres. Use prepare_simple_filter_parameter().
   * 
   * @param Array $value Genres to include or exclude
   * @return Array
   * @since 0.5
   */
  private function prepare_genre_filter_parameter($value) {
    
    // Don't initiate anything here unlike AnimeSearchCollection because for some reason,
    // manga.php doesn't accept genre as an empty array, and only accepts genre as "unprovided".

    // Loop through each genre and create a simple filter.
    foreach($value as $genre) {
      $genre_parameters = array_merge_recursive(
        $genre_parameters ?? [],
        $this->prepare_simple_filter_parameter('genre', 'genres', $genre)
      );
    }

    return $genre_parameters;

  }

  /**
   * Generate a directory-like cache key that Stash can read.
   * 
   * @param String $query
   * @param String $page
   * @param String $sort
   * @param Array $filters
   * @return String
   * @since 0.5
   */
  private function generate_cache_key($query, $page, $sort, $filters) {

    // Hash and cut down the query to a 12 letter string so it doesn't trigger a Too Long Key error.
    $query = substr(base_convert(md5($query), 16, 32), 0, 12);

    if(!empty($filters)) {
      
      // Hash and cut down the filters as well, but only if it's not empty.
      // Use json_encode() instead of serialize() because it is faster and produces a smaller string.
      $filters = substr(base_convert(md5(json_encode($filters)), 16, 32), 0, 12);

      // Add a slash so that it can continue with page.
      $filters = $filters . '/';

    } else {

      $filters = '';

    }

    return '/manga/search' . $query . '/' . $filters . (string)(int)$page;

  }

}