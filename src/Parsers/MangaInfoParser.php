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
use Matomari\Models\MangaInfoModel;
use Matomari\Models\BriefReferenceModel;

/**
 * Parse HTML of manga info pages into MangaInfo Models
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class MangaInfoParser extends Parser
{

  /**
   * Parse the HTML of the manga info response, and return the generated MangaInfo model.
   * 
   * @param String $response The response HTML from MAL
   * @return MangaInfo
   * @since 0.5
   */
  public function parse($response) {

    $html = $this->parser->str_get_html($response);

    if(!is_object($html)) {
      throw new MatomariError('The code for MAL is not valid HTML.', 502);
    }
   
    $manga = new MangaInfoModel();

    $manga->set('id', self::parseId($html));
    $manga->set('name', self::parseName($html));
    $manga->set('mal_url', self::parseMalUrl($html));
    $manga->set('image_url', self::parseImageUrl($html));
    $manga->set('score', self::parseScore($html));
    $manga->set('rank', self::parseRank($html));
    $manga->set('popularity', self::parsePopularity($html));
    $manga->set('synopsis', self::parseSynopsis($html));
    $manga->set('other_titles//english', self::parseEnglishTitles($html));
    $manga->set('other_titles//japanese', self::parseJapaneseTitles($html));
    $manga->set('other_titles//synonyms', self::parseSynonymousTitles($html));
    $manga->set('type', self::parseType($html));
    $manga->set('volumes', self::parseVolumes($html));
    $manga->set('chapters', self::parseChapters($html));
    $manga->set('publish_status', self::parsePublishStatus($html));
    $publish_dates = self::parsePublishDates($html);
    $manga->set('publish_dates//from', $publish_dates[0]);
    $manga->set('publish_dates//to', $publish_dates[1]);
    $manga->set('authors', self::parseAuthors($html));
    $manga->set('serialization', self::parseSerialization($html));
    $manga->set('genres', self::parseGenres($html));
    $manga->set('members_scored', self::parseMembersScored($html));
    $manga->set('members_inlist', self::parseMembersInList($html));
    $manga->set('members_favorited', self::parseMembersFavorited($html));
    $manga->set('background', self::parseBackground($html));
    $relations = self::parseRelations($html);
    $manga->set('relations//sequel', $relations[0]);
    $manga->set('relations//prequel', $relations[1]);
    $manga->set('relations//alternative_setting', $relations[2]);
    $manga->set('relations//alternative_version', $relations[3]);
    $manga->set('relations//side_story', $relations[4]);
    $manga->set('relations//parent_story', $relations[5]);
    $manga->set('relations//summary', $relations[6]);
    $manga->set('relations//full_story', $relations[7]);
    $manga->set('relations//spin_off', $relations[8]);
    $manga->set('relations//adaptation', $relations[9]);
    $manga->set('relations//character', $relations[10]);
    $manga->set('relations//other', $relations[11]);

    return $manga->asArray();

  }

  /**
   * Parse the MAL database id of the manga.
   * <input type='hidden' name='mid' value='13'>
   * 
   * @param Simple_html_dom $html
   * @return Integer
   */
  private function parseId($html) {

    return (int)$html->find('div#contentWrapper #editdiv input[name="mid"]', 0)->value;
  
  }

  /**
   * Parse the name of the manga.
   * <span itemprop='name'>One Piece</span>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private function parseName($html) {

    return $html->find('div#contentWrapper div h1.h1 span', 0)->innertext;
  
  }

  /**
   * Parse the MAL url for the manga.
   * <a href='/manga/13/One_Piece' 
   * class='horiznav_active'>Details</a>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private function parseMalUrl($html) {

    return 'https://myanimelist.net' . 
    $html->find('div#contentWrapper #content #horiznav_nav ul li a', 0)->href;
  
  }

  /**
   * Parse the main image url for the manga.
   * <img src='https://myanimelist.cdn-dena.com/images/manga/3/55539.jpg' alt='One Piece'
   * class='ac' itemprop='image'>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private function parseImageUrl($html) {

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
   * Parse the MAL community score for the manga
   * <div class='fl-l score' data-title='score' data-user='122,286 users' title='indicates a
   * weighted score. Please note that 'Not yet published' titles are excluded.'>     9.02  </div>
   * 
   * @param Simple_html_dom $html
   * @return Float
   */
  private function parseScore($html) {

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
  private function parseRank($html) {

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
  private function parsePopularity($html) {
    
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
  private function parseSynopsis($html) {

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
  private function parseEnglishTitles($html) {

    $alternativeTitleGroups = $html->find('div#contentWrapper div#content table div.js-scrollfix-bottom .spaceit_pad');
    foreach($alternativeTitleGroups as $value) {
      if(strpos($value->plaintext, 'English:') !== false) {
        return explode(', ', trim($value->find('text', 1)->innertext));
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
  private function parseJapaneseTitles($html) {

    $alternativeTitleGroups = $html->find('div#contentWrapper div#content table div.js-scrollfix-bottom .spaceit_pad');
    foreach($alternativeTitleGroups as $value) {
      if(strpos($value->plaintext, 'Japanese:') !== false) {
        return explode(', ', trim($value->find('text', 1)->innertext));
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
  private function parseSynonymousTitles($html) {

    $alternativeTitleGroups = $html->find('div#contentWrapper div#content table div.js-scrollfix-bottom .spaceit_pad');
    foreach($alternativeTitleGroups as $value) {
      if(strpos($value->plaintext, 'Synonyms:') !== false) {
        return explode(', ', trim($value->find('text', 1)->innertext));
      }
    }
    return [];

  }

  /**
   * Parse the media type.
   * <div>
   *   <span class='dark_text'>Type:</span>
   *   <a href='/topmanga.php?type=novels'>Novel</a>
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private function parseType($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Type:') !== false) {
        if(strpos($value->plaintext, 'Unknown') === false) {
          return strtolower(trim($value->find('text', 2)->innertext));
        }
      }
    }

  }

  /**
   * Parse the number of volumes.
   * <div>
   *   <span class='dark_text'>Volumes:</span>
   *   Unknown
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Integer
   */
  private function parseVolumes($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Volumes:') !== false) {
        if(strpos($value->plaintext, 'Unknown') === false) {
          return (int)trim($value->find('text', 1)->innertext);
        }
      }
    }

  }

  /**
   * Parse the number of chapters.
   * <div>
   *   <span class='dark_text'>Chapters:</span>
   *   5
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Integer
   */
  private function parseChapters($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Chapters:') !== false) {
        if(strpos($value->plaintext, 'Unknown') === false) {
          return (int)trim($value->find('text', 1)->innertext);
        }
      }
    }

  }

  /**
   * Parse the publishing status of the manga.
   * <div>
   *   <span class='dark_text'>Status:</span>
   *   Publishing
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private function parsePublishStatus($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      // Additional check for class because personal list status is also "Status:"
      if(strpos($value->plaintext, 'Status:') !== false &&
         $value->find('.dark_text', 0)) {
        if(strpos($value->plaintext, 'Unknown') === false) {
          $publish_status = trim($value->find('text', 1)->innertext);
          // Lowercase and add underscores for consistency with other endpoints
          return str_replace(' ', '_', strtolower($publish_status));
        }
      }
    }
  }

  /**
   * Parse the publish dates.
   * <div>
   *   <span class='dark_text'>Published:</span>
   *   Apr  25, 2012 to ?
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Array\String
   */
  private function parsePublishDates($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    $publish_dates = [];
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Published:') !== false) {
        if(strpos($value->plaintext, 'Unknown') === false &&
           strpos($value->plaintext, 'Not available') === false) {
          $date_string = preg_replace('!\s+!', ' ', $value->find('text', 1)->innertext);
          if(strpos($date_string, ' to ') !== false) {
            // contains 'to'

            $exploded = array_map('trim', explode(' to ', $date_string)); // Necessary trimming

            // Manga's info page weirdly has two spaces instead of one, so replace those
            foreach($exploded as $key => $explooooooosion) {
              $exploded[$key] = preg_replace('!\s+!', ' ', $explooooooosion);
            }

            $publish_dates[0] = Time::convert($exploded[0]);
            $publish_dates[1] = Time::convert($exploded[1]);

            return $publish_dates;

          } else {
            
            $publish_date = Time::convert(trim($date_string)); // Neccessasry trimming
            return [$publish_date, $publish_date]; // Compensate for the lack of a second date

          }
        }
      }
    }

  }

  /**
   * Parse the authors who wrote the manga.
   * <div>
   *   <span class='dark_text'>Authors:</span>
   *   <a href='/people/4212/Yuu_Kamiya'>Kamiya, Yuu</a> (Story & Art)
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Array
   */
  private function parseAuthors($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Authors:') !== false) {
        // Define the array outside of checking none, so that it returns [] when nothing is found
        $authors_arr = [];
        if(strpos($value->plaintext, 'None') === false) {
          // TODO: Change below to joining the find('text') into an array and removing the first item
          foreach($value->find('a') as $author) {
            $reference = new BriefReferenceModel();
            $reference->set('id', (int)explode('/', $author->href)[2]);
            $reference->set('name', $author->innertext);
            array_push($authors_arr, $reference->asArray());
          }
        }

        return $authors_arr;
      }
    }

  }

  /**
   * Parse the magazines which serialized this manga.
   * <div>
   *   <span class='dark_text'>Serialization:</span>
   *   None
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Array
   */
  private function parseSerialization($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Serialization:') !== false) {
        $serialization_arr = [];
        if(strpos($value->innertext, 'None') === false) {
          foreach($value->find('a') as $serialization) {
            $reference = new BriefReferenceModel();
            $reference->set('id', (int)explode('/', $serialization->href)[3]);
            $reference->set('name', $serialization->innertext);
            array_push($serialization_arr, $reference->asArray());
          }
        }

        return $serialization_arr;
      }
    }

  }

  /**
   * Parse the genres the manga is categorised into on MAL.
   * Since other endpoints accept genres by string, it's better to keep things consistent by not
   * using a BriefReference model here.
   * 
   * <div>
   *   <span class='dark_text'>Genres:</span>
   *   <a href='/manga/genre/4/Comedy' title='Comedy'>Comedy</a>
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private function parseGenres($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Genres:') !== false) {
        if(strpos($value->innertext, 'No genres have been added yet.') === false) {
          $genres = $value->find('text');
          $genres = array_slice($genres, 2); // Unlike anime which is 3
          return array_values(array_filter(array_map(function($genre) {
            // Remove whitespace and lowercase it so it's identical to the ones used
            // in filtering /manga/search
            return str_replace(' ', '', strtolower(trim($genre)));
          }, $genres), function($genre) {
            return $genre !== ',';
          }));
        }
      }
    }

  }

  /**
   * Parse the number of members who marked a score in their list.
   * <span itemprop='aggregateRating' itemscope itemtype='http://schema.org/AggregateRating' class='po-r js-statistics-info di-ib' data-id='info1'>
   *   <span class='dark_text'>Score:</span>
   *   <span itemprop='ratingValue'>8.67</span>
   *   <sup>1</sup>
   *    (scored by )
   *   <span itemprop='ratingCount'>6748</span>
   *    users)
   *   <meta itemprop='bestRating' content='10'>
   *   <meta itemprop='worstRating' content='1'>
   * </span>
   * 
   * @param Simple_html_dom $html
   * @return Integer
   */
  private function parseMembersScored($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Score:') !== false) {
        if(strpos($value->innertext, 'users') !== false) {
          return (int)trim(str_replace(',', '', $value->find('span', 1)->innertext));
        }
      }
    }

  }

  /**
   * Parse the number of members who have the manga in their list.
   * <div class='spaceit'>
   *   <span class='dark_text'>Members:</span>
   *   184,669
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Integer
   */
  private function parseMembersInList($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Members:') !== false) {
        return (int)trim(str_replace(',', '', $value->find('text', 1)->innertext));
      }
    }

  }

  /**
   * Parse the number of members who have the manga in their favourites.
   * <div class='spaceit'>
   *   <span class='dark_text'>Favorites:</span>
   *   40,310
   * </div>
   * 
   * @param Simple_html_dom $html
   * @return Integer
   */
  private function parseMembersFavorited($html) {

    $sidebarInformation = $html->find('div#contentWrapper div#content div.js-scrollfix-bottom div');
    foreach($sidebarInformation as $value) {
      if(strpos($value->plaintext, 'Favorites:') !== false) {
        return (int)trim(str_replace(',', '', $value->find('text', 1)->innertext));
      }
    }

  }

  /**
   * Parse the background information for the manga.
   * This section has a lot of complicated HTML.
   * 
   * @param Simple_html_dom $html
   * @return String
   */
  private function parseBackground($html) {

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
  private function parseRelations($html) {

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
              array_push($relation_sequel, $reference->asArray());
              break;
            case 'prequel':
              array_push($relation_prequel, $reference->asArray());
              break;
            case 'alternative setting':
              array_push($relation_alternative_setting, $reference->asArray());
              break;
            case 'alternative version':
              array_push($relation_alternative_version, $reference->asArray());
              break;
            case 'side story':
              array_push($relation_side_story, $reference->asArray());
              break;
            case 'parent story':
              array_push($relation_parent_story, $reference->asArray());
              break;
            case 'summary':
              array_push($relation_summary, $reference->asArray());
              break;
            case 'full story':
              array_push($relation_full_story, $reference->asArray());
              break;
            case 'spin-off':
              array_push($relation_spin_off, $reference->asArray());
              break;
            case 'adaptation':
              array_push($relation_adaptation, $reference->asArray());
              break;
            case 'character':
              array_push($relation_character, $reference->asArray());
              break;
            case 'other':
              array_push($relation_other, $reference->asArray());
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

}