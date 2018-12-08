<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Parsers;

use Matomari\Parsers\Parser;
use Matomari\Exceptions\MatomariError;
use Matomari\Components\Time;
use Matomari\Models\SearchModel;
use Matomari\Models\MangaSearchModel;

/**
 * Parse HTML of manga search pages into MangaSearch Models
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class MangaSearchParser extends Parser
{

  /**
   * Parse the HTML of the manga search response, and return the list of generated MangaSearches.
   * 
   * @param String $response The response HTML from MAL
   * @return Array
   * @since 0.5
   */
  public function parse($response) {

    $html = $this->parser->str_get_html($response);

    if(!is_object($html)) {
      throw new MatomariError('The code for MAL is not valid HTML.', 502);
    }

    $search = new SearchModel();

    // The results list
    $tr = $html->find('#contentWrapper #content div.list table tbody tr');
    if(count($tr) == 0) {
      // No results
      return $search->results;
    }

    // Remove first item from array (thead)
    array_shift($tr);

    // Loop through and parse each result
    foreach($tr as $result) {
      array_push($search->results['items'], self::parseResult($result)->asArray());
    }

    return $search->results;
    
  }

  /**
   * Parse each individual entry in the result list and return not the array but the model itself.
   * 
   * @param HtmlDomParser $result The parsed HTML for the individual entry
   * @return Array
   * @since 0.5 
   */
  private function parseResult($result) {

    $td_image = $result->find('td', 0);
    $td_name = $result->find('td', 1);
    $td_type = $result->find('td', 2);
    $td_volumes = $result->find('td', 3);
    $td_chapters = $result->find('td', 4);
    $td_score = $result->find('td', 5);
    $td_publish_date_from = $result->find('td', 6);
    $td_publish_date_to = $result->find('td', 7);
    $td_members_inlist = $result->find('td', 8);
  
    $manga = new MangaSearchModel();
   
    // TODO: Separate each individual parse to a different function
    
    // The ID
    // <a class="hoverinfo_trigger" href="https://myanimelist.net/manga/90/Loveless" id="sarea90" rel="#sinfo90"><img...></a>
    $manga->set('id', (int)substr(trim($td_image->find('div.picSurround a', 0)->id), 5));

    // The Name
    // <strong>Loveless</strong>
    $manga->set('name', (string)$td_name->find('a.hoverinfo_trigger strong', 0)->innertext);

    // The MAL URL
    // <a class='hoverinfo_trigger fw-b' href='https://myanimelist.net/manga/90/Loveless' id='sinfo90' rel='#sinfo90' style='display: inline-block;'>
    $manga->set('mal_url', (string)$td_name->find('a.hoverinfo_trigger', 0)->href);

    // The Image URL
    // <img width="50" height="70" alt="Loveless" border="0" data-src="https://myanimelist.cdn-dena.com/r/50x70/images/manga/2/171051.webp?s=9322c3e430735b915d69c06f08e292b4" data-srcset="https://myanimelist.cdn-dena.com/r/50x70/images/manga/2/171051.webp?s=9322c3e430735b915d69c06f08e292b4 1x, https://myanimelist.cdn-dena.com/r/100x140/images/manga/2/171051.webp?s=8b6fd8e7fc25b5ceadc60b5e903766ba 2x" class=" lazyloaded" srcset="https://myanimelist.cdn-dena.com/r/50x70/images/manga/2/171051.webp?s=9322c3e430735b915d69c06f08e292b4 1x, https://myanimelist.cdn-dena.com/r/100x140/images/manga/2/171051.webp?s=8b6fd8e7fc25b5ceadc60b5e903766ba 2x" src="https://myanimelist.cdn-dena.com/r/50x70/images/manga/2/171051.webp?s=9322c3e430735b915d69c06f08e292b4">
    if($td_image->find('div.picSurround a img', 0)->{'data-srcset'}) {
      $manga->set('image_url', (string)str_replace('webp', 'jpg',
        str_replace('/r/50x70', '',
          explode(
            '?s=',
            $td_image->find('div.picSurround a img', 0)->{'data-srcset'}
          )[0]
        )
      ));
    } else {
      $manga->set('image_url', (string)str_replace('webp', 'jpg',
        str_replace('/r/50x70', '',
          explode(
            '?s=',
            $td_image->find('div.picSurround a img', 0)->srcset
          )[0]
        )
      ));
    }

    // The Score
    // <td class='borderClass ac bgColor0' width='50'>    7.86  </td>
    if(trim($td_score->innertext) !== 'N/A') {
      $manga->set('score', (float)trim($td_score->innertext));
    }

    // The Type
    // <td class='borderClass ac bgColor0' width='45'>    Manga  </td>
    if(trim($td_type->innertext) !== '-') {
      $manga->set('type', (string)strtolower(trim($td_type->innertext)));
    }

    // The Volumes
    // <td class='borderClass ac bgColor0' width='40'>    -  </td>
    if(trim($td_volumes->innertext) !== '-') {
      $manga->set('volumes', (int)trim($td_volumes->innertext));
    }

    // The Chapters
    // <td class='borderClass ac bgColor0' width='50'>    -  </td>
    if(trim($td_chapters->innertext) !== '-') {
      $manga->set('chapters', (int)trim($td_chapters->innertext));
    }

    // The Publish-From Date
    // MM-DD-YY (and ?? if unknown, - if everything is unknown)
    $mal_publish_date_from = trim($td_publish_date_from->innertext);
    if($mal_publish_date_from !== '-') {
      $manga->set('publish_dates//from', (array)Time::convert($mal_publish_date_from));
    }

    // The Publish-To Date
    $mal_publish_date_to = trim($td_publish_date_to->innertext);
    if($mal_publish_date_to !== '-') {
      $manga->set('publish_dates//to', (array)Time::convert($mal_publish_date_to));
    }
    
    // The Members who have it in their list
    // <td class='borderClass ac bgColor0' width='75'>    22,585  </td>
    $manga->set('members_inlist', (int)str_replace(',', '', trim($td_members_inlist->innertext)));

    return $manga;

  }

}