<?php
/*

Status: Completed and Tested.
Get latest anime recommendations.

This method is not cached.
Method: GET
        /anime/recommendations
Authentication: None Required.
Parameters:
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
header("Cache-Control: no-cache, must-revalidate");
require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");
require_once(dirname(__FILE__) . "/../absoluteGMT.php");
require_once(dirname(__FILE__) . "/../classes/class.cache.php");
require_once(dirname(__FILE__) . "/../models/model.recommendation.php");
require_once(dirname(__FILE__) . "/../parsers/parser.anime.recommendations.php");

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ---------GETTING THE RECOMMENDATIONS---------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : "1";
  $show = ($page - 1) * 100;
  $page_param = "?show=" . $show;
  
  $url = "https://myanimelist.net/recommendations.php" . $page_param;
  $data = new Data();
  
  if($data->getCache($url)) {
    $content = $data->data;
  } else {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
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
        if($data->getCache("https://myanimelist.net/recommendations.php")) {
          $response = $data->data;
        } else {
          curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/recommendations.php");
          $response = curl_exec($ch);
          curl_close($ch);
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
    
    $data->saveCache($url, $response, 5);
    $content = $response;
  }
    
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ---------------------PARSE-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]  
  
  $recommendations_arr = AnimeRecommendationsParser::parse($content);
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "items" => $recommendations_arr
  );

  echo json_encode($output);
  http_response_code(200);
  
});
?>
