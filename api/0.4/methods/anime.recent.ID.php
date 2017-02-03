<?php
/*

Shows recent updates for an anime.

Method: GET
        /anime/recent/:id
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
require_once(dirname(__FILE__) . "/../authenticate_base.php");

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
  
  $recent_params = "";
  if(!isset($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || empty($_SERVER['PHP_AUTH_PW'])) {
    // No auth provided.
  } else {
    // Auth is provided.
    $MALSession = getSession($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
    if(isset($_GET['filter']) && !empty($_GET['filter'])) {
      // Filter is defined.
      switch(strtolower($_GET['filter'])) {
        case "all":
          $recent_params = "&m=all#members";
          break;
        case "friends":
          $recent_params = "#members";
          break;
        default:
          $recent_params = "#members";
          break;
      }
    }
  }

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/anime/" . $id . "/FoxInFlameIsAwesome/stats" . $recent_params);
  if($recent_params == "#members" || $recent_params == "&m=all#members") {
    curl_setopt($ch, CURLOPT_COOKIE, $MALSession['cookie_string']);
  }
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response_string = curl_exec($ch);
  if(curl_getinfo($ch, CURLINFO_HTTP_CODE) === 404) {
    echo json_encode(array(
      "message" => "Anime with specified id could not be found."
    ));
    http_response_code(404);
    return;
  }
  
  curl_close($ch);
  
  if(!$response_string) {
    echo json_encode(array(
      "message" => "MAL is offline."
    ));
    http_response_code(404);
    return;
  }
  
  $html = str_get_html($response_string);
  
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
  
  $updates = array();
  
  $elem_summary = $html->find("#content table tr", 0)->children(1)->find(".js-scrollfix-bottom-rel", 0);
  
  $elem_table_tr = $elem_summary->find("table", 1)->find("tr");
  foreach($elem_table_tr as $key => $tr) {
    if($key == 0) continue;
    $tr_username = $tr->find("td", 0)->find("a", 1)->innertext;
    $tr_image_url = substr($tr->find("td", 0)->find("a", 0)->style, 21, -1);
    if(strpos($tr_image_url, "userimages") === false) {
      $tr_image_url = null;
    } else {
      $tr_image_url = str_replace("_thumb", "", str_replace("/thumbs", "", $tr_image_url));
    }
    $tr_score = $tr->find("td", 1)->innertext;
    if($tr_score == "-") {
      $tr_score = null;
    }
    $tr_status_text = $tr->find("td", 2)->innertext;
    switch($tr_status_text) {
      case "Watching":
        $tr_status = 1;
        break;
      case "Completed":
        $tr_status = 2;
        break;
      case "On Hold":
        $tr_status = 3;
        break;
      case "Dropped":
        $tr_status = 4;
        break;
      case "Plan to Watch":
        $tr_status = 6;
        break;
      default:
        $tr_status = null;
        break;
    }
    array_push($updates, array(
      "username" => $tr_username,
      "image_url" => $tr_image_url,
      "score" => $tr_score,
      "status" => $tr_status,
      "status_text" => $tr_status_text
    ));
  }
  
  $output = array(
    "items" => $updates
  );
  
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo json_encode($output, JSON_NUMERIC_CHECK);
  http_response_code(200);
  
});