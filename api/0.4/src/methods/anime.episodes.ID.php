<?php
// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] -------------------HEADERS-------------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

header("access-control-allow-origin: *");
header('Content-Type: text/plain');
require("../SimpleHtmlDOM.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] --------------GETTING THE VALUES-------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

$parts = isset($_GET['id']) ? explode('/',$_GET['id']) : array();
if(!is_numeric($parts[0])) {
  echo "Specified anime id is not a number.";
  exit(400);
}
  $html = @file_get_html("https://myanimelist.net/anime/" . $parts[0] . "/foxinflameisawesome/episode"); // Supress warnings with @.
if(!$html) {
  if(!@file_get_html("https://myanimelist.net/anime/" . $parts[0])) {
    echo "Anime with specified id was not found.";
    exit(404);
  } else {
    echo "Anime doesn't have episodes!";
    exit(404);
  }
}
header('Content-Type: application/json');


//    [+] ============================================== [+]
//    [+] --------------SETTING THE VALUES-------------- [+]
//    [+] ============================================== [+]

$id = $parts[0];
$title = substr($html->find("div#contentWrapper div h1.h1 span", 0)->plaintext, 0, -1);
$table = $html->find("div#contentWrapper div#content div.js-scrollfix-bottom-rel div table table.episode_list", 0);
$episodes = [];
foreach($table->find("tr.episode-list-data") as $episode_tr) {
  $item["number"] = $episode_tr->find("td.episode-number", 0)->plaintext;
  $item["english_title"] = $episode_tr->find("td.episode-title a", 0)->plaintext;
  $japanese_title = $episode_tr->find("td.episode-title .di-ib", 0)->plaintext;
  $filler = false;
  if(strpos($japanese_title, "Filler") !== false) {
    $filler = true;
    $japanese_title = $episode_tr->find("td.episode-title .di-ib", 1)->plaintext;
  }
  $japanese_title_arr = explode("&nbsp;", $japanese_title);
  $item["japanese_title_romaji"] = $japanese_title_arr[0];
  $item["japanese_title"] = substr($japanese_title_arr[1], 1, -2);
  $item["air_date"] = $episode_tr->find("td.episode-aired", 0)->plaintext;
  $converted_filler = ($filler) ? "true" : "false";
  $item["filler"] = $converted_filler;
  array_push($episodes, $item);
}


// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] ------------DISPLAYING THE VALUES------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

echo "{";
echo "\"id\": " . $id . ",";
echo "\"anime_details_url\": \"http://www.foxinflame.tk/anime/api/animeInfo/".$id."\",";
echo "\"episodes\": [";
  foreach($episodes as $key => $value) {
    echo "{";
      echo "\"number\": \"" . $value["number"] . "\",";
      echo "\"english_title\": \"" . $value["english_title"] . "\",";
      echo "\"romaji_title\": \"" . $value["japanese_title_romaji"] . "\",";
      echo "\"japanese_title\": \"" . $value["japanese_title"] . "\",";
      echo "\"air_date\": \"" . $value["air_date"] . "\",";
      echo "\"filler\": " . $value["filler"];
    if((count($episodes) - 1) == $key) {
      echo "}";
    } else {
      echo "},";
    }
  }
echo "]";
echo "}";
?>