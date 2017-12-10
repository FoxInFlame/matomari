<?php
/*

Displays a user's anime list with pagination.

Method: GET
        /user/list/anime/:username
Authentication: None Required.
Parameters:
  - status: [Optional] Status (integer)
  - page: [Optional] Page (300 items per page)

Created by FoxInFlame.
A Part of the matomari API.

*/

// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] -------------------HEADERS-------------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache, must-revalidate");
header("Content-Type: application/json"); // 1 day
require_once(dirname(__FILE__) . "/../classes/class.cache.php");
# require_once(dirname(__FILE__) . "/../parsers/parser.user.list.anime.USERNAME.php");

call_user_func(function() {

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] -------------GETTING THE XML List------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]

  if(!isset($_GET['username'])) {
    echo json_encode(array(
      "message" => "The username parameter is not defined."
    ));
    http_response_code(400);
    return;
  }

  if(isset($_GET['status'])) {
    if(!is_numeric($_GET['status']) || !in_array($_GET['status'], ["1", "2", "3", "4", "6", "7"])) {
      echo json_encode(array(
        "message" => "Specified status is not valid."
      ));
      http_response_code(400);
      return;
    }
    $status = $_GET['status'];
  } else {
    $status = "7";
  }

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/malappinfo.php?u=" . $_GET['username'] . "&type=anime&status=all");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $malresponse_xml = curl_exec($ch);
  curl_close($ch);
  if(!$malresponse_xml) {
    echo json_encode(array(
      "message" => "MAL is offline."
    ));
    http_response_code(404);
    return;
  }
  if(strpos($malresponse_xml, "<myanimelist></myanimelist>") !== false) {
    echo json_encode(array(
      "message" => "User not found."
    ));
    http_response_code(404);
    return;
  }

  $malresponse_xml = new SimpleXMLElement($malresponse_xml);

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ------------GETTING THE JSON List------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]

  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : "1";

  $offset = ($page - 1) * 300;
  
  $ch = curl_init();
  $url = "https://myanimelist.net/animelist/" . $_GET['username'] . "/load.json?offset=" . $offset . "&status=" . $status;

  // cURL
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url); // Set the URL
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the content
  $malresponse_json = curl_exec($ch); // Execute the request, and show the response
  if(!$malresponse_json) {
    echo json_encode(array(
      "message" => "MAL is offline."
    ));
    http_response_code(404);
    return;
  }
  $malresponse_json_arr = json_decode($malresponse_json);
  $malresponse_json_reformatted = [];
  foreach($malresponse_json_arr as $malresponse_json_item) {
    $malresponse_json_reformatted[$malresponse_json_item->anime_id] = $malresponse_json_item;
  }

  foreach($malresponse_xml->anime as $anime) {
    $key = (string)$anime->series_animedb_id;
    if(!array_key_exists($key, $malresponse_json_reformatted)) continue;
    $malresponse_json_reformatted[$key]->anime_synonyms = (string)$anime->series_synonyms;
    $malresponse_json_reformatted[$key]->anime_image_path = (string)$anime->series_image;
    $malresponse_json_reformatted[$key]->last_updated = (string)$anime->my_last_updated;
    unset($malresponse_json_reformatted[$key]->anime_studios);
    unset($malresponse_json_reformatted[$key]->anime_licensors);
    unset($malresponse_json_reformatted[$key]->anime_season);
    unset($malresponse_json_reformatted[$key]->anime_studios);
  }

  echo json_encode(array(
    "items" => $malresponse_json_reformatted
  ));

  http_response_code(200);
});
?>