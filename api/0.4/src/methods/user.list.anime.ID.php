<?php
/*

Status: Completed and Tested.
Get an anime in the user's list.

Method: GET
        /user/list/anime/:id
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
require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");
require_once(dirname(__FILE__) . "/../class/class.anime.php");

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
  $redirect_response = curl_exec($curl);
  if(!$redirect_response) {
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
  
  $html = str_get_html($redirect_response);
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
  
  $anime = new Anime();
  
  $html->find("#add_anime_status option[selected]", 0) && $html->find("#add_anime_status option[selected]", 0)->value != "" ? $anime->set("user_status", $html->find("#add_anime_status option[selected]", 0)->value) : $anime->set("user_status", null); // Selected or nothing
  $html->find("#add_anime_is_rewatching", 0)->checked ? $anime->set("user_rewatching", true) : $anime->set("user_rewatching", false); // checked=checked or nothing
  $anime->set("user_episodes", $html->find("#add_anime_num_watched_episodes", 0)->value); // 0 or episode number
  $html->find("#add_anime_score option[selected]", 0) && $html->find("#add_anime_score option[selected]", 0)->value != "" ? $anime->set("user_score", $html->find("#add_anime_score option[selected]", 0)->value) : $anime->set("score", null); // Selected or nothing
  $html->find("#add_anime_start_date_month option[selected]", 0) && $html->find("#add_anime_start_date_month option[selected]", 0)->value != "" ? $startdate_month = substr("0" . $html->find("#add_anime_start_date_month option[selected]", 0)->value, -2) : $startdate_month = "--"; // Selected (int) or nothing
  $html->find("#add_anime_start_date_day option[selected]", 0) && $html->find("#add_anime_start_date_day option[selected]", 0)->value != "" ? $startdate_day = substr("0" . $html->find("#add_anime_start_date_day option[selected]", 0)->value, -2) : $startdate_day = "--"; // Selected (int) or nothing
  $html->find("#add_anime_start_date_year option[selected]", 0) && $html->find("#add_anime_start_date_year option[selected]", 0)->value != "" ? $startdate_year = $html->find("#add_anime_start_date_year option[selected]", 0)->value : $startdate_year = "----"; // Selected (int) or nothing
  $anime->set("user_start_date", $startdate_year . $startdate_month . $startdate_day);
  $html->find("#add_anime_finish_date_month option[selected]", 0) && $html->find("#add_anime_finish_date_month option[selected]", 0)->value != "" ? $enddate_month = substr("0" . $html->find("#add_anime_finish_date_month option[selected]", 0)->value, -2) : $enddate_month = "--"; // Selected (int) or nothing
  $html->find("#add_anime_finish_date_day option[selected]", 0) && $html->find("#add_anime_finish_date_day option[selected]", 0)->value != "" ? $enddate_day = substr("0" . $html->find("#add_anime_finish_date_day option[selected]", 0)->value, -2) : $enddate_day = "--"; // Selected (int) or nothing
  $html->find("#add_anime_finish_date_year option[selected]", 0) && $html->find("#add_anime_finish_date_year option[selected]", 0)->value != "" ? $enddate_year = $html->find("#add_anime_finish_date_year option[selected]", 0)->value : $enddate_year = "----"; // Selected (int) or nothing
  $anime->set("user_end_date", $enddate_year . $enddate_month . $enddate_day);
  $anime->set("user_tags", html_entity_decode($html->find("#add_anime_tags", 0)->innertext, ENT_QUOTES)); // empty or something - decode quotes and all that
  $html->find("#add_anime_priority option[selected]", 0) ? $anime->set("user_priority", $html->find("#add_anime_priority option[selected]", 0)->value) : $anime->set("priority", "0"); // There is no empty situation, 0 is the default.
  $html->find("#add_anime_storage_type option[selected]", 0) && $html->find("#add_anime_storage_type option[selected]", 0)->value != "" ? $anime->set("user_storage", $html->find("#add_anime_storage_type option[selected]", 0)->value) : $anime->set("user_storage", null);
  $anime->set("user_storage_value", $html->find("#add_anime_storage_value", 0)->value);
  $anime->set("user_rewatch_times", $html->find("#add_anime_num_watched_times", 0)->value); // 0 or something
  $html->find("#add_anime_rewatch_value option[selected]", 0) && $html->find("#add_anime_rewatch_value option[selected]", 0)->value != "" ? $anime->set("user_rewatch_value", $html->find("#add_anime_rewatch_value option[selected]", 0)->value) : $anime->set("user_rewatch_value", null);
  $anime->set("user_comments", html_entity_decode($html->find("#add_anime_comments", 0)->innertext, ENT_QUOTES)); // Decode quotes and all that
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "status" => $anime->get("user_status"),
    "status_str" => $anime->get("user_status_str"),
    "rewatching" => $anime->get("user_rewatching"),
    "episodes" => $anime->get("user_episodes"),
    "score" => $anime->get("user_score"),
    "startdate" => "string_" . $anime->get("user_start_date"),
    "enddate" => "string_" . $anime->get("user_end_date"),
    "tags" => $anime->get("user_tags"),
    "priority" => $anime->get("user_priority"),
    "storage" => $anime->get("user_storage"),
    "storage_value" => $anime->get("user_storage_value"),
    "rewatch_times" => $anime->get("user_rewatch_times"),
    "rewatch_value" => $anime->get("user_rewatch_value"),
    "comments" => $anime->get("user_comments")
  );
  
  // Remove string_ after parse
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo str_replace("string_", "", json_encode($output, JSON_NUMERIC_CHECK));
  http_response_code(200);

});
?>