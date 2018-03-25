<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Models;

use Matomari\Models\Model;

/** 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class MangaInfoModel extends Model
{
  
  public $info = [
    'id' => null,
    'name' => null,
    'mal_url' => null,
    'image_url' => null,
    'score' => null,
    'rank' => null,
    'popularity' => null,
    'synopsis' => null,
    'other_titles' => [
      'english' => [],
      'japanese' => [],
      'synonyms' => []
    ],
    'type' => null,
    'volumes' => null,
    'chapters' => null,
    'publish_status' => null,
    'publish_dates' => [
      'from' => null,
      'to' => null
    ],
    'authors' => [],
    'serialization' => [],
    'genres' => [],
    'members_scored' => null,
    'members_inlist' => null,
    'members_favorited' => null,
    'background' => null,
    'relations' => [ // https://myanimelist.net/info.php?go=relationinfo
      'sequel' => [],
      'prequel' => [],
      'alternative_setting' => [],
      'alternative_version' => [],
      'side_story' => [],
      'parent_story' => [],
      'summary' => [],
      'full_story' => [],
      'spin_off' => [],
      'adaptation' => [],
      'character' => [],
      'other' => []
    ]
  ];
  
}