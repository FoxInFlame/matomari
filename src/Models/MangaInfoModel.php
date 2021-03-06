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
 *   title="Anime Info",
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
 *     "rank",
 *     "popularity",
 *     "synopsis",
 *     "other_titles",
 *     "type",
 *     "volumes",
 *     "chapters",
 *     "publish_status",
 *     "publish_dates",
 *     "authors",
 *     "serialization",
 *     "genres",
 *     "members_scored",
 *     "members_inlist",
 *     "members_favorited",
 *     "background",
 *     "relations"
 *   }
 * )
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class MangaInfoModel extends Model
{

  /** 
   * @OA\Property(
   *   description="The manga ID on MAL",
   *   example=13245
   * )
   * @var Integer
   */
  public $id;

  /**
   * @OA\Property(
   *   description="The official romaji manga name",
   *   example="Chihayafuru"
   * )
   * @var String
   */
  public $name;
  
  /**
   * @OA\Property(
   *   description="The browser URL for the manga on MAL",
   *   example="https://myanimelist.net/manga/13245/Chihayafuru"
   * )
   * @var String
   */
  public $mal_url;

  /**
   * @OA\Property(
   *   description="The direct URL to the manga cover image on MAL",
   *   example="https://myanimelist.cdn-dena.com/images/manga/2/135341.jpg"
   * )
   * @var String
   */
  public $image_url;

  /**
   * @OA\Property(
   *   description="The MAL community score to 2 decimal places",
   *   nullable=true,
   *   example=8.55
   * )
   * @var Float
   */
  public $score;

  /**
   * @OA\Property(
   *   description="The overall manga ranking on MAL",
   *   nullable=true,
   *   example=106
   * )
   * @var Integer
   */
  public $rank;

  /**
   * @OA\Property(
   *   description="The manga popularity ranking on MAL",
   *   nullable=true,
   *   example=340
   * )
   * @var Integer
   */
  public $popularity;

  /**
   * @OA\Property(
   *   description="The full synopsis for the anime, formatted in HTML",
   *   nullable=true,
   *   example="Chihaya is a girl in the sixth grade, still not old enough to even know the meaning of the word zeal. But one day, she meets Arata, a transfer student from rural Fukui prefecture. Though docile and quiet, he has an unexpected skill: his ability to play competitive karuta, a traditional Japanese card game.<br /> <br /> Chihaya is struck by his obsession with the game, along with his ability to pick out the right card and swipe it away before any of his opponents. However, Arata is transfixed by her as well, all because of her unbelievable natural talent for the game. Don&#039;t miss this story of adolescent lives and emotions playing out in the most dramatic of ways!<br /> <br /> (Source: Kodansha Comics USA)"
   * )
   * @var String
   */
  public $synopsis;

  /**
   * @OA\Property(
   *   description="The alternative titles for the manga registered on MAL",
   *   required={"english","japanese","synonyms"},
   *   @OA\Property(
   *     property="english",
   *     type="array",
   *     description="The alternative english titles on MAL",
   *     example={
   *       "Chihayafuru"
   *     },
   *     @OA\Items(
   *       type="string"
   *     )
   *   ),
   *   @OA\Property(
   *     property="japanese",
   *     type="array",
   *     description="The japanese titles on MAL",
   *     example={
   *       "ちはやふる"
   *     },
   *     @OA\Items(
   *       type="string"
   *     )
   *   ),
   *   @OA\Property(
   *     property="synonyms",
   *     type="array",
   *     description="The synonymous english titles on MAL",
   *     example={},
   *     @OA\Items(
   *       type="string"
   *     )
   *   )
   * )
   * @var Object
   */
  public $other_titles = [
    'english' => [],
    'japanese' => [],
    'synonyms' => []
  ];

  /**
   * @OA\Property(
   *   description="The manga media type",
   *   enum={"manga","novel","one-shot","doujinshi","manhwa","manhua","oel"},
   *   example="tv"
   * )
   * @var String
   */
  public $type;

  /**
   * @OA\Property(
   *   description="The total number of volumes published for the manga",
   *   nullable=true,
   *   example=8
   * )
   * @var Integer
   */
  public $volumes;

  /**
   * @OA\Property(
   *   description="The total number of chapters for the manga",
   *   nullable=true,
   *   example=88
   * )
   * @var Integer
   */
  public $chapters;

  /**
   * @OA\Property(
   *   description="The publishing status of the manga",
   *   enum={"finished","publishing","not_yet_published"},
   *   example="publishing"
   * )
   * @var String
   */
  public $publish_status;

  /**
   * @OA\Property(
   *   description="The publish dates of the manga",
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
   *   description="The authors of the manga with IDs being People IDs",
   *   example={},
   *   @OA\Items(
   *     ref="#/components/schemas/BriefReferenceModel"
   *   )
   * )
   * @var BriefReference[]
   */
  public $authors = [];

  /**
   * @OA\Property(
   *   description="The serialization of the manga with IDs being Magazine IDs",
   *   example={},
   *   @OA\Items(
   *     ref="#/components/schemas/BriefReferenceModel"
   *   )
   * )
   * @var BriefReference[]
   */
  public $serialization = [];

  /**
   * @OA\Property(
   *   description="The genres of the manga on MAL (spaces omitted)",
   *   example={
   *     "drama",
   *     "josei",
   *     "sports",
   *     "game"
   *   },
   *   @OA\Items(
   *     type="string"
   *   )
   * )
   * @var String[]
   */
  public $genres = [];

  /**
   * @OA\Property(
   *   description="The number of people who set a score on MAL",
   *   example=6074
   * )
   * @var Integer
   */
  public $members_scored;

  /**
   * @OA\Property(
   *   description="The number of people who have the manga in their mangalist",
   *   example=18739
   * )
   * @var Integer
   */
  public $members_inlist;

  /**
   * @OA\Property(
   *   description="The number of people who have the manga in their favorites",
   *   example=1282
   * )
   * @var Integer
   */
  public $members_favorited;

  /**
   * @OA\Property(
   *   description="The background information for the manga on MAL",
   *   nullable=true,
   *   example="<i>Chihayafuru</i> won the second Manga Taisho award in 2009 and the 35th Kodansha Manga Award in the shoujo manga category in 2011.<br /> <br /> Kodansha published the first three volumes of <i>Chihayafuru</i>, consisting of the first seventeen chapters, in two English/Japanese volumes under their Kodansha Bilingual Comics line, on December 21, 2011 and February 23, 2012 respectively. Kodansha Comics USA has been digitally publishing the series in English since February 14, 2017.<br /> <br /> Two live-action films were released in 2016. The first, titled <i>Chihayafuru Kaminoku</i>, opened in Japanese cinemas on March 19, followed by a second film, titled <i>Shimonoku</i>, on April 29, 2016. During a stage greeting event for the second film, a surprise announcement was made that a sequel would be produced, although details for a production schedule or release date were not revealed."
   * )
   * @var String
   */
  public $background;

  /**
   * @OA\Property(
   *   description="The various media related to the anime",
   *   required={
   *     "sequel",
   *     "prequel",
   *     "alternative_setting",
   *     "alternative_version",
   *     "side_story",
   *     "parent_story",
   *     "summary",
   *     "full_story",
   *     "spin_off",
   *     "adaptation",
   *     "character",
   *     "other"
   *   },
   *   @OA\Property(
   *     property="sequel",
   *     type="array",
   *     description="The manga that are considered sequels with IDs being Manga IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="prequel",
   *     type="array",
   *     description="The manga that are considered prequels with IDs being Manga IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="alternative_setting",
   *     type="array",
   *     description="The manga that are considered based on an alternative setting with IDs being Manga IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="alternative_version",
   *     type="array",
   *     description="The manga that are considered an alternative version with IDs being Manga IDs",
   *     example={
   *       {
   *         "id": 64123,
   *         "name": "Chihayafuru: Chuugakusei-hen"
   *       }
   *     },
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="side_story",
   *     type="array",
   *     description="The manga that are considered side stories with IDs being Manga IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="parent_story",
   *     type="array",
   *     description="The manga that are considered parent stories with IDs being Manga IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="summary",
   *     type="array",
   *     description="The manga that are considered summarised versions with IDs being Manga IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="full_story",
   *     type="array",
   *     description="The manga that are considered full story versions with IDs being Manga IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="spin_off",
   *     type="array",
   *     title="Spin Offs",
   *     description="The manga that are considered spin offs with IDs being Manga IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="adaptation",
   *     type="array",
   *     description="The manga or anime that are considered ones that were adapted upon with IDs being Manga or Anime IDs",
   *     example={
   *       {
   *         "id": 10800,
   *         "name": "Chihayafuru"
   *       },
   *       {
   *         "id": 14397,
   *         "name": "Chihayafuru 2"
   *       },
   *       {
   *         "id": 37379,
   *         "name": "Chihayafuru 3"
   *       }
   *     },
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="character",
   *     type="array",
   *     description="The manga that are considered sharing same characters with IDs being Manga IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="other",
   *     type="array",
   *     description="The manga that are considered related in another way with IDs being Manga IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   )
   * )
   * @var Object
   */
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