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
use Matomari\Models\AnimeInfoModel;
use Matomari\Models\BriefReferenceModel;

/**
 * Parse HTML of anime info pages into AnimeInfo Models
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class AnimeInfoParser extends Parser
{

  /**
   * Parse the HTML of the anime info response, and return the generated AnimeInfo model.
   * 
   * @param String $response The response HTML from MAL
   * @return AnimeInfo
   * @since 0.5
   */
  public static function parse($response) {
    
    $html = HtmlDomParser::str_get_html($response);

    if(!is_object($html)) {
      throw new MatomariError('The code for MAL is not valid HTML.', 502);
    }
   
    $anime = new AnimeInfoModel();

    $anime->set('id', self::parseId($html));
    $anime->set('name', self::parseName($html));
    $anime->set('mal_url', self::parseMalUrl($html));
    $anime->set('image_url', self::parseImageUrl($html));
    $anime->set('score', self::parseScore($html));
    $anime->set('rank', self::parseRank($html));
    $anime->set('popularity', self::parsePopularity($html));
    $anime->set('synopsis', self::parseSynopsis($html));
    $anime->set('other_titles//english', self::parseEnglishTitles($html));
    $anime->set('other_titles//japanese', self::parseJapaneseTitles($html));
    $anime->set('other_titles//synonyms', self::parseSynonymousTitles($html));
    $anime->set('type', self::parseType($html));
    $anime->set('episodes', self::parseEpisodes($html));
    $anime->set('air_status', self::parseAirStatus($html));
    $air_dates = self::parseAirDates($html);
    if(gettype($air_dates) === 'array') {
      $anime->set('air_dates//from', $air_dates[0]);
      $anime->set('air_dates//to', $air_dates[1]);
    } else {
      $anime->set('premiere_date', $air_dates);
    }
    $anime->set('season', self::parseSeason($html));
    $anime->set('air_time', self::parseAirTime($html));
    $anime->set('producers', self::parseProducers($html));
    $anime->set('licensors', self::parseLicensors($html));
    $anime->set('studios', self::parseStudios($html));
    $anime->set('source', self::parseSource($html));
    $anime->set('genres', self::parseGenres($html));
    $anime->set('duration//per_episode', self::parseDurationPerEpisode($html));
    $anime->set('duration//total', self::parseDurationTotal(
      $anime->get('episodes'),
      $anime->get('duration//per_episode')
    ));
    $classification = self::parseClassification($html);
    $anime->set('classification//name', $classification[0]);
    $anime->set('classification//description', $classification[1]);
    $anime->set('members_scored', self::parseMembersScored($html));
    $anime->set('members_inlist', self::parseMembersInList($html));
    $anime->set('members_favorited', self::parseMembersFavorited($html));
    $anime->set('background', self::parseBackground($html));
    $relations = self::parseRelations($html);
    $anime->set('relations//sequel', $relations[0]);
    $anime->set('relations//prequel', $relations[1]);
    $anime->set('relations//alternative_setting', $relations[2]);
    $anime->set('relations//alternative_version', $relations[3]);
    $anime->set('relations//side_story', $relations[4]);
    $anime->set('relations//parent_story', $relations[5]);
    $anime->set('relations//summary', $relations[6]);
    $anime->set('relations//full_story', $relations[7]);
    $anime->set('relations//spin_off', $relations[8]);
    $anime->set('relations//adaptation', $relations[9]);
    $anime->set('relations//character', $relations[10]);
    $anime->set('relations//other', $relations[11]);
    $anime->set('theme_songs//openings', self::parseOpeningThemeSongs($html));
    $anime->set('theme_songs//endings', self::parseEndingThemeSongs($html));

    return $anime->info;

  }

  /**
   * Parse the MAL database id of the anime.
   * <input type='hidden' name='aid' value='16870'>
   * 
   * @param Simple_html_dom $html
   * @return Integer
   */
  private static function parseId($html) {

    return (int)$html->find('div#contentWrapper #editdiv input[name="aid"]', 0)->value;
  
  }

  /**
   * Parse the name of the anime.
   * <span itemprop='name'>The Last: Naruto the Movie</span>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private static function parseName($html) {

    return $html->find('div#contentWrapper div h1.h1 span', 0)->innertext;
  
  }

  /**
   * Parse the MAL url for the anime.
   * <a href='https://myanimelist.net/anime/16870/The_Last__Naruto_the_Movie' 
   * class='horiznav_active'>Details</a>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private static function parseMalUrl($html) {

    return $html->find('div#contentWrapper #content #horiznav_nav ul li a', 0)->href;
  
  }

  /**
   * Parse the main image url for the anime.
   * <img src='https://myanimelist.cdn-dena.com/images/anime/10/68631.jpg' alt='The Last:
   * Naruto the Movie' class='ac' itemprop='image'>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private static function parseImageUrl($html) {

    // Only when an image exists can we start parsing.
    if($html->find('div#contentWrapper div#content table div a img.ac', 0)) {

      $imageSource = $html->find('div#contentWrapper div#content table div a img.ac', 0)->src;

      // Contains two with ?s=
      if(strpos($imageSource, ' 1x,') !== false) {
        $imageSource = explode(' 1x,', $imageSource)[0];
      }

      // URL is /r/ and contains ?s=
      if(strpos($imageSource, '/r/') !== false) {
        $imageSource = str_replace('/r/50x70', '', explode('?s=', $imageSource)[0]);
      }

      // URL has a modifier
      if(strpos($imageSource, 't.jpg') !== false || strpos($imageSource, 'l.jpg') !== false) {
        $imageSource = str_replace('l.jpg', '.jpg', str_replace('t.jpg', '.jpg', $imageSource));
      }

      return $imageSource;

    }
  
  }

  /**
   * Parse the MAL community score for the anime
   * <div class='fl-l score' data-title='score' data-user='80,686 users' title='indicates a
   * weighted score. Please note that 'Not yet aired' titles are excluded.'>     7.88  </div>
   * 
   * @param Simple_html_dom $html
   * @return Float
   */
  private static function parseScore($html) {

    $element = $html->find('div#contentWrapper div#content div.anime-detail-header-stats .score', 0);
    if(trim($element->plaintext) !== 'N/A') {
      return (float)trim($element->plaintext);
    }

  }

  /**
   * Parse the MAL ranking number.
   * <span class='numbers ranked' title='based on the top anime page. Please note that 'Not yet
   * aired' and 'R18+' titles are excluded.'>Ranked <strong>#735</strong></span>
   * 
   * @param Simple_html_dom $html
   * @return Integer
   */
  private static function parseRank($html) {

    $element = $html->find('div#contentWrapper div#content div.anime-detail-header-stats span.ranked strong', 0);
    if(trim($element->plaintext) !== 'N/A') {
      // Remove the hashtag
      return (int)substr($element->plaintext, 1);
    }

  }

  /**
   * Parse the popularity number on MAL.
   * <span class='numbers popularity'>Popularity <strong>#552</strong></span>
   * 
   * @param Simple_html_dom $html
   * @return Integer
   */
  private static function parsePopularity($html) {
    
    $element = $html->find('div#contentWrapper div#content div.anime-detail-header-stats span.popularity strong', 0);
    if($element->plaintext != 'N/A') {
      // Remove the hashtag
      return (int)substr($element->plaintext, 1);
    }

  }

  /**
   * Parse the synopsis.
   * <span itemprop='description'>blahblah<br>blahblahblahb</span>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private static function parseSynopsis($html) {

    $element = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom-rel table td span[itemprop=description]', 0);
    if($element && strpos($element->innertext, 'No synopsis has been added yet') === false) {
      return (string)htmlspecialchars_decode(html_entity_decode(trim($element->innertext), 0, 'UTF-8'));
    }
  }

  /**
   * Parse the English titles on MAL
   * <div class='spaceit_pad'>
   *   <span class='dark_text'>English:</span>
   *     The Last: Naruto the Movie
   * 
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Array
   */
  private static function parseEnglishTitles($html) {

    $alternativeTitleGroups = $html->find('div#contentWrapper div#content table div.js-scrollfix-bottom .spaceit_pad');
    foreach($alternativeTitleGroups as $value) {
      if(strpos($value->plaintext, 'English:') !== false) {
        return explode(', ', trim($value->find('text', 2)->innertext));
      }
    }
    return [];

  }

  /**
   * Parse the Japanese titles on MAL
   * <div class='spaceit_pad'>
   *   <span class='dark_text'>Japanese:</span>
   *     纏り最高
   * 
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Array
   */
  private static function parseJapaneseTitles($html) {

    $alternativeTitleGroups = $html->find('div#contentWrapper div#content table div.js-scrollfix-bottom .spaceit_pad');
    foreach($alternativeTitleGroups as $value) {
      if(strpos($value->plaintext, 'Japanese:') !== false) {
        return explode(', ', trim($value->find('text', 2)->innertext));
      }
    }
    return [];

  }

  /**
   * Parse the synonymous titles on MAL
   * <div class='spaceit_pad'>
   *   <span class='dark_text'>Synonyms:</span>
   *     mAPI
   * 
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Array
   */
  private static function parseSynonymousTitles($html) {

    $alternativeTitleGroups = $html->find('div#contentWrapper div#content table div.js-scrollfix-bottom .spaceit_pad');
    foreach($alternativeTitleGroups as $value) {
      if(strpos($value->plaintext, 'Synonyms:') !== false) {
        return explode(', ', trim($value->find('text', 2)->innertext));
      }
    }
    return [];

  }

  /**
   * Parse the media type.
   * <div>
   *   <span class='dark_text'>Type:</span>
   *   <a href='https://myanimelist.net/topanime.php?type=movie'>Movie</a>
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private static function parseType($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Type:') !== false) {
        if(strpos($value->plaintext, 'Unknown') === false) {
          // MAL has no links for Music types anymore
          if($value->find('a', 0)) {
            return strtolower(trim($value->find('a', 0)->innertext));
          } else {
            return strtolower(trim($value->find('text', 2)->innertext));
          }
        }
      }
    }

  }

  /**
   * Parse the number of episodes.
   * <div>
   *   <span class='dark_text'>Episodes:</span>
   *   1
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Integer
   */
  private static function parseEpisodes($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Episodes:') !== false) {
        if(strpos($value->plaintext, 'Unknown') === false) {
          return (int)trim($value->find('text', 2)->innertext);
        }
      }
    }

  }

  /**
   * Parse the airing status of the anime.
   * <div>
   *   <span class='dark_text'>Status:</span>
   *   Finished Airing
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private static function parseAirStatus($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      // Additional check for class because personal list status is also "Status:"
      if(strpos($value->plaintext, 'Status:') !== false &&
         $value->find('.dark_text', 0)) {
        if(strpos($value->plaintext, 'Unknown') === false) {
          $air_status = trim($value->find('text', 2)->innertext);
          // Lowercase and add underscores for consistency with other endpoints
          return str_replace(' ', '_', strtolower($air_status));
        }
      }
    }

  }

  /**
   * Parse the air dates.
   * <div>
   *   <span class='dark_text'>Aired:</span>
   *   Dec 6, 2014
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Array\String
   */
  private static function parseAirDates($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    $air_dates = [];
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Aired:') !== false) {
        if(strpos($value->plaintext, 'Unknown') === false &&
           strpos($value->plaintext, 'Not available') === false) {
          if(strpos($value->find('text', 2)->innertext, ' to ') !== false) {
            // contains 'to'
            
            $exploded = array_map('trim', explode(' to ', $value->find('text', 2)->innertext)); // Neccessasry trimming

            $air_dates[0] = Time::convert($exploded[0]);
            $air_dates[1] = Time::convert($exploded[1]);

            return $air_dates;
            
          } else {

            return Time::convert(trim($value->find('text', 2)->innertext)); // Neccessasry trimming
            // No need to return an array here because we check if the result is a string
            // when calling the function and we set premiere_date instead of air_dates if so.
          }
        }
      }
    }

  }

  /**
   * Parse the season the anime aired.
   * <div>
   *   <span class='dark_text'>Premiered:</span>
   *   <a href='https://myanimelist.net/anime/season/2014/fall'>Fall 2014</a>
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private static function parseSeason($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Premiered:') !== false) {
        if(strpos($value->plaintext, '?') === false) {
          return trim($value->find('a', 0)->innertext);
        }
      }
    }

  }

  /**
   * Parse the time the anime broadcasted on TV.
   * <div>
   *   <span class='dark_text'>Broadcast:</span>
   *   Thursdays at 23:30 (JST)
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private static function parseAirTime($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Broadcast:') !== false) {
        if(strpos($value->plaintext, 'Not scheduled once per week') !== false) {
          return 'Irregular Schedule';
        } elseif(strpos($value->plaintext, 'Unknown') === false) {
          return trim($value->find('text', 2)->innertext);
        }
      }
    }

  }

  /**
   * Parse the producers in the making of the anime.
   * <div>
   *   <span class='dark_text'>Producers:</span>
   *   <a href='/anime/producer/64/Sotsu' title='Sotsu'>Sotsu</a>
   *   ,
   *   <a href='/anime/producer/166/Movic' title='Movic'>Movic</a>
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Array
   */
  private static function parseProducers($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Producers:') !== false) {
        // Define the array outside of checking none, so that it returns [] when nothing is found
        $producers_arr = [];
        if(strpos($value->plaintext, 'None found') === false) {
          // TODO: Change below to joining the find('text') into an array and removing the first item
          foreach($value->find('a') as $producer) {
            $reference = new BriefReferenceModel();
            $reference->set('id', (int)explode('/', $producer->href)[3]);
            $reference->set('name', $producer->innertext);
            array_push($producers_arr, $reference->info);
          }
        }

        return $producers_arr;
      }
    }

  }

  /**
   * Parse the licensors in the broadcast of the anime.
   * <div>
   *   <span class='dark_text'>Licensors:</span>
   *   <a href='/producer/376/Sentai_Filmworks' title='Sentai Filmworks'>Sentai Filmworks</a>
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Array
   */
  private static function parseLicensors($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Licensors:') !== false) {
        $licensors_arr = [];
        if(strpos($value->innertext, 'None found') === false) {
          foreach($value->find('a') as $licensor) {
            $reference = new BriefReferenceModel();
            $reference->set('id', (int)explode('/', $licensor->href)[3]);
            $reference->set('name', $licensor->innertext);
            array_push($licensors_arr, $reference->info);
          }
        }

        return $licensors_arr;
      }
    }

  }

  /**
   * Parse the studios behind the entire anime.
   * <div>
   *   <span class='dark_text'>Licensors:</span>
   *   <a href='/anime/producer/132/PA_Works' title='P.A. Works'>P.A. Works</a>
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Array
   */
  private static function parseStudios($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Studios:') !== false) {
        $studios_arr = [];
        if(strpos($value->innertext, 'None found') === false) {
          foreach($value->find('a') as $studio) {
            $reference = new BriefReferenceModel();
            $reference->set('id', (int)explode('/', $studio->href)[3]);
            $reference->set('name', $studio->innertext);
            array_push($studios_arr, $reference->info);
          }
        }

        return $studios_arr;
      }
    }

  }

  /**
   * Parse the original anime source.
   * <div>
   *   <span class='dark_text'>Source:</span>
   *   Original
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private static function parseSource($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Source:') !== false) {
        if(strpos($value->innertext, 'Unknown') === false) {
          return trim($value->find('text', 2)->innertext);
        }
      }
    }

  }

  /**
   * Parse the genres the anime is categorised into on MAL.
   * Since other endpoints accept genres by string, it's better to keep things consistent by not
   * using a BriefReference model here.
   * 
   * <div>
   *   <span class='dark_text'>Genres:</span>
   *   <a href='/anime/genre/4/Comedy' title='Comedy'>Comedy</a>
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private static function parseGenres($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Genres:') !== false) {
        if(strpos($value->innertext, 'No genres have been added yet.') === false) {
          $genres = $value->find('text');
          $genres = array_slice($genres, 3);
          return array_values(array_filter(array_map(function($genre) {
            // Remove whitespace and lowercase it so it's identical to the ones used
            // in filtering /anime/search
            return str_replace(' ', '', strtolower(trim($genre)));
          }, $genres), function($genre) {
            return $genre !== ',';
          }));
        }
      }
    }

  }

  /**
   * Parse the episode duration listed on MAL. It will convert all hours and seconds into minutes,
   * and if an anime is less than a minute, the duration will be rounded up, to 1.
   * 
   * <div>
   *   <span class='dark_text'>Duration:</span>
   *   24 min. per ep.
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Integer
   */
  private static function parseDurationPerEpisode($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Duration:') !== false) {
        if(strpos($value->innertext, 'Unknown') === false) {
          $total_minutes = 0;
          if(strpos($value->plaintext, ' hr.') !== false) {
            preg_match('/\d+(?= hr.)/', $value->plaintext, $matches);
            $hour = trim($matches[0]);
            $hour_minutes = (int)$hour * 60;
            $total_minutes += $hour_minutes;
          }
          if(strpos($value->plaintext, ' min.') !== false) {
            preg_match('/\d+(?= min.)/', $value->plaintext, $matches);
            $minutes = trim($matches[0]);
            $total_minutes += (int)$minutes;
          }
          if(strpos($value->plaintext, ' sec.') !== false) { // Example id: 33902
            preg_match('/\d+(?= sec.)/', $value->plaintext, $matches);
            $seconds = trim($matches[0]);
            $seconds_minutes = (int)$seconds / 60;
            $total_minutes += (int)ceil($seconds_minutes); // Cast to int because ceil() returns float
          }

          return $total_minutes;
        }
      }
    }

  }

  /**
   * Calculate the total episode duration.
   * 
   * @param Simple_html_dom $html
   * @return Integer
   */
  private static function parseDurationTotal($episodes, $minutes) {

    if($episodes != null && $minutes != null) {
      return $episodes * $minutes;
    }

  }

  /**
   * Parse the classification name and description.
   * <div>
   *   <span class='dark_text'>Rating:</span>
   *   PG-13 - Teens 13 or older
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Array
   */
  private static function parseClassification($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Rating:') !== false) {
        if(strpos($value->innertext, 'Unknown') === false &&
           strpos($value->innertext, 'None') === false) {
          $classification_fulltext = trim($value->find('text' , 2)->innertext);
          return array_map('trim', explode(' -', $classification_fulltext));
        }
      }
    }

  }

  /**
   * Parse the number of members who marked a score in their list.
   * <div itemprop='aggregateRating' itemscope itemtype='http://schema.org/AggregateRating' class='po-r js-statistics-info di-ib' data-id='info1'>
   *   <span class='dark_text'>Score:</span>
   *   <span itemprop='ratingValue'>8.46</span>
   *   <sup>1</sup>
   *    (scored by )
   *   <span itemprop='ratingCount'>66,462</span>
   *    users)
   *   <meta itemprop='bestRating' content='10'>
   *   <meta itemprop='worstRating' content='1'>
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Integer
   */
  private static function parseMembersScored($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Score:') !== false) {
        if(strpos($value->innertext, 'users') !== false) {
          return (int)trim(str_replace(',', '', $value->find('span', 2)->innertext));
        }
      }
    }

  }

  /**
   * Parse the number of members who have the anime in their list.
   * <div class='spaceit'>
   *   <span class='dark_text'>Members:</span>
   *   195,615
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Integer
   */
  private static function parseMembersInList($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Members:') !== false) {
        return (int)trim(str_replace(',', '', $value->find('text', 2)->innertext));
      }
    }

  }

  /**
   * Parse the number of members who have the anime in their favourites.
   * <div class='spaceit'>
   *   <span class='dark_text'>Favorites:</span>
   *   4,277
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Integer
   */
  private static function parseMembersFavorited($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Favorites:') !== false) {
        return (int)trim(str_replace(',', '', $value->find('text', 2)->innertext));
      }
    }

  }

  /**
   * Parse the background information for the anime.
   * This section has a lot of complicated HTML.
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private static function parseBackground($html) {

    $background_td = $html->find('div#contentWrapper div#content .js-scrollfix-bottom-rel table td', 0);
    $background_td_section = explode('</h2>', $background_td->innertext)[2];
    $background = explode('<h2', explode('<div', $background_td_section)[0])[0];
    if(strpos($background, 'No background information has been added to this title') === false) {
      return trim($background);
    }

  }

  /**
   * Parse the other related works that are in the MAL database
   * More info: https://myanimelist.net/info.php?go=relationinfo
   * 
   * @param Simple_html_dom $html
   * @return Array
   */
  private static function parseRelations($html) {

    $relation_td = $html->find('div#contentWrapper .js-scrollfix-bottom-rel .anime_detail_related_anime tbody', 0);
    $relation_sequel = $relation_prequel
                     = $relation_alternative_setting
                     = $relation_alternative_version
                     = $relation_side_story
                     = $relation_parent_story
                     = $relation_summary
                     = $relation_full_story
                     = $relation_spin_off
                     = $relation_adaptation
                     = $relation_character
                     = $relation_other
                     = [];
    if($relation_td) {
      foreach($relation_td->find('tr') as $relation_row) {
        $key = 0;
        foreach($relation_row->find('td a') as $relation_item) {
          $reference = new BriefReferenceModel();
          $reference->set('id', (int)explode('/', $relation_item->href)[2]);
          $reference->set('name', (string)$relation_item->innertext);
          switch(strtolower(substr($relation_row->find('td text', 0)->innertext, 0, -1))) {
            case 'sequel':
              array_push($relation_sequel, $reference->info);
              break;
            case 'prequel':
              array_push($relation_prequel, $reference->info);
              break;
            case 'alternative setting':
              array_push($relation_alternative_setting, $reference->info);
              break;
            case 'alternative version':
              array_push($relation_alternative_version, $reference->info);
              break;
            case 'side story':
              array_push($relation_side_story, $reference->info);
              break;
            case 'parent story':
              array_push($relation_parent_story, $reference->info);
              break;
            case 'summary':
              array_push($relation_summary, $reference->info);
              break;
            case 'full story':
              array_push($relation_full_story, $reference->info);
              break;
            case 'spin-off':
              array_push($relation_spin_off, $reference->info);
              break;
            case 'adaptation':
              array_push($relation_adaptation, $reference->info);
              break;
            case 'character':
              array_push($relation_character, $reference->info);
              break;
            case 'other':
              array_push($relation_other, $reference->info);
              break;
            default:
              break;
          }
          $key++;
        }
      }
    }
    return [
      $relation_sequel,
      $relation_prequel,
      $relation_alternative_setting,
      $relation_alternative_version,
      $relation_side_story,
      $relation_parent_story,
      $relation_summary,
      $relation_full_story,
      $relation_spin_off,
      $relation_adaptation,
      $relation_character,
      $relation_other
    ];

  }

  /**
   * Parse the opening theme songs.
   * Thankfully these have a proper class name, .theme-songs and .theme-song.
   * 
   * @param Simple_html_dom $html
   * @return Array
   */ 
  private static function parseOpeningThemeSongs($html) {

    $openings_div = $html->find('div#contentWrapper div#content div[class=theme-songs js-theme-songs opnening]', 0);
    $openings = [];
    foreach($openings_div->find('span') as $opening) {
      // If no openings exist, there will be a div and 0 spans
      $theme_song = [
        // TODO: Maybe change this to explode by '&quot; by' and then explode again
        // But will do only if there are bug reports
        'name' => explode('&quot;', $opening->innertext)[1],
        // Artist cannot contain a parenthesis so we can use it to split artists
        // View more: https://myanimelist.net/dbchanges.php?aid=14131&t=theme
        'artist' => explode(' (', explode('&quot; by ', preg_replace('!\s+!', ' ', $opening->innertext))[1])[0]
      ];
      $episodes_str = explode(' (ep', explode('&quot; by ', $opening->innertext)[1])[1] ?? '';
      $episodes_str = str_replace('s', '', $episodes_str);
      $episodes = [];
      if($episodes_str !== '') {
        foreach(explode(', ', $episodes_str) as $episode) {
          if(strpos($episode, '-') !== false) {
            // Is a range
            array_push($episodes, [
              'from' => (int)explode('-', $episode)[0],
              'to' => (int)explode('-', $episode)[1]
            ]);
          } else {
            // Is a single episode
            array_push($episodes, [
              'from' => (int)$episode,
              'to' => (int)$episode
            ]);
          }
        }
      }
      $theme_song['episodes'] = $episodes;
      array_push($openings, $theme_song);
    }
    return $openings;
  
  }
  
  /**
   * Parse the ending theme songs.
   * 
   * @param Simple_html_dom $html
   * @return Array
   */
  private static function parseEndingThemeSongs($html) {

    $endings_div = $html->find('div#contentWrapper div#content div[class=theme-songs js-theme-songs ending]', 0);
    $endings = [];
    foreach($endings_div->find('span') as $ending) {
      // If no endings exist, there will be a div and 0 spans
      $theme_song = [
        // TODO: Maybe change this to explode by '&quot; by' and then explode again
        // But will do only if there are bug reports
        'name' => explode('&quot;', $ending->innertext)[1],
        // Artist cannot contain a parenthesis so we can use it to split artists
        // View more: https://myanimelist.net/dbchanges.php?aid=14131&t=theme
        'artist' => explode(' (', explode('&quot; by ', $ending->innertext)[1])[0]
      ];
      $episodes_str = explode(' (ep', explode('&quot; by ', $ending->innertext)[1])[1] ?? '';
      $episodes_str = str_replace('s', '', $episodes_str);
      $episodes = [];
      if($episodes_str !== '') {
        foreach(explode(', ', $episodes_str) as $episode) {
          if(strpos($episode, '-') !== false) {
            // Is a range
            array_push($episodes, [
              'from' => (int)explode('-', $episode)[0],
              'to' => (int)explode('-', $episode)[1]
            ]);
          } else {
            // Is a single episode
            array_push($episodes, [
              'from' => (int)$episode,
              'to' => (int)$episode
            ]);
          }
        }
      }
      $theme_song['episodes'] = $episodes;
      array_push($endings, $theme_song);
    }
    return $endings;
    
  }

}