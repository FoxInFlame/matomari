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
use Matomari\Parsers\AnimeSearchParser;

/**
 * Collection for anime search - grabs information from cache or URL, and creates Models.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class AnimeSearchCollection extends Collection
{

  /**
   * Various mapping between URL and Request.
   * @var Array
   */
  private $mapping = [
    'type' => [
      'tv' => '1',
      'ova' => '2',
      'movie' => '3',
      'special' => '4',
      'ona' => '5',
      'music' => '6',
      'default' => '0'
    ],
    'air_status' => [
      'currently_airing' => '1',
      'finished_airing' => '2',
      'not_yet_aired' => '3',
      'default' => '0'
    ],
    'classification' => [
      'g' => '1',
      'pg' => '2',
      'pg-13' => '3',
      'r' => '4',
      'r+' => '5',
      'rx' => '6',
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
      'thriller' => '41',
      'seinen' => '42',
      'josei' => '43'
    ]
  ];

  /**
   * Call request, create a parser, and store the model generated from the parser
   * When the deepest level of caching is required (storing HTML files), it should be
   * done in this layer.
   * 
   * @param String $query The query to search for
   * @param String $page The raw unparsed page number 
   * @param String $sort The raw unparsed sorting specifier
   * @param Array $filter The filters, parsed into an associative array
   *  $filter = [
   *    'type' => (string) Show results matching the media type
   *    'score' => (string) Show results with scores above the score
   *    'air_status' => (string) Show results matching the airing status
   *    'producer' => (string) TBD
   *    'classification' => (string) Show results matching the classification name
   *    'air_dates' => [
   *      'from' => (string) Show results which start airing from the date
   *      'to' => (string) Show results which end airing on the date
   *    ]
   *    'letter' => (string) Show results that start with the alphabet letter
   *    'genres' => (array) 
   *    'genre_type' => (string) Either include or exclude the previous array of genres
   *  ]
   * @since 0.5
   */
  public function __construct($query, $page, $sort, $filters) {

    $filter_param = '';
    foreach($filters as $filter_name => $filter_value) {
      if($filter_value === '') {
        continue;
      }

      switch(strtolower($filter_name)) {
        case 'type':
          // type: TV, OVA, Movie, Special, ONA, Music.
          $filter_param .= '&type=';
          if(isset($this->mapping['type'][$filter_value])) {
            $filter_param .= $this->mapping['type'][strtolower($filter_value)];
          } else {
            $filter_param .= $this->mapping['type']['default'];
          }
          break;
        case 'score':
          if(is_numeric($filter_value) && (int)$filter_value >= 1 && $filter_value <= 10) {
            $filter_param .= '&score=' . $filter_value;
          } else {
            $filter_param .= '&score=0';
          }
          break;
        case 'air_status':
          $filter_param .= '&status=';
          if(isset($this->mapping['air_status'][strtolower($filter_value)])) {
            $filter_param .= $this->mapping['air_status'][strtolower($filter_value)];
          } else {
            $filter_param .= $this->mapping['air_status']['default'];
          }
          break;
        case 'producer':
          // TODO: Do something here...
          break;
        case 'classification':
          $filter_param .= '&r=';
          if(isset($this->mapping['classification'][strtolower($filter_value)])) {
            $filter_param .= $this->mapping['classification'][strtolower($filter_value)];
          } else {
            $filter_param .= $this->mapping['classification']['default'];
          }
          break;
        case 'air_dates':
          if($filter_value['from']) {
            if(strlen($filter_value) != 8) {
              $filter_param .= '&sm=0&sd=0&sy=0';
              break;
            }
            $sy = $sm = $sd = '0';
            if(is_numeric(substr($filter_value, 0, 4))) {
              $sy = (int)substr($filter_value, 0, 4);
            }
            if(is_numeric(substr($filter_value, 4, 2))) {
              $sm = (int)substr($filter_value, 4, 2);
            }
            if(is_numeric(substr($filter_value, 6, 2))) {
              $sd = (int)substr($filter_value, 6, 2);
            }
            $filter_param .= '&sy=' . $sy . '&sm=' . $sm . '&sd=' . $sd;
          } else if($filter_value['to']) {
            if(strlen($filter_value) != 8) {
              $filter_param .= '&em=0&ed=0&ey=0';
              break;
            }
            $ey = $em = $ed = '0';
            if(is_numeric(substr($filter_value, 0, 4))) {
              $ey = (int)substr($filter_value, 0, 4);
            }
            if(is_numeric(substr($filter_value, 4, 2))) {
              $em = (int)substr($filter_value, 4, 2);
            }
            if(is_numeric(substr($filter_value, 6, 2))) {
              $ed = (int)substr($filter_value, 6, 2);
            }
            $filter_param .= '&ey=' . $ey . '&em=' . $em . '&ed=' . $ed;
          }
          break;
        case 'letter':
          if(strlen($filter_value) != 1) {
            break;
          }
          if(!preg_match('/^[a-zA-Z]$/', $filter_value)) {
            break;
          }
          $filter_param .= '&letter=' . $filter_value;
          break;
        case 'genres':
          foreach($filter_value as $filter_value_genre) {
            if(isset($this->mapping['genres'][strtolower($filter_value_genre)])) {
              $filter_param .= '&genre[]=' . $this->mapping['genres'][strtolower($filter_value_genre)];
            }
          }
          break;
        case 'exclude_genres':
          if($filter_value) {
            $filter_param .= '&gx=1';
          }
          break;
      }
    }

    if(strlen($query) < 3) {
      $query = $query . str_repeat(' ', 3 - strlen($query));
    }

    $page_param = '&show=' . ($page - 1) * 50;

    $guzzle_client = new Client();
    $response = $guzzle_client->request('GET', 'https://myanimelist.net/anime.php?q=' . urlencode($query) . $filter_param . '&c[]=a&c[]=b&c[]=c&c[]=d&c[]=e&c[]=f&c[]=g' . $page_param, ['http_errors' => false]);

    if($response->getStatusCode() === 429) {
      throw new MatomariError('Too many frequent requests. Please wait and retry.', 429);
    } else if($response->getStatusCode() === 404) {
      throw new MatomariError('The page could not be found.', 404);
    } else if($response->getStatusCode() !== 200) {
      throw new MatomariError('There was an unknown error with MAL. Contact FoxInFlame.', 500);
    }

    $this->array = AnimeSearchParser::parse($response->getBody());
  }

}