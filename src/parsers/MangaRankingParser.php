<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Parsers;

use Sunra\PhpSimple\HtmlDomParser;
use Matomari\Parsers\Parser;
use Matomari\Exceptions\MatomariError;
use Matomari\Components\Time;
use Matomari\Models\RankingModel;
use Matomari\Models\MangaRankingModel;

/**
 * Parse HTML of manga ranking pages into MangaRanking Models
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class MangaRankingParser extends Parser
{

  /**
   * Parse the HTML of the manga ranking response, and return the list of generated MangaRankings.
   * 
   * @param String $response The response HTML from MAL
   * @return Array
   * @since 0.5
   */
  public static function parse($response) {

    $html = HtmlDomParser::str_get_html($response);

    if(!is_object($html)) {
      throw new MatomariError('The code for MAL is not valid HTML.', 502);
    }

    $ranking = new RankingModel();

    // The entries list
    $tr = $html->find("#contentWrapper .top-ranking-table .ranking-list");
    if(count($tr) == 0) {
      // No entries
      return $ranking->entries;
    }

    // Loop through and parse each entry
    foreach($tr as $entry) {
      array_push($ranking->entries['items'], self::parseResult($entry)->asArray());
    }

    return $ranking->entries;
    
  }

  /**
   * Parse each individual entry in the result list and return not the array but the model itself.
   * 
   * @param HtmlDomParser $result The parsed HTML for the individual entry
   * @return Array
   * @since 0.5 
   */
  private static function parseResult($result) {

    $td_rank = $result->find('td', 0);
    $td_mostinformationhere = $result->find('td', 1);
    $td_score = $result->find('td', 2);
  
    $manga = new MangaRankingModel();
   
    // TODO: Separate each individual parse to a different function
    
    // The ID
    // <a class="hoverinfo_trigger fl-l ml12 mr8" href="https://myanimelist.net/manga/2/Berserk" id="#area2" rel="#info2">
    $manga->set('id', (int)substr(trim($td_mostinformationhere->find('a.hoverinfo_trigger', 0)->id), 5));

    // The Name
    // <a class="hoverinfo_trigger fs14 fw-b" href="https://myanimelist.net/manga/2/Berserk" id="#area2" rel="#info2" style="display: inline-block;">Berserk</a>
    $manga->set('name', (string)$td_mostinformationhere->find('.detail a', 0)->innertext);

    // The MAL URL
    // <a class="hoverinfo_trigger fs14 fw-b" href="https://myanimelist.net/manga/2/Berserk" id="#area2" rel="#info2" style="display: inline-block;">Berserk</a>
    $manga->set('mal_url', (string)$td_mostinformationhere->find('.detail a', 0)->href);

    // The Image URL
    // <img width="50" height="70" alt="Manga: Berserk" class=" lazyloaded" border="0" data-src="https://cdn.myanimelist.net/r/50x70/images/manga/1/157931.webp?s=00f3f9be0895815367e3172c275dc4a3" data-srcset="https://cdn.myanimelist.net/r/50x70/images/manga/1/157931.webp?s=00f3f9be0895815367e3172c275dc4a3 1x, https://cdn.myanimelist.net/r/100x140/images/manga/1/157931.webp?s=4d595f271b618ccf157f0cf5ba0bc61c 2x" srcset="https://cdn.myanimelist.net/r/50x70/images/manga/1/157931.webp?s=00f3f9be0895815367e3172c275dc4a3 1x, https://cdn.myanimelist.net/r/100x140/images/manga/1/157931.webp?s=4d595f271b618ccf157f0cf5ba0bc61c 2x" src="https://cdn.myanimelist.net/r/50x70/images/manga/1/157931.webp?s=00f3f9be0895815367e3172c275dc4a3">
    if($td_mostinformationhere->find('a.hoverinfo_trigger img', 0)->{'data-srcset'}) {
      $manga->set('image_url', (string)str_replace('webp', 'jpg',
        str_replace('/r/50x70', '',
          explode(
            '?s=',
            $td_mostinformationhere->find('a.hoverinfo_trigger img', 0)->{'data-srcset'}
          )[0]
        )
      ));
    } else {
      $manga->set('image_url', (string)str_replace('webp', 'jpg',
        str_replace('/r/50x70', '',
          explode(
            '?s=',
            $td_mostinformationhere->find('a.hoverinfo_trigger img', 0)->srcset
          )[0]
        )
      ));
    }

    // The Score
    // <span class="text on">9.31</span>
    if(trim($td_score->innertext) !== 'N/A') {
      $manga->set('score', (float)trim($td_score->find('span', 0)->innertext));
    }

    // The Rank
    // <span class="lightLink top-anime-rank-text rank1">1</span>
    $manga->set('rank', (int)$td_rank->find('span', 0)->innertext);

    // <div class="information di-ib mt4">
    //   Manga (? vols)<br>
    //   Aug 1989 - <br>
    //   212,522 members
    // </div>

    // The Type
    if(trim($td_mostinformationhere->find('.information text', 0)->innertext) !== '-') {
      $manga->set('type', (string)strtolower(trim(
        explode(' (', $td_mostinformationhere->find('.information text', 0)->innertext)[0]
      )));
    }

    // The Volumes
    if(trim($td_mostinformationhere->find('.information text', 0)->innertext) !== '-') {
      preg_match_all('/\((.*) vols\)/',
        $td_mostinformationhere->find('.information text', 0)->innertext, $matches);

      $manga->set('volumes', (int)strtolower(trim($matches[1][0])));
    }

    // The Publish-From Date
    $manga->set('publish_dates//from', (array)Time::convert(
      str_replace(' ', ', ', trim(
        // Time::convert only converts ones with comma between month and year
        explode(' - ', $td_mostinformationhere->find('.information text', 1)->innertext)[0]
      ))
    ));
    
    // The Publish-To Date
    $manga->set('publish_dates//to', (array)Time::convert(
      str_replace(' ', ', ', trim(
        explode(' - ', $td_mostinformationhere->find('.information text', 1)->innertext)[1]
      ))
    ));
    
    // The Members who have it in their list
    $manga->set('members_inlist', (int)str_replace(',', '', 
      explode(' members', trim($td_mostinformationhere->find('.information text', 2)->innertext))[0]
    ));

    return $manga;

  }

}