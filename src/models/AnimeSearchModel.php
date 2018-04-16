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
 *     "classification"
 *   }
 * )
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class AnimeSearchModel extends Model
{

  /** 
   * @OAS\Property(
   *   title="Anime ID",
   *   description="The anime ID on MAL",
   *   example=36475
   * )
   * @var Integer
   */
  public $id;

  /**
   * @OAS\Property(
   *   title="Anime Name",
   *   description="The official romaji anime name",
   *   example="Sword Art Online Alternative: Gun Gale Online"
   * )
   * @var String
   */
  public $name;
  
  /**
   * @OAS\Property(
   *   title="MAL URL",
   *   description="The browser URL for the anime on MAL",
   *   example="https://myanimelist.net/anime/36475/Sword_Art_Online_Alternative__Gun_Gale_Online"
   * )
   * @var String
   */
  public $mal_url;

  /**
   * @OAS\Property(
   *   title="Image URL",
   *   description="The direct URL to the anime cover image on MAL",
   *   example="https://myanimelist.cdn-dena.com/images/anime/1788/90355.jpg"
   * )
   * @var String
   */
  public $image_url;

  /**
   * @OAS\Property(
   *   title="Score",
   *   description="The MAL community score to 2 decimal places",
   *   nullable=true,
   *   example=7.3
   * )
   * @var Float
   */
  public $score;

  /**
   * @OAS\Property(
   *   title="Type",
   *   description="The anime media type",
   *   enum={"tv","ova","movie","special","ona","music"},
   *   example="tv"
   * )
   * @var String
   */
  public $type;

  /**
   * @OAS\Property(
   *   title="Episodes",
   *   description="The total number of episodes in the anime",
   *   nullable=true,
   *   example=12
   * )
   * @var Integer
   */
  public $episodes;

  /**
   * @OAS\Property(
   *   title="Air Dates",
   *   description="The air dates of the anime",
   *   required={"from","to"},
   *   @OAS\Property(
   *     property="from",
   *     type="string",
   *     title="Air Start Date",
   *     description="The air start date in ISO 8601 compatible format",
   *     nullable=true,
   *     example="2018-04-08"
   *   ),
   *   @OAS\Property(
   *     property="to",
   *     type="string",
   *     nullable=true,
   *     title="Air End Date",
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
   *   title="Classification",
   *   description="The classification of the anime on MAL",
   *   required={"name"},
   *   @OAS\Property(
   *     property="name",
   *     type="string",
   *     title="Classification Name",
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
   *   title="Members In List",
   *   description="The number of people who have the anime in their animelist",
   *   example=114512
   * )
   * @var Integer
   */
  public $members_inlist;
  
}