<?php
/*

Shows characters in an anime.

Method: GET
        /anime/characters/:id
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

  $url = "https://myanimelist.net/anime/" . $id . "/FoxInFlameIsAwesome/characters";
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
  
  $characters_arr = array();
  
  $characters = $html->find("#content tbody tr", 0)->children(1)->find(".js-scrollfix-bottom-rel", 0)->children();
  foreach($characters as $character) {
    if(strpos($character->name, "staff") !== false) break; // Stop before staffs (there is an empty element with the name staff right before staff section)
    if($character->tag !== "table") continue; // Only loop tables
    $character_arr = array(); // Not to be confused with plural array. This contains information about one character.
    $character_name = $character->find("td", 1)->find("a", 0)->innertext; // name, not sorted with comma (as it is on MAL)
    $character_id = explode("/", $character->find("td", 1)->find("a", 0)->href)[2];
    $character_role = $character->find("td", 1)->find(".spaceit_pad small", 0) ? strtolower($character->find("td", 1)->find(".spaceit_pad small", 0)->innertext) : null; // lowercase or null
    $character_image_2x = strpos($character->find("td", 0)->find("img", 0)->{'data-src'}, "characters") !== false ? $character->find("td", 0)->find("img", 0)->{'data-src'} : null;
    $character_image_full = $character_image_2x ? "https://myanimelist.cdn-dena.com/images/characters/" . explode("/", $character_image_2x)[7] . "/" . explode(".", explode("/", $character_image_2x)[8])[0] . ".jpg" : null;
    $character_image_1x = str_replace("46x64", "23x32", $character_image_2x);
    $character_actors = array();
    $character_actors_elem = $character->find("td", 2)->find("table tbody tr");
    foreach($character_actors_elem as $actor) {
      $actor_name = $actor->find("td", 0)->find("a", 0)->innertext; // name, not sorted with comma (as it is on MAL)
      $actor_id = explode("/", $actor->find("td", 0)->find("a", 0)->href)[2]; // id to search for
      $actor_language = strtolower($actor->find("td", 0)->find("small", 0)->innertext); // lowercase language (using it as key)
      $actor_image_2x = strpos($actor->find("td", 1)->find("img", 0)->{'data-src'}, "voiceactors") !== false ? $actor->find("td", 1)->find("img", 0)->{'data-src'} : null;
      $actor_image_full = $actor_image_2x ? "https://myanimelist.cdn-dena.com/images/voiceactors/" . explode("/", $actor_image_2x)[7] . "/" . explode(".", explode("/", $actor_image_2x)[8])[0] . ".jpg" : null;
      $actor_image_1x = str_replace("46x64", "24x32", $actor_image_2x);
      if(isset($character_actors[$actor_language])) { // key exists with array, add on
        $character_actors[$actor_language][] = array(
          "name" => $actor_name,
          "id" => $actor_id,
          "image" => $actor_image_full,
          "image_1x" => $actor_image_1x,
          "image_2x" => $actor_image_2x
        );
      } else { // key doesn't exist, create array there
        $character_actors[$actor_language] = array(array(
          "name" => $actor_name,
          "id" => $actor_id,
          "image" => $actor_image_full,
          "image_1x" => $actor_image_1x,
          "image_2x" => $actor_image_2x
        ));
      }
    }
    array_push($characters_arr, array(
      "name" => $character_name,
      "id" => $character_id,
      "image_auto" => $character_image_full,
      "image_1x" => $character_image_1x,
      "image_2x" => $character_image_2x,
      "role" => $character_role,
      "voice_actors" => $character_actors
    ));
  }
  
  
  $output = array(
    "characters" => $characters_arr
  );
  
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo json_encode($output, JSON_NUMERIC_CHECK);
  http_response_code(200);
  
});