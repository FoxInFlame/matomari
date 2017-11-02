<?php
/*

Shows news related to an anime.

This method is cached for a week. Set the nocache parameter to true to use a fresh version (slower).
Method: GET
        /anime/news/:id
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

ini_set("display_errors", true);
ini_set("display_startup_errors", true);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Cache-Control: no-cache, must-revalidate");
require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");
require_once(dirname(__FILE__) . "/../class/class.cache.php");

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ---------------READ THE REQUEST--------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $id = isset($_GET['id']) ? $_GET['id'] : "";
  if(empty($id)) {
    echo json_encode(array(
      "message" => "The id parameter is not defined."
    ));
    http_response_code(400);
    return;
  }
  if(!is_numeric($id)) {
    echo json_encode(array(
      "message" => "Specified anime id is not a number."
    ));
    http_response_code(400);
    return;
  }

  $url = "https://myanimelist.net/anime/" . $id . "/FoxInFlameIsAwesome/news";
  $data = new Data();
  
  if($data->getCache($url)) {
    $html = str_get_html($data->data);
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
      echo json_encode(array(
        "message" => "Anime with specified id could not be found."
      ));
      http_response_code(404);
      return;
    }
    curl_close($ch);
    
    $data->saveCache($url, $response);
    $html = str_get_html($response);
  }
  
  if(!is_object($html)) {
    echo json_encode(array(
      "message" => "The code for MAL is not valid HTML markup."
    ));
    http_response_code(500);
    return;
  }
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------GET/SET THE VALUES-------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $news_arr = array();
  
  $children = $html->find("#content tr", 0)->children(1)->find(".js-scrollfix-bottom-rel", 0)->children();
  
  foreach($children as $child) {
    if(strpos($child->class, "clearfix") === false) {
      continue;
    }
    array_push($news_arr, array(
      "id" => substr($child->find(".picSurround a", 0)->href, 6),
      "image" => $child->find(".picSurround a img", 0)->{'data-srcset'} ? $child->find(".picSurround a img", 0)->{'data-srcset'} : $child->find(".picSurround a img", 0)->{'srcset'},
      "title" => $child->find(".spaceit a strong", 0)->innertext,
      "url" => "https://myanimelist.net" . $child->find(".picSurround a", 0)->href,
      "content_snippet" => str_replace($child->find(".clearfix p a", 0)->outertext, "", $child->find(".clearfix p", 0)->innertext),
      "author" => $child->find(".lightLink a", 0)->innertext,
      "time" => $child->find(".lightLink", 0)->find("text", 0)->innertext,
      "discuss" => $child->find(".lightLink a", 1)->href
    ));
  }
  
  $output = array(
    "news" => $news_arr
  );
  
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo json_encode($output, JSON_NUMERIC_CHECK);
  http_response_code(200);
  
});