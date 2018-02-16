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
use Matomari\Parsers\AnimeInfoParser;

/**
 * Collection for anime search - grabs information from cache or URL, and creates Models.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class AnimeSearchCollection extends Collection
{

  /**
   * Call request, create a parser, and store the model generated from the parser
   * When the deepest level of caching is required (storing HTML files), it should be
   * done in this layer.
   * 
   * @param String $query The query to search for
   * @param String $filter The raw unparsed filter to use
   * @param String $page The raw unparsed page number 
   * @since 0.5
   */
  public function __construct($query, $filter, $page) {

    $filters = explode(',', $filter);
    foreach($filters as $individual_filter) {
      $filterparts = explode(':', $individual_filter);
      if(!isset($filterparts[0]) || !isset($filterparts[1]) || trim($filterparts[0]) == '' || trim($filterparts[1]) == '' || count($filterparts) != 2) {
        continue; // Parameter is not valid, so skip
      }
      switch(strtolower($filterparts[0])) {
        case 'type':
          // type: TV, OVA, Movie, Special, ONA, Music.
          switch(strtolower($filterparts[1])) {
            case 'tv':
              $filter_param .= '&type=1';
              break;
            case 'ova':
              $filter_param .= '&type=2';
              break;
            case 'movie':
              $filter_param .= '&type=3';
              break;
            case 'special':
              $filter_param .= '&type=4';
              break;
            case 'ona':
              $filter_param .= '&type=5';
              break;
            case 'music':
              $filter_param .= '&type=6';
              break;
            default:
              $filter_param .= '&type=0';
              break;
          }
          break;
        case 'score':
          switch(strtolower($filterparts[1])) {
            case '1':
              $filter_param .= '&score=1';
              break;
            case '2':
              $filter_param .= '&score=2';
              break;
            case '3':
              $filter_param .= '&score=3';
              break;
            case '4':
              $filter_param .= '&score=4';
              break;
            case '5':
              $filter_param .= '&score=5';
              break;
            case '6':
              $filter_param .= '&score=6';
              break;
            case '7':
              $filter_param .= '&score=7';
              break;
            case '8':
              $filter_param .= '&score=8';
              break;
            case '9':
              $filter_param .= '&score=9';
              break;
            case '10':
              $filter_param .= '&score=10';
              break;
            default:
              $filter_param .= '&score=0';
              break;
          }
          break;
        case 'status':
          switch(strtolower($filterparts[1])) {
            case 'finished_airing':
              $filter_param .= '&status=2';
              break;
            case 'currently_airing':
              $filter_param .= '&status=1';
              break;
            case 'not_yet_aired':
              $filter_param .= '&status=3';
              break;
            default:
              $filter_param .= '&status=0';
              break;
          }
          break;
        case 'producer':
          // Too much to handle right now... Maybe later.
          break;
        case 'rating':
          switch(strtolower($filterparts[1])) {
            case 'g':
              $filter_param .= '&r=1';
              break;
            case 'pg':
              $filter_param .= '&r=2';
              break;
            case 'pg-13':
              $filter_param .= '&r=3';
              break;
            case 'r':
              $filter_param .= '&r=4';
              break;
            case 'r+':
              $filter_param .= '&r=5';
              break;
            case 'rx':
              $filter_param .= '&r=6';
              break;
            default:
              $filter_param .= '&r=0';
              break;
          }
          break;
        case 'startdate':
          if(strlen($filterparts[1]) != 8) {
            $filter_param .= '&sm=0&sd=0&sy=0';
            break;
          }
          $sy = $sm = $sd = '0';
          if(is_numeric(substr($filterparts[1], 0, 4))) {
            $sy = (int)substr($filterparts[1], 0, 4);
          }
          if(is_numeric(substr($filterparts[1], 4, 2))) {
            $sm = (int)substr($filterparts[1], 4, 2);
          }
          if(is_numeric(substr($filterparts[1], 6, 2))) {
            $sd = (int)substr($filterparts[1], 6, 2);
          }
          $filter_param .= '&sy=' . $sy . '&sm=' . $sm . '&sd=' . $sd;
          break;
        case 'enddate':
          if(strlen($filterparts[1]) != 8) {
            $filter_param .= '&em=0&ed=0&ey=0';
            break;
          }
          $ey = $em = $ed = '0';
          if(is_numeric(substr($filterparts[1], 0, 4))) {
            $ey = (int)substr($filterparts[1], 0, 4);
          }
          if(is_numeric(substr($filterparts[1], 4, 2))) {
            $em = (int)substr($filterparts[1], 4, 2);
          }
          if(is_numeric(substr($filterparts[1], 6, 2))) {
            $ed = (int)substr($filterparts[1], 6, 2);
          }
          $filter_param .= '&ey=' . $ey . '&em=' . $em . '&ed=' . $ed;
          break;
        case 'startswithletter':
          if(strlen($filterparts[1]) != 1) {
            break;
          }
          if(!preg_match('/^[a-zA-Z]$/', $filterparts[1])) {
            break;
          }
          $filter_param .= '&letter=' . $filterparts[1];
          break;
        case 'inc-genre':
          if(strpos($filter_param, '&gx=0') === false) $filter_param .= '&gx=0';
          switch(strtolower($filterparts[1])) {
            case 'action':
              $filter_param .= '&genre[]=1';
              break;
            case 'adventure':
              $filter_param .= '&genre[]=2';
              break;
            case 'cars':
              $filter_param .= '&genre[]=3';
              break;
            case 'comedy':
              $filter_param .= '&genre[]=4';
              break;
            case 'dementia':
              $filter_param .= '&genre[]=5';
              break;
            case 'demons':
              $filter_param .= '&genre[]=6';
              break;
            case 'mystery':
              $filter_param .= '&genre[]=7';
              break;
            case 'drama':
              $filter_param .= '&genre[]=8';
              break;
            case 'ecchi':
              $filter_param .= '&genre[]=9';
              break;
            case 'fantasy':
              $filter_param .= '&genre[]=10';
              break;
            case 'game':
              $filter_param .= '&genre[]=11';
              break;
            case 'hentai':
              $filter_param .= '&genre[]=12';
              break;
            case 'historical':
              $filter_param .= '&genre[]=13';
              break;
            case 'horror':
              $filter_param .= '&genre[]=14';
              break;
            case 'kids':
              $filter_param .= '&genre[]=15';
              break;
            case 'magic':
              $filter_param .= '&genre[]=16';
              break;
            case 'martialarts':
              $filter_param .= '&genre[]=17';
              break;
            case 'mecha':
              $filter_param .= '&genre[]=18';
              break;
            case 'music':
              $filter_param .= '&genre[]=19';
              break;
            case 'parody':
              $filter_param .= '&genre[]=20';
              break;
            case 'samurai':
              $filter_param .= '&genre[]=21';
              break;
            case 'romance':
              $filter_param .= '&genre[]=22';
              break;
            case 'school':
              $filter_param .= '&genre[]=23';
              break;
            case 'scifi':
              $filter_param .= '&genre[]=24';
              break;
            case 'shoujo':
              $filter_param .= '&genre[]=25';
              break;
            case 'shoujoai':
              $filter_param .= '&genre[]=26';
              break;
            case 'shounen':
              $filter_param .= '&genre[]=27';
              break;
            case 'shounenai':
              $filter_param .= '&genre[]=28';
              break;
            case 'space':
              $filter_param .= '&genre[]=29';
              break;
            case 'sports':
              $filter_param .= '&genre[]=30';
              break;
            case 'superpower':
              $filter_param .= '&genre[]=31';
              break;
            case 'vampire':
              $filter_param .= '&genre[]=32';
              break;
            case 'yaoi':
              $filter_param .= '&genre[]=33';
              break;
            case 'yuri':
              $filter_param .= '&genre[]=34';
              break;
            case 'harem':
              $filter_param .= '&genre[]=35';
              break;
            case 'sliceoflife':
              $filter_param .= '&genre[]=36';
              break;
            case 'supernatural':
              $filter_param .= '&genre[]=37';
              break;
            case 'military':
              $filter_param .= '&genre[]=38';
              break;
            case 'police':
              $filter_param .= '&genre[]=39';
              break;
            case 'psychological':
              $filter_param .= '&genre[]=40';
              break;
            case 'thriller':
              $filter_param .= '&genre[]=41';
              break;
            case 'seinen':
              $filter_param .= '&genre[]=42';
              break;
            case 'josei':
              $filter_param .= '&genre[]=43';
              break;
          }
          break;
        case 'exc-genre':
          if(strpos($filter_param, '&gx=1') === false) $filter_param .= '&gx=1';
          switch(strtolower($filterparts[1])) {
            case 'action':
              $filter_param .= '&genre[]=1';
              break;
            case 'adventure':
              $filter_param .= '&genre[]=2';
              break;
            case 'cars':
              $filter_param .= '&genre[]=3';
              break;
            case 'comedy':
              $filter_param .= '&genre[]=4';
              break;
            case 'demantia':
              $filter_param .= '&genre[]=5';
              break;
            case 'demons':
              $filter_param .= '&genre[]=6';
              break;
            case 'mystery':
              $filter_param .= '&genre[]=7';
              break;
            case 'drama':
              $filter_param .= '&genre[]=8';
              break;
            case 'ecchi':
              $filter_param .= '&genre[]=9';
              break;
            case 'fantasy':
              $filter_param .= '&genre[]=10';
              break;
            case 'game':
              $filter_param .= '&genre[]=11';
              break;
            case 'hentai':
              $filter_param .= '&genre[]=12';
              break;
            case 'historical':
              $filter_param .= '&genre[]=13';
              break;
            case 'horror':
              $filter_param .= '&genre[]=14';
              break;
            case 'kids':
              $filter_param .= '&genre[]=15';
              break;
            case 'magic':
              $filter_param .= '&genre[]=16';
              break;
            case 'martialarts':
              $filter_param .= '&genre[]=17';
              break;
            case 'mecha':
              $filter_param .= '&genre[]=18';
              break;
            case 'music':
              $filter_param .= '&genre[]=19';
              break;
            case 'parody':
              $filter_param .= '&genre[]=20';
              break;
            case 'samurai':
              $filter_param .= '&genre[]=21';
              break;
            case 'romance':
              $filter_param .= '&genre[]=22';
              break;
            case 'school':
              $filter_param .= '&genre[]=23';
              break;
            case 'scifi':
              $filter_param .= '&genre[]=24';
              break;
            case 'shoujo':
              $filter_param .= '&genre[]=25';
              break;
            case 'shoujoai':
              $filter_param .= '&genre[]=26';
              break;
            case 'shounen':
              $filter_param .= '&genre[]=27';
              break;
            case 'shounenai':
              $filter_param .= '&genre[]=28';
              break;
            case 'space':
              $filter_param .= '&genre[]=29';
              break;
            case 'sports':
              $filter_param .= '&genre[]=30';
              break;
            case 'superpower':
              $filter_param .= '&genre[]=31';
              break;
            case 'vampire':
              $filter_param .= '&genre[]=32';
              break;
            case 'yaoi':
              $filter_param .= '&genre[]=33';
              break;
            case 'yuri':
              $filter_param .= '&genre[]=34';
              break;
            case 'harem':
              $filter_param .= '&genre[]=35';
              break;
            case 'sliceoflife':
              $filter_param .= '&genre[]=36';
              break;
            case 'supernatural':
              $filter_param .= '&genre[]=37';
              break;
            case 'military':
              $filter_param .= '&genre[]=38';
              break;
            case 'police':
              $filter_param .= '&genre[]=39';
              break;
            case 'psychological':
              $filter_param .= '&genre[]=40';
              break;
            case 'thriller':
              $filter_param .= '&genre[]=41';
              break;
            case 'seinen':
              $filter_param .= '&genre[]=42';
              break;
            case 'josei':
              $filter_param .= '&genre[]=43';
              break;
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

    $this->array = AnimeInfoParser::parse($response->getBody());
  }

}