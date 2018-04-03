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
class MangaSearchModel extends Model
{
  
  public $info = [
    'id' => null,
    'name' => null,
    'mal_url' => null,
    'image_url' => null,
    'score' => null,
    'type' => null,
    'volumes' => null,
    'chapters' => null,
    'publish_dates' => [
      'from' => null,
      'to' => null
    ],
    'members_inlist' => null
  ];
  
}