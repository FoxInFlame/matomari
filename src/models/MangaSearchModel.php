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
 *   title="Manga Search Result",
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
 *     "chapters",
 *     "publish_dates",
 *     "members_inlist"
 *   }
 * )
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class MangaSearchModel extends Model
{
  
  /** 
   * @OA\Property(
   *   description="The manga ID on MAL",
   *   example=86043
   * )
   * @var Integer
   */
  public $id;

  /**
   * @OA\Property(
   *   description="The official romaji manga name",
   *   example="Isekai de &quot;Kuro no Iyashi Te&quot; tte Yobareteimasu"
   * )
   * @var String
   */
  public $name;
  
  /**
   * @OA\Property(
   *   description="The browser URL for the manga on MAL",
   *   example="https://myanimelist.net/manga/86043/Isekai_de_Kuro_no_Iyashi_Te_tte_Yobareteimasu"
   * )
   * @var String
   */
  public $mal_url;

  /**
   * @OA\Property(
   *   description="The direct URL to the manga cover image on MAL",
   *   example="https://myanimelist.cdn-dena.com/images/manga/3/186483.jpg"
   * )
   * @var String
   */
  public $image_url;

  /**
   * @OA\Property(
   *   description="The MAL community score to 2 decimal places",
   *   nullable=true,
   *   example=7.19
   * )
   * @var Float
   */
  public $score;

  /**
   * @OA\Property(
   *   description="The manga media type",
   *   enum={"manga", "novel", "one-shot", "doujinshi", "manhwa", "manhua", "oel"},
   *   example="manga"
   * )
   * @var String
   */
  public $type;

  /**
   * @OA\Property(
   *   description="The total number of volumes for the manga",
   *   nullable=true,
   *   example=4
   * )
   * @var Integer
   */
  public $volumes;

  /**
   * @OA\Property(
   *   description="The publish dates of the anime",
   *   required={"from","to"},
   *   @OA\Property(
   *     property="from",
   *     type="object",
   *     nullable=true,
   *     description="The publish start date in ISO 8601 compatible format",
   *     ref="#/components/schemas/MatomariDate"
   *   ),
   *   @OA\Property(
   *     property="to",
   *     type="object",
   *     nullable=true,
   *     description="The publish end date in ISO 8601 compatible format",
   *     ref="#/components/schemas/MatomariDate"
   *   )
   * )
   * @var Object
   */
  public $publish_dates = [
    'from' => null,
    'to' => null
  ];

  /**
   * @OA\Property(
   *   description="The number of people who have the manga in their mangalist",
   *   example=5526
   * )
   * @var Integer
   */
  public $members_inlist;
  
}