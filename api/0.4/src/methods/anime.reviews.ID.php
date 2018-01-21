<?php
/*

Status: Completed and Tested.
Shows reviews on an anime.

This method is cached for a week. Set the nocache parameter to true to use a fresh version (slower).
Method: GET
        /anime/reviews/:id
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
header("Cache-Control: max-age=86400, public"); // 1 day
require_once(dirname(__FILE__) . "/../parsers/parser.anime.reviews.ID.php");
require_once(dirname(__FILE__) . "/../classes/class.cache.php");

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ---------------------CURL--------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  if(!isset($_GET['id'])) {
    echo json_encode(array(
      "message" => "The id parameter is not defined."
    ));
    http_response_code(400);
    return;
  }
  if(!is_numeric($_GET['id'])) {
    echo json_encode(array(
      "message" => "Specified anime id is not a number."
    ));
    http_response_code(400);
    return;
  }

  $sort = isset($_GET['sort']) ? $_GET['sort'] : "helpful_weighted";
  switch(strtolower($sort)) {
    case "helpful_weighted":
      $sort_cookie = "helpful";
      break;
    case "helpful":
      $sort_cookie = "helpful_all";
      break;
    case "recent":
      $sort_cookie = "recent";
      break;
    default:
      $sort_cookie = "helpful";
      break;
  }

  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : "1";
  
  // In order to identify different sorts as different URLs, we will be adding a parameter to the URL
  // Of course, it doesn't do anything - the real sort is done with cookies.

  $url = "https://myanimelist.net/anime/" . $_GET['id'] . "/FoxInFlameIsAwesome/reviews?p=" . $page . "&sort=" . $sort_cookie;

  $data = new Data();
  
  if($data->getCache($url, 1440)) {
    $content = $data->data;
  } else {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, "reviews_sort=" . $sort_cookie);
    $response = curl_exec($ch);
    if(!$response) {
       echo json_encode(array(
        "message" => "MAL is offline."
      ));
      http_response_code(404);
      return;
    }
    if(curl_getinfo($ch, CURLINFO_HTTP_CODE) === 404) {
      echo json_encode(array(
        "message" => "Anime with specified id could not be found."
      ));
      http_response_code(404);
      return;
    }
    if(!preg_match('/<body [a-zA-Z0-9!@#$&()\\-`.+,\/\"= ]*class="[a-zA-Z0-9!@#$&()\\-`.+,\/\"= ]*page-common/g', $response)) {
      echo json_encode(array(
        "message" => "MAL is under maintenance."
      ));
      http_response_code(503);
      return;
    }
    curl_close($ch);
    
    $data->saveCache($url, $response);
    $content = $response;
  }
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ---------------------PARSE-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]

  $reviews_arr = AnimeReviewsIDParser::parse($content);

  if(!$reviews_arr) return;

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  echo json_encode(array(
    "items" => $reviews_arr
  ));
  http_response_code(200);
  
});
?>