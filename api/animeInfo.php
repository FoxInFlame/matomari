<?php
/*

Shows detailed information about an anime.

Method: GET
Authentication: None Required.
Response: Anime information in JSON.
Parameters:
  - id: [Required] Anime ID.

Created by FoxInFlame.
A Part of the matomari API.

*/

// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] -------------------HEADERS-------------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Cache-Control: no-cache, must-revalidate");
require("../SimpleHtmlDOM.php");


// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] --------------GETTING THE VALUES-------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

$parts = isset($_GET["id"]) ? explode("/",$_GET["id"]) : array();
if(empty($parts)) {
  echo json_encode(array(
    "error" => "The id parameter is not defined."
  ));
  die();
}
if(!is_numeric($parts[0])) {
  echo json_encode(array(
    "error" => "Specified anime id is not a number."
  ));
  die();
}
$html = @file_get_html("https://myanimelist.net/anime/" . $parts[0]);
if(!$html) {
  echo json_encode(array(
    "error" => "Anime with specified id was not found."
  ));
  die();
}

//    [+] ============================================== [+]
//    [+] --------------SETTING THE VALUES-------------- [+]
//    [+] ============================================== [+]

$id = $parts[0];
$title = substr($html->find("div#contentWrapper div h1.h1 span", 0)->plaintext, 0, -1);
$alternativeTitles = $html->find("div#contentWrapper div#content table div.js-scrollfix-bottom .spaceit_pad");
$alternativeTitles_eng = null;
$alternativeTitles_jap = null;
$alternativeTitles_syn = null;
foreach($alternativeTitles as $value) {
  if(strpos($value->plaintext, "English:") !== false) {
    $alternativeTitles_eng = trim(substr($value->plaintext, 15), " ");
  } else if(strpos($value->plaintext, "Japanese:") !== false) {
    $alternativeTitles_jap = trim(substr($value->plaintext, 16), " ");
  } else if(strpos($value->plaintext, "Synonyms:") !== false) {
    $alternativeTitles_syn = trim(substr($value->plaintext, 16), " ");
  }
}
unset($value);
$rank = substr($html->find("div#contentWrapper div#content div.anime-detail-header-stats span.ranked strong", 0)->plaintext, 1);
$popularity_rank = substr($html->find("div#contentWrapper div#content div.anime-detail-header-stats span.popularity strong", 0)->plaintext, 1);
$image_url = $html->find("div#contentWrapper div#content table div a img.ac", 0)->src;
$mal_link = trim($html->find("div#contentWrapper div#content table div.js-scrollfix-bottom-rel div#horiznav_nav ul li a", 0)->href, " ");
$information = $html->find("div#contentWrapper div#content div.js-scrollfix-bottom div");
foreach($information as $value) {
  if(strpos($value->plaintext, "Type:") !== false) {
    $type = str_replace("</a>", "", trim(substr($value->plaintext, 9), " "));
    // MAL has an ending tag without a starting tag bug so remove that
    if($type == "Unknown") {
      $type = null;
    }
  }
  if(strpos($value->plaintext, "Episodes:") !== false) {
    $episodes = trim(substr($value->plaintext, 13), " ");
    if($episodes == "Unknown") {
      $episodes = null;
    }
  }
  if(strpos($value->plaintext, "Duration:") !== false) {
    if(strpos($value->plaintext, "hr.") !== false) {
      preg_match("/\d+(?= hr.)/", $value->plaintext, $matches);
      $hour = trim($matches[0], " ");
      $minutes = intval($hour) * 60;
    }
    if(strpos($value->plaintext, "min.") !== false) {
      preg_match("/\d+(?= min.)/", $value->plaintext, $matches);
      $minutes = trim($matches[0], " ");
      if(isset($hour)) {
        $minutes = intval($minutes) + (intval($hour) * 60);
      }
    }
  }
  if(strpos($value->plaintext, "Score:") !== false) {
    if(strpos($value->plaintext, "users") !== false) {
      preg_match("/\d(,?\d?)+(?=  users)/", $value->plaintext, $matches);
      $score_count = str_replace(",", "", $matches[0]);
    } else {
      $score_count = null;
    }
  }
  if(strpos($value->plaintext, "Members:") !== false) {
    $members_count = str_replace(",", "", trim(substr($value->plaintext, 12), " "));
  }
  if(strpos($value->plaintext, "Favorites:") !== false) {
    $favourites_count = str_replace(",", "", trim(substr($value->plaintext, 14), " "));
  }
  if(strpos($value->plaintext, "Genres:") !== false) {
    $genres_str = trim(substr($value->plaintext, 11), " ");
    $genres_arr = explode(", ", $genres_str);
    $genres_arr = array_map("trim", $genres_arr);
    if($genres_arr[0] == "No genres have been added yet.") {
      $genres_arr = [];
    }
  }
  if(strpos($value->plaintext, "Source:") !== false) {
    $source = trim(substr($value->plaintext, 11), " ");
  }
  if(strpos($value->plaintext, "Producers:") !== false) {
    $producers_str = trim(substr($value->plaintext, 16), " ");
    $producers_arr = explode(", ", $producers_str);
    $producers_arr = array_map("trim", $producers_arr);
    if($producers_arr[0] == "None found") {
      $producers_arr = [];
    }
  }
  if(strpos($value->plaintext, "Studios:") !== false) {
    $studios_str = trim(substr($value->plaintext, 14), " ");
    $studios_arr = explode(", ", $studios_str);
    $studios_arr = array_map("trim", $studios_arr);
    if($studios_arr[0] == "None found") {
      $studios_arr = [];
    }
  }
  if(strpos($value->plaintext, "Licensors:") !== false) {
    $licensors_str = trim(substr($value->plaintext, 16), " ");
    $licensors_arr = explode(", ", $licensors_str);
    $licensors_arr = array_map("trim", $licensors_arr);
    if($licensors_arr[0] == "None found") {
      $licensors_arr = [];
    }
  }
}
unset($value);


//    [+] ============================================== [+]
//    [+] --------------SETTING THE EMPTY--------------- [+]
//    [+] ============================================== [+]

if(!isset($type)) {
  $type = null;
}
if(!isset($episodes)) {
  $episodes = null;
}
if(!isset($minutes)) {
  $minutes = null;
}
if(!isset($score_count)) {
  $score_count = null;
}
if(!isset($genres_arr)) {
  $genres_arr = [];
}
if($episodes != null) {
  $total_duration = intval($minutes) * intval($episodes);
} else {
  $total_duration = null;
}
$score = trim($html->find("div#contentWrapper div#content div.anime-detail-header-stats .score", 0)->plaintext, " ");
if($rank == "/A") {
  $rank = null;
}
if($score == "N/A") {
  $score = null;
}
$synopsis = htmlspecialchars_decode(html_entity_decode(trim($html->find("div#contentWrapper div#content div.js-scrollfix-bottom-rel table td span[itemprop=description]", 0)->innertext, " "), 0, "UTF-8"));


// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] --------------------OUTPUT-------------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

$output = array(
  "id" => $id,
  "title" => $title,
  "other_titles" => array(
    "english" => $alternativeTitles_eng,
    "japanese" => $alternativeTitles_jap,
    "synonyms" => $alternativeTitles_syn
  ),
  "rank" => $rank,
  "popularity" => $popularity_rank,
  "image_url" => $image_url,
  "source" => $source,
  "url" => $mal_link,
  "type" => $type,
  "episodes" => $episodes,
  "duration" => $minutes,
  "total_duration" => $total_duration,
  "score" => $score,
  "score_count" => $score_count,
  "members_count" => $members_count,
  "favourites_count" => $favourites_count,
  "genres" => $genres_arr,
  "producers" => $producers_arr,
  "studios" => $studios_arr,
  "licensors" => $licensors_arr,
  "synopsis" => $synopsis
);

// JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
echo json_encode($output, JSON_NUMERIC_CHECK);
?>