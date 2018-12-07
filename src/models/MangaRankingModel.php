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
 *   title="Manga Ranking Entry",
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
 *     "volumes",
 *     "publish_dates",
 *     "members_inlist"
 *   }
 * )
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class MangaRankingModel extends Model
{

  /** 
   * @OAS\Property(
   *   description="The manga ID on MAL",
   *   example=13245
   * )
   * @var Integer
   */
  public $id;

  /**
   * @OAS\Property(
   *   description="The official romaji manga name",
   *   example="Chihayafuru"
   * )
   * @var String
   */
  public $name;
  
  /**
   * @OAS\Property(
   *   description="The browser URL for the manga on MAL",
   *   example="https://myanimelist.net/manga/13245/Chihayafuru"
   * )
   * @var String
   */
  public $mal_url;

  /**
   * @OAS\Property(
   *   description="The direct URL to the manga cover image on MAL",
   *   example="https://myanimelist.cdn-dena.com/images/manga/2/135341.jpg"
   * )
   * @var String
   */
  public $image_url;

  /**
   * @OAS\Property(
   *   description="The MAL community score to 2 decimal places",
   *   nullable=true,
   *   example=8.55
   * )
   * @var Float
   */
  public $score;

  /**
   * @OAS\Property(
   *   description="The overall manga ranking on MAL",
   *   nullable=true,
   *   example=106
   * )
   * @var Integer
   */
  public $rank;

  /**
   * @OAS\Property(
   *   description="The manga media type",
   *   enum={"manga","novel","one-shot","doujinshi","manhwa","manhua","oel"},
   *   example="tv"
   * )
   * @var String
   */
  public $type;

  /**
   * @OAS\Property(
   *   description="The total number of volumes published for the manga",
   *   nullable=true,
   *   example=8
   * )
   * @var Integer
   */
  public $volumes;

  /**
   * @OAS\Property(
   *   description="The publish dates of the manga",
   *   required={"from","to"},
   *   @OAS\Property(
   *     property="from",
   *     type="string",
   *     description="The publish start date in ISO 8601 compatible format",
   *     nullable=true,
   *     example="2018-04-08"
   *   ),
   *   @OAS\Property(
   *     property="to",
   *     type="string",
   *     nullable=true,
   *     description="The publish end date in ISO 8601 compatible format",
   *     nullable=true,
   *     example="2019-10"
   *   )
   * )
   * @var Object
   */
  public $publish_dates = [
    'from' => null,
    'to' => null
  ];

  /**
   * @OAS\Property(
   *   description="The number of people who have the manga in their mangalist",
   *   example=18739
   * )
   * @var Integer
   */
  public $members_inlist;
  
}