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
 *     "episodes",
 *     "air_status",
 *     "air_dates",
 *     "season",
 *     "premiere_date",
 *     "air_time",
 *     "producers",
 *     "licensors",
 *     "studios",
 *     "source",
 *     "genres",
 *     "duration",
 *     "classification",
 *     "members_scored",
 *     "members_inlist",
 *     "members_favorited",
 *     "background",
 *     "relations",
 *     "theme_songs",
 *     "external"
 *   }
 * )
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class AnimeInfoModel extends Model
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
   *   description="The anime popularity ranking on MAL",
   *   nullable=true,
   *   example=743
   * )
   * @var Integer
   */
  public $popularity;

  /**
   * @OA\Property(
   *   description="The full synopsis for the anime, formatted in HTML",
   *   nullable=true,
   *   example="Karen Kohiruimaki always felt out of place in the real world. Due to her extreme height, she found it hard to make friends with other girls her age. Everything changes when she&#039;s introduced to VR and Gun Gale Online. In GGO, Karen is free to play the cute, chibi avatar of her dreams! Can Karen find friendship in this bullet-ridden MMO...?<br /> <br /> (Source: Yen Press)"
   * )
   * @var String
   */
  public $synopsis;

  /**
   * @OA\Property(
   *   description="The alternative titles for the anime registered on MAL",
   *   required={"english","japanese","synonyms"},
   *   @OA\Property(
   *     property="english",
   *     type="array",
   *     description="The alternative english titles on MAL",
   *     example={},
   *     @OA\Items(
   *       type="string"
   *     )
   *   ),
   *   @OA\Property(
   *     property="japanese",
   *     type="array",
   *     description="The japanese titles on MAL",
   *     example={
   *       "ソードアート・オンライン オルタナティブ ガンゲイル・オンライン"
   *     },
   *     @OA\Items(
   *       type="string"
   *     )
   *   ),
   *   @OA\Property(
   *     property="synonyms",
   *     type="array",
   *     description="The synonymous english titles on MAL",
   *     example={
   *       "SAO Alternative Gun Gale Online"
   *     },
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
   *   description="The airing status of the anime",
   *   enum={"finished_airing","currently_airing","not_yet_aired"},
   *   example="currently_airing"
   * )
   * @var String
   */
  public $air_status;

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
   *   description="The season and year of the release (null if aired in one day)",
   *   nullable=true,
   *   example="Spring 2018"
   * )
   * @var String
   */
  public $season;

  /**
   * @OA\Property(
   *   description="The premiere date of the anime (null unless aired in one day)",
   *   nullable=true,
   *   example="Feb 18, 2017"
   * )
   * @var String
   */
  public $premiere_date;

  /**
   * @OA\Property(
   *   description="The unparsed air time and day as it appears on MAL (null if aired in one day)",
   *   nullable=true,
   *   example="Sundays at 00:00 (JST)"
   * )
   * @var String
   */
  public $air_time;

  /**
   * @OA\Property(
   *   description="The producers of the anime with IDs being Producer IDs",
   *   example={},
   *   @OA\Items(
   *     ref="#/components/schemas/BriefReferenceModel"
   *   )
   * )
   * @var BriefReference[]
   */
  public $producers = [];

  /**
   * @OA\Property(
   *   description="The licensors of the anime with IDs being Producer IDs",
   *   example={
   *     {
   *       "id": 493,
   *       "name": "Aniplex of America"
   *     }
   *   },
   *   @OA\Items(
   *     ref="#/components/schemas/BriefReferenceModel"
   *   )
   * )
   * @var BriefReference[]
   */
  public $licensors = [];

  /**
   * @OA\Property(
   *   description="The studios of the anime with IDs being Producer IDs",
   *   example={
   *     {
   *       "id": 1127,
   *       "name": "Studio 3Hz"
   *     }
   *   },
   *   @OA\Items(
   *     ref="#/components/schemas/BriefReferenceModel"
   *   )
   * )
   * @var BriefReference[]
   */
  public $studios = [];

  /**
   * @OA\Property(
   *   description="The original source for the anime",
   *   example="Light novel"
   * )
   * @var String
   */
  public $source;

  /**
   * @OA\Property(
   *   description="The genres of the anime on MAL (spaces omitted)",
   *   example={
   *     "action",
   *     "game",
   *     "military",
   *     "sci-fi",
   *     "fantasy"
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
   *   description="The estimated duration of the anime",
   *   @OA\Property(
   *     property="total",
   *     type="integer",
   *     description="The estimated total length of the anime in minutes (null if `episodes` or `duration.per_episode` is null)",
   *     nullable=true,
   *     example=576
   *   ),
   *   @OA\Property(
   *     property="per_episode",
   *     type="integer",
   *     description="The length of one episode of the anime in minutes",
   *     nullable=true,
   *     example=24
   *   )
   * )
   * @var Object
   */
  public $duration = [
    'total' => null,
    'per_episode' => null
  ];

  /**
   * @OA\Property(
   *   description="The classification of the anime on MAL",
   *   required={"name","description"},
   *   @OA\Property(
   *     property="name",
   *     type="string",
   *     description="The symbol name for the classification of the anime",
   *     example="PG-13"
   *   ),
   *   @OA\Property(
   *     property="description",
   *     type="string",
   *     description="The short description for the classification of the anime",
   *     example="Teens 13 or older"
   *   )
   * )
   * @var Object
   */
  public $classification = [
    'name' => null,
    'description' => null
  ];

  /**
   * @OA\Property(
   *   description="The number of people who set a score on MAL",
   *   example=9676
   * )
   * @var Integer
   */
  public $members_scored;

  /**
   * @OA\Property(
   *   description="The number of people who have the anime in their animelist",
   *   example=114512
   * )
   * @var Integer
   */
  public $members_inlist;

  /**
   * @OA\Property(
   *   description="The number of people who have the anime in their favorites",
   *   example=271
   * )
   * @var Integer
   */
  public $members_favorited;

  /**
   * @OA\Property(
   *   description="The backgroud information for the anime on MAL",
   *   nullable=true,
   *   example="<i>Sword Art Online II</i> adapts novels 5 to 8 of Reki Kawahara's light novel series of the same title.<br><br>The first episode was screened at various special events held in the United States, France, Germany, Hong Kong, Taiwan, Korea and Japan before its television premiere."
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
   *     description="The anime that are considered sequels with IDs being Anime IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="prequel",
   *     type="array",
   *     description="The anime that are considered prequels with IDs being Anime IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="alternative_setting",
   *     type="array",
   *     description="The anime that are considered based on an alternative setting with IDs being Anime IDs",
   *     example={
   *       {
   *         "id": 82795,
   *         "name": "Sword Art Online Alternative: Gun Gale Online"
   *       },
   *       {
   *         "id": 37278,
   *         "name": "Sword Art Online Fatal Bullet: The Third Episode - Pilot-ban"
   *       },
   *       {
   *         "id": 37362,
   *         "name": "Swword Art Online Fatal Bullet: The Third Episode"
   *       }
   *     },
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="alternative_version",
   *     type="array",
   *     description="The anime that are considered an alternative version with IDs being Anime IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="side_story",
   *     type="array",
   *     description="The anime that are considered side stories with IDs being Anime IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="parent_story",
   *     type="array",
   *     description="The anime that are considered parent stories with IDs being Anime IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="summary",
   *     type="array",
   *     description="The anime that are considered summarised versions with IDs being Anime IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="full_story",
   *     type="array",
   *     description="The anime that are considered full story versions with IDs being Anime IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="spin_off",
   *     type="array",
   *     description="The anime that are considered spin offs with IDs being Anime IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="adaptation",
   *     type="array",
   *     description="The manga or anime that are considered originals that were adapted upon with IDs being Manga or Anime IDs",
   *     example={
   *       {
   *         "id": 82795,
   *         "name": "Sword Art Online Alternative: Gun Gale Online"
   *       },
   *       {
   *         "id": 21881,
   *         "name": "Sword Art Online II"
   *       },
   *       {
   *         "id": 37278,
   *         "name": "Sword Art Online Fatal Bullet: The Third Episode - Pilot-ban"
   *       },
   *       {
   *         "id": 37362,
   *         "name": "Sword Art Online Fatal Bullet: The Third Episode"
   *       },
   *     },
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="character",
   *     type="array",
   *     description="The anime that are considered sharing same characters with IDs being Anime IDs",
   *     example={},
   *     @OA\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OA\Property(
   *     property="other",
   *     type="array",
   *     description="The anime that are considered related in another way with IDs being Anime IDs",
   *     example={
   *       {
   *         "id": 37278,
   *         "name": "Sword Art Online Fatal Bullet: The Third Episode - Pilot-ban"
   *       },
   *       {
   *         "id": 37362,
   *         "name": "Sword Art Online Fatal Bullet: The Third Episode"
   *       }
   *     },
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

  /**
   * @OA\Schema(
   *   schema="ThemeSong",
   *   title="Theme Song",
   *   type="object",
   *   required={"name","artist","episodes"},
   *   @OA\Property(
   *     property="name",
   *     type="string",
   *     description="The name of the opening theme song",
   *     example="Platinum Jet (プラチナジェット)"
   *   ),
   *   @OA\Property(
   *     property="artist",
   *     type="string",
   *     description="The artist who sang the theme song",
   *     example="Doughnut◎Quintet"
   *   ),
   *   @OA\Property(
   *     property="episodes",
   *     type="array",
   *     description="The episodes where the theme song was used",
   *     @OA\Items(
   *       title="Theme Song Usage Episodes",
   *       type="object",
   *       required={"from","to"},
   *       @OA\Property(
   *         property="from",
   *         type="integer",
   *         description="The episode from which the theme song started being used",
   *         example=15
   *       ),
   *       @OA\Property(
   *         property="to",
   *         type="integer",
   *         description="The episode from which the theme song stopped being used",
   *         example=18
   *       )
   *     )
   *   )
   * )
   */

  /**
   * @OA\Property(
   *   description="The opening and ending theme songs for the anime",
   *   required={"openings","endings"},
   *   @OA\Property(
   *     property="openings",
   *     type="array",
   *     description="The opening theme songs used within the anime",
   *     @OA\Items(
   *       ref="#/components/schemas/ThemeSong"
   *     )
   *   ),
   *   @OA\Property(
   *     property="endings",
   *     type="array",
   *     description="The ending theme songs used within the anime",
   *     @OA\Items(
   *       ref="#/components/schemas/ThemeSong"
   *     )
   *   )
   * )
   * @var Object
   */
  public $theme_songs = [
    'openings' => [],
    'endings' => []
  ];

  /**
   * @OA\Property(
   *   description="Links to the anime on external services",
   *   @OA\Property(
   *     property="anidb",
   *     type="string",
   *     description="The browser URL for the anime on AniDB.net",
   *     example="https://anidb.net/a3320"
   *   ),
   *   @OA\Property(
   *     property="ann",
   *     type="string",
   *     description="The browser URL for the anime on AnimeNewsNetwork.com",
   *     example="https://animenewsnetwork.com/encyclopedia/anime.php?id=5334"
   *   ),
   *   @OA\Property(
   *     property="anilist",
   *     type="string",
   *     description="The browser URL for the anime on AniList.co",
   *     example="https://anilist.co/anime/20812"
   *   ),
   *   @OA\Property(
   *     property="kitsu",
   *     type="string",
   *     description="The browser URL for the anime on Kitsu.io",
   *     example="https://kitsu.io/anime/8698"
   *   )
   * )
   * @var Object
   */
  public $external   = [];
  
}