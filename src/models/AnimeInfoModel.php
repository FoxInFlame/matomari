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
class AnimeInfoModel extends Model
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
    'episodes' => null,
    'air_status' => null,
    'air_dates' => [
      'from' => null,
      'to' => null
    ],
    'season' => null,
    'air_time' => null,
    'premiere_date' => null,
    'producers' => [],
    'licensors' => [],
    'studios' => [],
    'source' => null,
    'genres' => [],
    'duration' => [
      'total' => null,
      'per_episode' => null
    ],
    'classification' => [
      'name' => null,
      'description' => null
    ],
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
    ],
    'theme_songs' => [
      'openings' => [],
      'endings' => []
    ]
  ];
  
}