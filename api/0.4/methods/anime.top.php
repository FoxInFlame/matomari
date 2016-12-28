<?php
/*

Get top ranked anime.

Method: GET
        /anime/top
Authentication: None Required.
Parameters:
  - sort: [Optional] Set to change the ranking method. "all", "airing", "tv", "ovas", "popularity", etc. (defaults to all)
  - page: [Optional] Page number. If page doesn't exist, becomes 1. (defaults to 1)
  - detail: [Optional] Set the parameter to anything to show more details about each anime. Request will take about 2 minutes in total, so use this only if super-necessary.

Created by FoxInFlame.
A Part of the matomari API.

*/

// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] -------------------HEADERS-------------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

ini_set("display_errors", true);
ini_set("display_startup_errors", true);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Cache-Control: no-cache, must-revalidate");
require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ------------GETTING THE TOP ANIME------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $showDetailed = isset($_GET['detail']);
  $sort = isset($_GET['sort']) ? $_GET['sort'] : "all";
  switch(strtolower($sort)) {
    case "all":
      $sort_param = "";
      break;
    case "airing":
      $sort_param = "?type=airing";
      break;
    case "upcoming":
      $sort_param = "?type=upcoming";
      break;
    case "tv":
      $sort_param = "?type=tv";
      break;
    case "movies":
      $sort_param = "?type=movie";
      break;
    case "ovas":
      $sort_param = "?type=ova";
      break;
    case "specials":
      $sort_param = "?type=special";
      break;
    case "popularity":
      $sort_param = "?type=bypopularity";
      break;
    case "favorites":
      $sort_param = "?type=favorite";
      break;
    default:
      $sort_param = "";
      break;
  }
  
  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : "1";
  $show = ($page - 1) * 50;
  $page_param = $sort_param == "" ? "?limit=" . $show : "&limit=" . $show;
    
  $html = @file_get_html("https://myanimelist.net/topanime.php" . $sort_param . $page_param);
  if(!$html) {
    if($page != 1) {
      $html = @file_get_html("https://myanimelist.net/topanime.php" . $sort_param);
      if(!$html) {
        echo json_encode(array(
          "error" => "MAL is offline, or their code changed."
        ));
        return;
      }
    } else {
      echo json_encode(array(
        "error" => "MAL is offline, or their code changed."
      ));
      return;
    }
  }
  
  $top_ranking_table = $html->find(".top-ranking-table", 0);
  $ranking_items = $top_ranking_table->find("tr.ranking-list");
  $anime_arr = array();
  foreach($ranking_items as $anime) {
    $ranking_rank = $anime->find("td.rank span", 0)->innertext;
    $id = substr($anime->find("td.title .hoverinfo_trigger", 0)->id, 5);
    if($showDetailed) {
      ob_start();
      $_GET['id'] = $id;
      include(dirname(__FILE__) . "/anime.info.ID.php");
      $response_json = ob_get_clean();
      $response_array = json_decode($response_json, true);
      $response_array = array(
        "ranking_rank" => $ranking_rank
      ) + $response_array;
      array_push($anime_arr, $response_array);
      continue;
    }
    $title = $anime->find("td.title .detail div.di-ib a.hoverinfo_trigger", 0)->innertext;
    $information = $anime->find("td.title .detail div.information", 0)->innertext;
    $information_parts = explode("<br>", $information);
    $type = explode(" (", trim($information_parts[0]))[0];
    $episodes = substr(explode(" (", trim($information_parts[0]))[1], 0, -5);
    $members_count = str_replace(",", "", substr(trim($information_parts[2]), 0, -8));
    $score = $anime->find("td.score span.text", 0)->innertext;
    array_push($anime_arr, array(
      "ranking_rank" => $rating_rank,
      "id" => $id,
      "title" => $title,
      "type" => $type,
      "episodes" => $episodes,
      "score" => $score,
      "members_count" => $members_count
    ));
  }
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = $anime_arr;
  
  // Remove string_ after parse
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo str_replace("string_", "", json_encode($output, JSON_NUMERIC_CHECK));
});
?>