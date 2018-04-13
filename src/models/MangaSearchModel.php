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
  
  public $id;

  public $name;

  public $mal_url;

  public $image_url;

  public $score;

  public $type;

  public $volumes;

  public $chapters;

  public $publish_dates = [
    'from' => null,
    'to' => null
  ];

  public $classification = [
    'name' => null
  ];

  public $members_inlist;
  
}