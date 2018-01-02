<?php
/*

Status: Completed and Tested.
Get an anime in the user's list.

This method is not cached.
Method: GET
        /@me/list/anime/:id
Authentication: HTTP Basic Auth with MAL Credentials.
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
header("Cache-Control: no-cache, must-revalidate");
require_once(dirname(__FILE__) . "/../parsers/parser.@me.list.anime.ID.php");

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------LOGIN--------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  require_once(dirname(__FILE__) . "/../authenticate_base.php");
  
  if(!isset($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || empty($_SERVER['PHP_AUTH_PW'])) {
    header("WWW-Authenticate: Basic realm=\"myanimelist.net\"");
    echo json_encode(array(
      "message" => "Authorisation Required."
    ));
    http_response_code(401);
    return;
  } else {
    $MALsession = getSession($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
    if(!$MALsession) return;
  }


  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------VALIDATE REQUEST---------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $submit_url;
  
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
  
  $curl = curl_init();
  $submit_url = "https://myanimelist.net/ownlist/anime/" . $_GET['id'] . "/edit";
  curl_setopt($curl, CURLOPT_URL, $submit_url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_COOKIE, $MALsession['cookie_string']);
  curl_setopt($curl, CURLOPT_HEADER, true);
  $response = curl_exec($curl);
  if(!$response) {
    echo json_encode(array(
      "message" => "MAL is offline."
    ));
    http_response_code(404);
    return;
  }
  if(curl_getinfo($curl, CURLINFO_HTTP_CODE) === 303) {
    echo json_encode(array(
      "message" => "Anime with specified id could not be found in the list."
    ));
    http_response_code(404);
    return;
  }
  curl_close($curl);

  $content = $response;
    
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ---------------------PARSE-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]  
  
  $anime = MeListAnimeIDParser::parse($content);

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  echo json_encode($anime);
  http_response_code(200);

});
?>