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

  public $id;

  public $name;

  public $mal_url;

  public $image_url;

  public $score;

  public $rank;

  public $popularity;

  public $synopsis;

  public $other_titles = [
    'english' => [],
    'japanese' => [],
    'synonyms' => []
  ];

  public $type;

  public $volumes;

  public $chapters;

  public $publish_status;

  public $publish_dates = [
    'from' => null,
    'to' => null
  ];

  public $authors = [];

  public $serialization = [];

  public $genres = [];

  public $members_scored;

  public $members_inlist;

  public $members_favorited;

  public $background;

  public $relations = [ // https://myanimelist.net/info.php?go=relationinfo
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
  ];
  
}