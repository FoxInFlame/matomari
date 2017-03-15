<?php
/*

Shows recommendations between two anime.

This method is cached for a day. Set the nocache parameter to true to use a fresh version (slower).
Method: GET
        /anime/recommendations/:id/:id
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
require_once(dirname(__FILE__) . "/../class/class.anime.php");
require_once(dirname(__FILE__) . "/../class/class.cache.php");

call_user_func(function() {

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------READING THE REQUEST------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]

  $id = isset($_GET['id']) ? $_GET['id'] : "";
  $id2 = isset($_GET['id2']) ? $_GET['id2'] : "";

  if(empty($id)) {
    echo json_encode(array(
      "message" => "The id parameter is not defined."
    ));
    http_response_code(400);
    return;
  }
  if(empty($id2)) {
    echo json_encode(array(
      "message" => "Two ids are not defined."
    ));
    http_response_code(400);
    return;
  }
  if(!is_numeric($id) || !is_numeric($id2)) {
    echo json_encode(array(
      "message" => "Specified anime id is not a number."
    ));
    http_response_code(400);
    return;
  }

  $url = "https://myanimelist.net/recommendations/anime/" . $id . "-" . $id2;
  $data = new Data(); // Initialise cache class

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
        "recommendations" => array()
      ));
      http_response_code(200);
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

  $recommendations = $html->find("#content .borderDark .borderClass");
  $anime_arr = array();
  foreach($recommendations as $recommendation) {
    $reason = explode("<span style=\"display:none\"", $recommendation->find(".spaceit_pad", 0)->innertext)[0] . $recommendation->find(".spaceit_pad span", 0)->innertext;
    $author = $recommendation->find(".spaceit_pad", 1)->find("a", 0)->innertext;
    array_push($anime_arr, array(
      "reason" => $reason,
      "author" => $author
    ));
  }
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "items" => $recommendations_arr
  );
  
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo json_encode($output, JSON_NUMERIC_CHECK);
  http_response_code(200);
});