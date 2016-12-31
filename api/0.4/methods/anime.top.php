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
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/topanime.php" . $sort_param . $page_param);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response_string = curl_exec($ch);
  if(!$response_string) {
    if($page != 1) {
      curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/topanime.php" . $sort_param);
      $response_string = curl_exec($ch);
      curl_close($ch);
      if(!$response_string) {
        echo json_encode(array(
          "error" => "MAL is offline, or their code changed."
        ));
        http_response_code(404);
        return;
      }
    } else {
      curl_close($ch);
      echo json_encode(array(
        "error" => "MAL is offline, or their code changed."
      ));
      http_response_code(404);
      return;
    }
  }
  curl_close($ch);
  $html = str_get_html($response_string);
  
  $top_ranking_table = $html->find(".top-ranking-table", 0);
  $ranking_items = $top_ranking_table->find("tr.ranking-list");
  $anime_arr = array();
  foreach($ranking_items as $anime) {
    $ranking_rank = $anime->find("td.rank span", 0)->innertext;
    $id = substr($anime->find("td.title .hoverinfo_trigger", 0)->id, 5);
    if($showDetailed) {
      $info = file_get_html("https://myanimelist.net/includes/ajax.inc.php?t=64&id=" . $id);
      $parts = explode("\n", trim($info->plaintext));
      $titleAndYear = trim($info->find("a.hovertitle", 0)->innertext);
      $title = trim(substr($titleAndYear, 0, -7));
      $release_year = substr(substr($titleAndYear, -5), 0, -1);
      $synopsis_snippet = substr(trim($info->find("div", 0)->plaintext), 0, -9);
      $reverse = array_reverse($parts);
      $members = str_replace(",", "", trim(substr($reverse[0], 12)));
      $popularity = substr(trim($reverse[1]), 14);
      $score = trim(substr(explode("(scored by ", trim($reverse[3]))[0], 6));
      $score_count = str_replace(",", "", trim(substr(explode("(scored by ", trim($reverse[3]))[1], 0, 7)));
      $episodes = substr(trim($reverse[4]), 10);
      $type = substr(trim($reverse[5]), 7);
      $status = substr(trim($reverse[6]), 9);
      $genres = explode(", ", trim(explode("Genres: ", $reverse[7])[1]));
      
      $response_array = array(
        "ranking_rank" => $ranking_rank,
        "id" => $id,
        "title" => $title,
        "type" => $type,
        "episodes" => $episodes,
        "score" => $score,
        "score_count" => $score_count,
        "member_count" => $members,
        "popularity" => $popularity,
        "genres" => $genres,
        "status" => $status,
        "release_year" => $release_year,
        "synopsis_snippet" => $synopsis_snippet
      );
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
      "ranking_rank" => $ranking_rank,
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
  
  $output = array(
    "top" => $anime_arr
  );
  
  // Remove string_ after parse
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo str_replace("string_", "", json_encode($output, JSON_NUMERIC_CHECK));
  http_response_code(200);
  
});
?>