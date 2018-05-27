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
 * @OAS\Schema(
 *   title="Anime Search Result",
 *   type="object",
 *   xml={
 *     "name": "root"
 *   },
 *   required={
 *     "id",
 *     "name",
 *     "mal_url",
 *     "image_url",
 *     "score",
 *     "type",
 *     "episodes",
 *     "air_dates",
 *     "classification",
 *     "members_inlist"
 *   }
 * )
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class AnimeSearchModel extends Model
{

  /** 
   * @OAS\Property(
   *   description="The anime ID on MAL",
   *   example=36475
   * )
   * @var Integer
   */
  public $id;

  /**
   * @OAS\Property(
   *   description="The official romaji anime name",
   *   example="Sword Art Online Alternative: Gun Gale Online"
   * )
   * @var String
   */
  public $name;
  
  /**
   * @OAS\Property(
   *   description="The browser URL for the anime on MAL",
   *   example="https://myanimelist.net/anime/36475/Sword_Art_Online_Alternative__Gun_Gale_Online"
   * )
   * @var String
   */
  public $mal_url;

  /**
   * @OAS\Property(
   *   description="The direct URL to the anime cover image on MAL",
   *   example="https://myanimelist.cdn-dena.com/images/anime/1788/90355.jpg"
   * )
   * @var String
   */
  public $image_url;

  /**
   * @OAS\Property(
   *   description="The MAL community score to 2 decimal places",
   *   nullable=true,
   *   example=7.3
   * )
   * @var Float
   */
  public $score;

  /**
   * @OAS\Property(
   *   description="The anime media type",
   *   enum={"tv","ova","movie","special","ona","music"},
   *   example="tv"
   * )
   * @var String
   */
  public $type;

  /**
   * @OAS\Property(
   *   description="The total number of episodes in the anime",
   *   nullable=true,
   *   example=12
   * )
   * @var Integer
   */
  public $episodes;

  /**
   * @OAS\Property(
   *   description="The air dates of the anime",
   *   required={"from","to"},
   *   @OAS\Property(
   *     property="from",
   *     type="string",
   *     description="The air start date in ISO 8601 compatible format",
   *     nullable=true,
   *     example="2018-04-08"
   *   ),
   *   @OAS\Property(
   *     property="to",
   *     type="string",
   *     nullable=true,
   *     description="The air end date in ISO 8601 compatible format",
   *     nullable=true,
   *     example="2019-10"
   *   )
   * )
   * @var Object
   */
  public $air_dates = [
    'from' => null,
    'to' => null
  ];

  /**
   * @OAS\Property(
   *   description="The classification of the anime on MAL",
   *   required={"name"},
   *   @OAS\Property(
   *     property="name",
   *     type="string",
   *     description="The symbol name for the classification of the anime",
   *     example="PG-13"
   *   )
   * )
   * @var Object
   */
  public $classification = [
    'name' => null
  ];

  /**
   * @OAS\Property(
   *   description="The number of people who have the anime in their animelist",
   *   example=114512
   * )
   * @var Integer
   */
  public $members_inlist;
  
}