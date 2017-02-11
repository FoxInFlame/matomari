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

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/anime/" . $id . "/FoxInFlameIsAwesome/characters");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response_string = curl_exec($ch);
  if(!$response_string) {
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
  
  $html = str_get_html($response_string);
  
  if(!is_object($html)) {
    echo json_encode(array(
      "message" => "The code for MAL is not valid HTML markup.",
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
    if(strpos($character->name, "staff") !== false) break; // Stop before staffs
    if($character->tag !== "table") continue; // Only loop tables
    $character_arr = array(); // Not to be confused with plural array. This contains information about one character.
    $character_name = $character->find("td", 1)->find("a", 0)->innertext; // name, not sorted with comma (as it is on MAL)
    $character_role = $character->find("td", 1)->find(".spaceit_pad small", 0) ? strtolower($character->find("td", 1)->find(".spaceit_pad small", 0)->innertext) : null; // lowercase or null
    $character_actors = array();
    $character_actors_elem = $character->find("td", 2)->find("table tbody tr");
    foreach($character_actors_elem as $actor) {
      $actor_name = $actor->find("td", 0)->find("a", 0)->innertext; // name, not sorted with comma (as it is on MAL)
      $actor_id = explode("/", $actor->find("td", 0)->find("a", 0)->href)[2]; // id to search for
      $actor_language = strtolower($actor->find("td", 0)->find("small", 0)->innertext); // lowercase language (using it as key)
      $actor_picture = $actor->find("td", 1)->find("img", 0)->{'data-src'};
      if(strpos($actor_picture, "voiceactors") === false) {
        // No Image: https://myanimelist.cdn-dena.com/images/questionmark_23.gif
        // Image example: https://myanimelist.cdn-dena.com/r/46x64/images/voiceactors/3/10613.jpg?s=835147e33307b4e2a7203f3341ccd9d1
        $actor_picture = null;
      }
      if(isset($character_actors[$actor_language])) { // key exists with array, add on
        $character_actors[$actor_language][] = array(
          "name" => $actor_name,
          "id" => $actor_id,
          "image" => $actor_picture
        );
      } else { // key doesn't exist, create array there
        $character_actors[$actor_language] = array(array(
          "name" => $actor_name,
          "id" => $actor_id,
          "image" => $actor_picture
        ));
      }
    }
    array_push($characters_arr, array(
      "name" => $character_name,
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