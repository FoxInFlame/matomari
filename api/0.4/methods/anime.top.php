<?php
/*

Get top ranked anime.

This method is cached. Set the nocache parameter to true to use a fresh version (slower).
Method: GET
        /anime/top
Authentication: None Required.
Parameters:
  - sort: [Optional] Set to change the ranking method. "all", "airing", "tv", "ova", "bypopularity", etc. (defaults to all)
  - page: [Optional] Page number. If page doesn't exist, becomes 1. (defaults to 1)
  - details: [Optional] Show details. Will become really slow. (defaults to false)

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
require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ------------GETTING THE TOP ANIME------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $showDetailed = isset($_GET['details']) && strtolower($_GET['details']) == "true" ? true : false;
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
    case "movie":
      $sort_param = "?type=movie";
      break;
    case "ova":
      $sort_param = "?type=ova";
      break;
    case "special":
      $sort_param = "?type=special";
      break;
    case "bypopularity":
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
  
  $url = "https://myanimelist.net/topanime.php" . $sort_param . $page_param;
  $data = new Data(); // Initialise cache class
  
  if($data->getCache($url)) {
    $html = str_get_html($data->data);
  } else {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/topanime.php" . $sort_param . $page_param);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if(!$response) {
      if($page != 1) { // If page isn't one, try one
        curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/topanime.php" . $sort_param);
        $response = curl_exec($ch);
        curl_close($ch);
        if(!$response) {
          echo json_encode(array(
            "message" => "MAL is offline."
          ));
          http_response_code(404);
          return;
        }
      } else {
        curl_close($ch);
        echo json_encode(array(
          "message" => "MAL is offline."
        ));
        http_response_code(404);
        return;
      }
    }
    curl_close($ch);
    
    $data->saveCache($url, $response);
    $html = str_get_html($response);
  }
  
  if(!is_object($html)) {
    echo json_encode(array(
      "message" => "The code for MAL is not valid HTML markup.",
    ));
    http_response_code(500);
    return;
  }
  
  $top_ranking_table = $html->find(".top-ranking-table", 0);
  $ranking_items = $top_ranking_table->find("tr.ranking-list");
  $anime_arr = array();
  foreach($ranking_items as $anime) {
    $ranking_rank = $anime->find("td.rank span", 0)->innertext;
    $id = substr($anime->find("td.title .hoverinfo_trigger", 0)->id, 5);
    if($showDetailed) {
      $detail_data = new Data(); // Initialise cache class, again
      $url = "https://myanimelist.net/includes/ajax.inc.php?t=64&id=" . $id;
      
      if($detail_data->getCache($url)) {
        $info = str_get_html($detail_data->data);
      } else {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if(!$response) {
          continue;
        }
        curl_close($ch);
        $data->saveCache($url, $response);
        $info = str_get_html($response);
      }

      $parts = explode("\n", trim($info->plaintext));
      $titleAndYear = trim($info->find("a.hovertitle", 0)->innertext);
      $title = trim(substr($titleAndYear, 0, -7));
      $release_year = substr(substr($titleAndYear, -5), 0, -1);
      $synopsis_snippet = trim(str_replace("read more", "", $info->find("div", 0)->plaintext));
      $reverse = array_reverse($parts);
      $members = str_replace(",", "", trim(substr($reverse[0], 12)));
      $popularity = substr(trim($reverse[1]), 14);
      $score = trim(substr(explode("(scored by ", trim($reverse[3]))[0], 6));
      $score_count = str_replace(",", "", trim(substr(explode("(scored by ", trim($reverse[3]))[1], 0, -7)));
      $episodes = substr(trim($reverse[4]), 10);
      $type = substr(trim($reverse[5]), 7);
      $status = substr(trim($reverse[6]), 9);
      $genres = explode(", ", trim(explode("Genres: ", $reverse[7])[1]));
      if($episodes == " Unknown") $episodes = null;
      $response_array = array(
        "ranking_rank" => $ranking_rank,
        "id" => $id,
        "title" => $title,
        "type" => $type,
        "episodes" => $episodes,
        "score" => $score,
        "score_count" => $score_count,
        "members_count" => $members,
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
    "items" => $anime_arr
  );
  
  // Remove string_ after parse
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo str_replace("string_", "", json_encode($output, JSON_NUMERIC_CHECK));
  http_response_code(200);
  
});
?>
