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
class AnimeInfo extends Model
{
  public $info = array(
    "id" => null,
    "title" => null,
    "mal_url" => null,
    "image_url" => null,
    "score" => null,
    "rank" => null,
    "popularity" => null,
    "synopsis" => null,
    "other_titles" => array(),
    "type" => null,
    "episodes" => null,
    "air_status" => null,
    "air_dates" => array(
      "from" => null,
      "to" => null
    ),
    "season" => null,
    "air_time" => null,
    "premier_date" => null,
    "producers" => array(),
    "licensors" => array(),
    "studios" => array(),
    "source" => null,
    "genres" => array(),
    "duration" => array(
      "total" => null,
      "per_episode" => null
    ),
    "rating" => null,
    "members_scored" => null,
    "members_inlist" => null,
    "members_favorited" => null,
    "background" => null,
    "related" => array(),
    "theme_songs" => array()
  );
  
}