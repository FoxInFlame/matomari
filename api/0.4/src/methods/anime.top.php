<?php
/*

Status: Completed and Tested.
Get top ranked anime.

This method is cached for a day. Set the nocache parameter to true to use a fresh version (slower).
Method: GET
        /anime/top
Authentication: None Required.
Parameters:
  - sort: [Optional] Set to change the ranking method. "all", "airing", "tv", "ova", "bypopularity", etc. (defaults to all)
  - page: [Optional] Page number. If page doesn't exist, becomes 1. (defaults to 1)

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
header("Cache-Control: max-age=86400, public"); // 1 day
require_once(dirname(__FILE__) . "/../classes/class.cache.php");
require_once(dirname(__FILE__) . "/../parsers/parser.anime.top.php");

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ------------GETTING THE TOP ANIME------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
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
    case "byfavorites":
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
  
  if($data->getCache($url, 1440)) { // 24 x 60 = One Day
    $content = $data->data;
  } else {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/topanime.php" . $sort_param . $page_param);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if(!$response) {
      echo json_encode(array(
        "message" => "MAL is offline."
      ));
      http_response_code(404);
      return;
    }
    if(curl_getinfo($ch, CURLINFO_HTTP_CODE) === 404) {
      if($page != 1) { // If page isn't one, try one
        curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/topanime.php" . $sort_param);
        $response = curl_exec($ch);
        curl_close($ch);
      } else {
        curl_close($ch);
        echo json_encode(array(
          "message" => "MAL is offline."
        ));
        http_response_code(404);
        return;
      }
    }
    
    $data->saveCache($url, $response);
    $content = $response;
  }

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ---------------------PARSE-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]

  $anime = AnimeTopParser::parse($content);

  $page = (($anime[0]["rank"] - 1) / 50) + 1;
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "page" => (int)$page,
    "items" => $anime
  );
  echo json_encode($output);
  http_response_code(200);
  
});
?>