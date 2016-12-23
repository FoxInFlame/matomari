<?php
/*

Shows detailed information about a MAL club.

Method: GET
Authentication: None Required.
Response: Club information in JSON.
Parameters:
  - clubid: [Required] MAL Club ID.

Created by FoxInFlame.
A Part of the matomari API.

*/

// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] -------------------HEADERS-------------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

header("Access-Control-Allow-Origin: *");
// Content type is set later.
header("Cache-Control: no-cache, must-revalidate");
require(dirname(__FILE__) . "/../SimpleHtmlDOM.php");


// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] --------------GETTING THE VALUES-------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

$parts = isset($_GET["clubid"]) ? explode("/",$_GET["clubid"]) : array();
if(empty($parts)) {
  echo json_encode(array(
    "error" => "The clubid parameter is not defined."
  ));
  die();
}
$html = @file_get_html("https://myanimelist.net/clubs.php?cid=" . $parts[0]);
if(!$html) {
  echo json_encode(array(
    "error" => "Club was not found or MAL is offline."
  ));
  die();
}


//    [+] ============================================== [+]
//    [+] --------------SETTING THE VALUES-------------- [+]
//    [+] ============================================== [+]

$clubid = $parts[0];
$mal_link = "https://myanimelist.net/clubs.php?cid=" . $clubid;
$html_leftside = $html->find("div#contentWrapper #content table", 0)->find("tr", 0)->find("td", 0);
$html_rightside = $html->find("div#contentWrapper #content table", 0)->find("tr", 0)->find("td[width=300]", 0);

$club_title = $html->find("div#contentWrapper div h1", 0)->innertext;
$image_url = $html_rightside->find("div img", 0)->src;
$clubstats = $html_rightside->find("div.spaceit_pad");
$member_count = null;
$picture_count = null;
$category = null;
$creation_date = null;
foreach($clubstats as $value) {
  if(strpos($value->plaintext, "Members") !== false) {
    $member_count = trim(substr($value->plaintext, 9), " ");
  } else if(strpos($value->plaintext, "Pictures") !== false) {
    $picture_count = trim(substr($value->plaintext, 9));
  } else if(strpos($value->plaintext, "Category") !== false) {
    $category = trim(substr($value->plaintext, 9));
  } else if(strpos($value->plaintext, "Created") !== false) {
    $creation_date = trim(substr($value->plaintext, 8));
  }
}
unset($value);
$otherInfo = $html_rightside->find("div.borderClass");
$officers_normal_arr = array();
$officers_creator = null;
$officers_admins_arr = array();
$relation_anime_arr = array();
$relation_manga_arr = array();
$relation_character_arr = array();
foreach($otherInfo as $value) {
  if(strpos($value->innertext, "/profile/") !== false) {
    if(strpos($value->innertext, "(Creator)") !== false) {
      $officers_creator = trim(substr($value->plaintext, 0, -9));
      continue;
    } else if(strpos($value->innertext, "(Admin)") !== false) {
      array_push($officers_admins_arr, trim(substr($value->plaintext, 0, -8)));
      continue;
    } else {
      array_push($officers_normal_arr, trim($value->plaintext));
      continue;
    }
  }
  
  if(strpos($value->innertext, "anime.php") !== false) {
    array_push($relation_anime_arr, "string_".substr(trim($value->find("a", 0)->href), 13));
    continue;
  }
  if(strpos($value->innertext, "manga.php") !== false) {
    array_push($relation_manga_arr, "string_".substr(trim($value->find("a", 0)->href), 13));
    continue;
  }
  if(strpos($value->innertext, "character.php") !== false) {
    array_push($relation_character_arr, "string_".substr(trim($value->find("a", 0)->href), 17));
    continue;
  }
}
unset($value);

$information = htmlspecialchars_decode(html_entity_decode(trim($html_leftside->find("div.clearfix", 0)->innertext,  " ")));

// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] --------------------OUTPUT-------------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

$output = array(
  "id" => $clubid,
  "title" => $club_title,
  "url" => $mal_link,
  "information" => $information,
  "member_count" => $member_count,
  "picture_count" => $picture_count,
  "category" => $category,
  "creation_date" => $creation_date,
  "creator" => $officers_creator,
  "officers" => array(
    "normal" => $officers_normal_arr,
    "creator" => $officers_creator,
    "admins" => $officers_admins_arr
  ),
  "relationships" => array(
    "anime" => $relation_anime_arr,
    "manga" => $relation_manga_arr,
    "characters" => $relation_character_arr
  )
);

// Remove string_ after parse
// JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
echo str_replace("string_", "", json_encode($output, JSON_NUMERIC_CHECK));
?>