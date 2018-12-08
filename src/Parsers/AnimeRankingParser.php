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
use Matomari\Models\RankingModel;
use Matomari\Models\AnimeRankingModel;

/**
 * Parse HTML of anime ranking pages into AnimeRanking Models
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class AnimeRankingParser extends Parser
{

  /**
   * Parse the HTML of the anime ranking response, and return the list of generated AnimeRankings.
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
  private function parseResult($result) {

    $td_rank = $result->find('td', 0);
    $td_mostinformationhere = $result->find('td', 1);
    $td_score = $result->find('td', 2);
  
    $anime = new AnimeRankingModel();
   
    // TODO: Separate each individual parse to a different function
    
    // The ID
    // <a class="hoverinfo_trigger fl-l ml12 mr8" href="https://myanimelist.net/anime/5114/Fullmetal_Alchemist__Brotherhood" id="#area5114" rel="#info5114">
    $anime->set('id', (int)substr(trim($td_mostinformationhere->find('a.hoverinfo_trigger', 0)->id), 5));

    // The Name
    // <a href="https://myanimelist.net/anime/5114/Fullmetal_Alchemist__Brotherhood" class="hovertitle">Fullmetal Alchemist: Brotherhood (2009)</a>
    $anime->set('name', (string)$td_mostinformationhere->find('.detail a', 0)->innertext);

    // The MAL URL
    // <a href="https://myanimelist.net/anime/5114/Fullmetal_Alchemist__Brotherhood" class="hovertitle">Fullmetal Alchemist: Brotherhood (2009)</a>
    $anime->set('mal_url', (string)$td_mostinformationhere->find('.detail a', 0)->href);

    // The Image URL
    // <img width="50" height="70" alt="Anime: Fullmetal Alchemist: Brotherhood" class=" lazyloaded" border="0" data-src="https://myanimelist.cdn-dena.com/r/50x70/images/anime/5/47421.webp?s=06392961aa190c6c078ea98f60529f11" data-srcset="https://myanimelist.cdn-dena.com/r/50x70/images/anime/5/47421.webp?s=06392961aa190c6c078ea98f60529f11 1x, https://myanimelist.cdn-dena.com/r/100x140/images/anime/5/47421.webp?s=597b2c97f1958da9bf61a1daa9ded156 2x" srcset="https://myanimelist.cdn-dena.com/r/50x70/images/anime/5/47421.webp?s=06392961aa190c6c078ea98f60529f11 1x, https://myanimelist.cdn-dena.com/r/100x140/images/anime/5/47421.webp?s=597b2c97f1958da9bf61a1daa9ded156 2x" src="https://myanimelist.cdn-dena.com/r/50x70/images/anime/5/47421.webp?s=06392961aa190c6c078ea98f60529f11">
    if($td_mostinformationhere->find('a.hoverinfo_trigger img', 0)->{'data-srcset'}) {
      $anime->set('image_url', (string)str_replace('webp', 'jpg',
        str_replace('/r/50x70', '',
          explode(
            '?s=',
            $td_mostinformationhere->find('a.hoverinfo_trigger img', 0)->{'data-srcset'}
          )[0]
        )
      ));
    } else {
      $anime->set('image_url', (string)str_replace('webp', 'jpg',
        str_replace('/r/50x70', '',
          explode(
            '?s=',
            $td_mostinformationhere->find('a.hoverinfo_trigger img', 0)->srcset
          )[0]
        )
      ));
    }

    // The Score
    // <span class="text on">9.25</span>
    if(trim($td_score->find('span', 0)->innertext) !== 'N/A') {
      $anime->set('score', (float)trim($td_score->find('span', 0)->innertext));
    }

    // The Rank
    // <span class="lightLink top-anime-rank-text rank5">10051</span>
    $anime->set('rank', (int)$td_rank->find('span', 0)->innertext);

    // <div class="information di-ib mt4">
    //    TV (64 eps)<br>
    //    Apr 2009 - Jul 2010<br>
    //    1,203,717 members
    //  </div>

    // The Type
    if(trim($td_mostinformationhere->find('.information text', 0)->innertext) !== '-') {
      $anime->set('type', (string)strtolower(trim(
        explode(' (', $td_mostinformationhere->find('.information text', 0)->innertext)[0]
      )));
    }

    // The Episodes
    if(trim($td_mostinformationhere->find('.information text', 0)->innertext) !== '?') {
      preg_match_all('/\((.*) eps\)/',
        $td_mostinformationhere->find('.information text', 0)->innertext, $matches);

      $anime->set('episodes', (int)strtolower(trim($matches[1][0])));
    }

    $mal_air_dates = $td_mostinformationhere->find('.information text', 1)->innertext;
    
    // The Air-From Date
    $mal_air_date_from = trim(explode(' - ', $mal_air_dates)[0]);
    if($mal_air_date_from !== '') {
      $anime->set('air_dates//from', (array)Time::convert(
        // Time::convert only converts ones with comma between month and year
        str_replace(' ', ', ', $mal_air_date_from)
      ));
    }
    
    // The Air-To Date
    $mal_air_date_to = trim(explode(' - ', $mal_air_dates)[1]);
    if($mal_air_date_to !== '') {
      $anime->set('air_dates//to', (array)Time::convert(
        str_replace(' ', ', ', $mal_air_date_to)
      ));
    }
    
    // The Members who have it in their list
    $anime->set('members_inlist', (int)str_replace(',', '', 
      explode(' members', trim($td_mostinformationhere->find('.information text', 2)->innertext))[0]
    ));

    return $anime;

  }

}