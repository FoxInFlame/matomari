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
 *     "theme_songs"
 *   }
 * )
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class AnimeInfoModel extends Model
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
   *   title="Rank",
   *   description="The overall anime ranking on MAL",
   *   nullable=true,
   *   example=2437
   * )
   * @var Integer
   */
  public $rank;

  /**
   * @OAS\Property(
   *   title="Popularity",
   *   description="The anime popularity ranking on MAL",
   *   nullable=true,
   *   example=743
   * )
   * @var Integer
   */
  public $popularity;

  /**
   * @OAS\Property(
   *   title="Synopsis",
   *   description="The full synopsis for the anime, formatted in HTML",
   *   nullable=true,
   *   example="Karen Kohiruimaki always felt out of place in the real world. Due to her extreme height, she found it hard to make friends with other girls her age. Everything changes when she&#039;s introduced to VR and Gun Gale Online. In GGO, Karen is free to play the cute, chibi avatar of her dreams! Can Karen find friendship in this bullet-ridden MMO...?<br /> <br /> (Source: Yen Press)"
   * )
   * @var String
   */
  public $synopsis;

  /**
   * @OAS\Property(
   *   title="Other Titles",
   *   description="The alternative titles for the anime registered on MAL",
   *   required={"english","japanese","synonyms"},
   *   @OAS\Property(
   *     property="english",
   *     type="array",
   *     title="English Titles",
   *     description="The alternative english titles on MAL",
   *     example={},
   *     @OAS\Items(
   *       type="string"
   *     )
   *   ),
   *   @OAS\Property(
   *     property="japanese",
   *     type="array",
   *     title="Japanese Titles",
   *     description="The japanese titles on MAL",
   *     example={
   *       "ソードアート・オンライン オルタナティブ ガンゲイル・オンライン"
   *     },
   *     @OAS\Items(
   *       type="string"
   *     )
   *   ),
   *   @OAS\Property(
   *     property="synonyms",
   *     type="array",
   *     title="Synonymous Titles",
   *     description="The synonymous english titles on MAL",
   *     example={
   *       "SAO Alternative Gun Gale Online"
   *     },
   *     @OAS\Items(
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
   *   title="Air Status",
   *   description="The airing status of the anime",
   *   enum={"finished_airing","currently_airing","not_yet_aired"},
   *   example="currently_airing"
   * )
   * @var String
   */
  public $air_status;

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
   *   title="Season",
   *   description="The season and year of the release (null if aired in one day)",
   *   nullable=true,
   *   example="Spring 2018"
   * )
   * @var String
   */
  public $season;

  /**
   * @OAS\Property(
   *   title="Premiere Date",
   *   description="The premiere date of the anime (null unless aired in one day)",
   *   nullable=true,
   *   example="Feb 18, 2017"
   * )
   * @var String
   */
  public $premiere_date;

  /**
   * @OAS\Property(
   *   title="Air Time",
   *   description="The unparsed air time and day as it appears on MAL (null if aired in one day)",
   *   nullable=true,
   *   example="Sundays at 00:00 (JST)"
   * )
   * @var String
   */
  public $air_time;

  /**
   * @OAS\Property(
   *   title="Producers",
   *   description="The producers of the anime with IDs being Producer IDs",
   *   example={},
   *   @OAS\Items(
   *     ref="#/components/schemas/BriefReferenceModel"
   *   )
   * )
   * @var BriefReference[]
   */
  public $producers = [];

  /**
   * @OAS\Property(
   *   title="Licensors",
   *   description="The licensors of the anime with IDs being Producer IDs",
   *   example={
   *     {
   *       "id": 493,
   *       "name": "Aniplex of America"
   *     }
   *   },
   *   @OAS\Items(
   *     ref="#/components/schemas/BriefReferenceModel"
   *   )
   * )
   * @var BriefReference[]
   */
  public $licensors = [];

  /**
   * @OAS\Property(
   *   title="Studios",
   *   description="The studios of the anime with IDs being Producer IDs",
   *   example={
   *     {
   *       "id": 1127,
   *       "name": "Studio 3Hz"
   *     }
   *   },
   *   @OAS\Items(
   *     ref="#/components/schemas/BriefReferenceModel"
   *   )
   * )
   * @var BriefReference[]
   */
  public $studios = [];

  /**
   * @OAS\Property(
   *   title="Source",
   *   description="The original source for the anime",
   *   example="Light novel"
   * )
   * @var String
   */
  public $source;

  /**
   * @OAS\Property(
   *   title="Genres",
   *   description="The genres of the anime on MAL (spaces omitted)",
   *   example={
   *     "action",
   *     "game",
   *     "military",
   *     "sci-fi",
   *     "fantasy"
   *   },
   *   @OAS\Items(
   *     type="string"
   *   )
   * )
   * @var String[]
   */
  public $genres = [];

  /**
   * @OAS\Property(
   *   title="Duration",
   *   description="The estimated duration of the anime",
   *   @OAS\Property(
   *     property="total",
   *     type="integer",
   *     title="Total Duration",
   *     description="The estimated total length of the anime in minutes (null if episodes or per_episode is null)",
   *     nullable=true,
   *     example=576
   *   ),
   *   @OAS\Property(
   *     property="per_episode",
   *     type="integer",
   *     title="Per Episode Duration",
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
   * @OAS\Property(
   *   title="Classification",
   *   description="The classification of the anime on MAL",
   *   required={"name","description"},
   *   @OAS\Property(
   *     property="name",
   *     type="string",
   *     title="Classification Name",
   *     description="The symbol name for the classification of the anime",
   *     example="PG-13"
   *   ),
   *   @OAS\Property(
   *     property="description",
   *     type="string",
   *     title="Classification Description",
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
   * @OAS\Property(
   *   title="Members Scored",
   *   description="The number of people who set a score on MAL",
   *   example=9676
   * )
   * @var Integer
   */
  public $members_scored;

  /**
   * @OAS\Property(
   *   title="Members In List",
   *   description="The number of people who have the anime in their animelist",
   *   example=114512
   * )
   * @var Integer
   */
  public $members_inlist;

  /**
   * @OAS\Property(
   *   title="Members In Favorites",
   *   description="The number of people who have the anime in their favorites",
   *   example=271
   * )
   * @var Integer
   */
  public $members_favorited;

  /**
   * @OAS\Property(
   *   title="Background",
   *   description="The backgroud information for the anime on MAL",
   *   nullable=true,
   *   example="<i>Sword Art Online II</i> adapts novels 5 to 8 of Reki Kawahara's light novel series of the same title.<br><br>The first episode was screened at various special events held in the United States, France, Germany, Hong Kong, Taiwan, Korea and Japan before its television premiere."
   * )
   * @var String
   */
  public $background;

  /**
   * @OAS\Property(
   *   title="Relations",
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
   *   @OAS\Property(
   *     property="sequel",
   *     type="array",
   *     title="Sequels",
   *     description="The anime that are considered sequels with IDs being Anime IDs",
   *     example={},
   *     @OAS\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OAS\Property(
   *     property="prequel",
   *     type="array",
   *     title="Prequels",
   *     description="The anime that are considered prequels with IDs being Anime IDs",
   *     example={},
   *     @OAS\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OAS\Property(
   *     property="alternative_setting",
   *     type="array",
   *     title="Alternative Settings",
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
   *     @OAS\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OAS\Property(
   *     property="alternative_version",
   *     type="array",
   *     title="Alternative Versions",
   *     description="The anime that are considered an alternative version with IDs being Anime IDs",
   *     example={},
   *     @OAS\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OAS\Property(
   *     property="side_story",
   *     type="array",
   *     title="Side Stories",
   *     description="The anime that are considered side stories with IDs being Anime IDs",
   *     example={},
   *     @OAS\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OAS\Property(
   *     property="parent_story",
   *     type="array",
   *     title="Parent Stories",
   *     description="The anime that are considered parent stories with IDs being Anime IDs",
   *     example={},
   *     @OAS\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OAS\Property(
   *     property="summary",
   *     type="array",
   *     title="Summaries",
   *     description="The anime that are considered summarised versions with IDs being Anime IDs",
   *     example={},
   *     @OAS\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OAS\Property(
   *     property="full_story",
   *     type="array",
   *     title="Full Stories",
   *     description="The anime that are considered full story versions with IDs being Anime IDs",
   *     example={},
   *     @OAS\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OAS\Property(
   *     property="spin_off",
   *     type="array",
   *     title="Spin Offs",
   *     description="The anime that are considered spin offs with IDs being Anime IDs",
   *     example={},
   *     @OAS\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OAS\Property(
   *     property="adaptation",
   *     type="array",
   *     title="Adaptations",
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
   *     @OAS\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OAS\Property(
   *     property="character",
   *     type="array",
   *     title="Characters",
   *     description="The anime that are considered sharing same characters with IDs being Anime IDs",
   *     example={},
   *     @OAS\Items(
   *       ref="#/components/schemas/BriefReferenceModel"
   *     )
   *   ),
   *   @OAS\Property(
   *     property="other",
   *     type="array",
   *     title="Others",
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
   *     @OAS\Items(
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
   * @OAS\Schema(
   *   schema="ThemeSong",
   *   title="Theme Song",
   *   required={"name","artist","episodes"},
   *   @OAS\Property(
   *     property="name",
   *     type="string",
   *     description="The name of the opening theme song",
   *     example="Platinum Jet (プラチナジェット)"
   *   ),
   *   @OAS\Property(
   *     property="artist",
   *     type="string",
   *     description="The artist who sang the theme song",
   *     example="Doughnut◎Quintet"
   *   ),
   *   @OAS\Property(
   *     property="episodes",
   *     type="array",
   *     description="The episodes where the theme song was used",
   *     @OAS\Items(
   *       title="Theme Song Usage Episodes",
   *       required={"from","to"},
   *       @OAS\Property(
   *         property="from",
   *         type="integer",
   *         description="The episode from which the theme song started being used",
   *         example=15
   *       ),
   *       @OAS\Property(
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
   * @OAS\Property(
   *   title="Theme Songs",
   *   description="The opening and ending theme songs for the anime",
   *   required={"openings","endings"},
   *   @OAS\Property(
   *     property="openings",
   *     type="array",
   *     title="Opening Themes",
   *     description="The opening theme songs used within the anime",
   *     @OAS\Items(
   *       ref="#/components/schemas/ThemeSong"
   *     )
   *   ),
   *   @OAS\Property(
   *     property="endings",
   *     type="array",
   *     title="Ending Themes",
   *     description="The ending theme songs used within the anime",
   *     @OAS\Items(
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
  
}