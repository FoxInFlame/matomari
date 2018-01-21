<?php
/*

Status: Completed and Tested.
Shows detailed information about an anime.

This method is cached for a week. Set the nocache parameter to true to use a fresh version (slower).
Method: GET
        /anime/info/:id
Authentication: None Required.
Parameters:
  - None.

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
header("Cache-Control: max-age=604800, public"); // 1 week
require_once(dirname(__FILE__) . "/../parsers/parser.anime.info.ID.php");
require_once(dirname(__FILE__) . "/../classes/class.cache.php");

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ---------------------CURL--------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $parts = isset($_GET['id']) ? explode("/",$_GET['id']) : array();
  if(empty($parts)) {
    echo json_encode(array(
      "message" => "The id parameter is not defined."
    ));
    http_response_code(400);
    return;
  }
  if(!is_numeric($parts[0])) {
    echo json_encode(array(
      "message" => "Specified anime id is not a number."
    ));
    http_response_code(400);
    return;
  }
  
  $url = "https://myanimelist.net/anime/" . $parts[0];
  $data = new Data();
  
  if($data->getCache($url, 604800)) {
    // Use cache if there is one
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
    if(curl_getinfo($ch, CURLINFO_HTTP_CODE) === 429) {
      echo json_encode(array(
        "message" => "Too many requests."
      ));
      http_response_code(429);
      return; // return so don't save cache
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

  $anime = AnimeInfoIDParser::parse($content);

  if(!$anime) return;

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  echo json_encode($anime);
  http_response_code(200);

});
?>