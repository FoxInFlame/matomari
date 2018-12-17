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
 * @OA\Schema(
 *   title="Anime Ranking Entry",
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
 *     "members_inlist"
 *   }
 * )
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class AnimeRankingModel extends Model
{

  /** 
   * @OA\Property(
   *   description="The anime ID on MAL",
   *   example=36475
   * )
   * @var Integer
   */
  public $id;

  /**
   * @OA\Property(
   *   description="The official romaji anime name",
   *   example="Sword Art Online Alternative: Gun Gale Online"
   * )
   * @var String
   */
  public $name;
  
  /**
   * @OA\Property(
   *   description="The browser URL for the anime on MAL",
   *   example="https://myanimelist.net/anime/36475/Sword_Art_Online_Alternative__Gun_Gale_Online"
   * )
   * @var String
   */
  public $mal_url;

  /**
   * @OA\Property(
   *   description="The direct URL to the anime cover image on MAL",
   *   example="https://myanimelist.cdn-dena.com/images/anime/1788/90355.jpg"
   * )
   * @var String
   */
  public $image_url;

  /**
   * @OA\Property(
   *   description="The MAL community score to 2 decimal places",
   *   nullable=true,
   *   example=7.3
   * )
   * @var Float
   */
  public $score;

  /**
   * @OA\Property(
   *   description="The overall anime ranking on MAL",
   *   nullable=true,
   *   example=2437
   * )
   * @var Integer
   */
  public $rank;

  /**
   * @OA\Property(
   *   description="The anime media type",
   *   enum={"tv","ova","movie","special","ona","music"},
   *   example="tv"
   * )
   * @var String
   */
  public $type;

  /**
   * @OA\Property(
   *   description="The total number of episodes in the anime",
   *   nullable=true,
   *   example=12
   * )
   * @var Integer
   */
  public $episodes;

  /**
   * @OA\Property(
   *   description="The air dates of the anime",
   *   required={"from","to"},
   *   @OA\Property(
   *     property="from",
   *     type="object",
   *     nullable=true,
   *     description="The air start date in ISO 8601 compatible format",
   *     ref="#/components/schemas/MatomariDate"
   *   ),
   *   @OA\Property(
   *     property="to",
   *     type="object",
   *     nullable=true,
   *     description="The air end date in ISO 8601 compatible format",
   *     ref="#/components/schemas/MatomariDate"
   *   )
   * )
   * @var Object
   */
  public $air_dates = [
    'from' => null,
    'to' => null
  ];

  /**
   * @OA\Property(
   *   description="The number of people who have the anime in their animelist",
   *   example=114512
   * )
   * @var Integer
   */
  public $members_inlist;
  
}