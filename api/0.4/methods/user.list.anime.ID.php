<?php
/*

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
  
  $html->find("#add_anime_status option[selected]", 0) && $html->find("#add_anime_status option[selected]", 0)->value != "" ? $status = $html->find("#add_anime_status option[selected]", 0)->value : $status = ""; // Selected or nothing
  $html->find("#add_anime_is_rewatching", 0)->checked ? $rewatching = true : $rewatching = false; // checked=checked or nothing
  $episodes = $html->find("#add_anime_num_watched_episodes", 0)->value; // 0 or episode number
  $html->find("#add_anime_score option[selected]", 0) && $html->find("#add_anime_score option[selected]", 0)->value != "" ? $score = $html->find("#add_anime_score option[selected]", 0)->value : $score = ""; // Selected or nothing
  $html->find("#add_anime_start_date_month option[selected]", 0) && $html->find("#add_anime_start_date_month option[selected]", 0)->value != "" ? $startdate_month = substr("0" . $html->find("#add_anime_start_date_month option[selected]", 0)->value, -2) : $startdate_month = "--"; // Selected (int) or nothing
  $html->find("#add_anime_start_date_day option[selected]", 0) && $html->find("#add_anime_start_date_day option[selected]", 0)->value != "" ? $startdate_day = substr("0" . $html->find("#add_anime_start_date_day option[selected]", 0)->value, -2) : $startdate_day = "--"; // Selected (int) or nothing
  $html->find("#add_anime_start_date_year option[selected]", 0) && $html->find("#add_anime_start_date_year option[selected]", 0)->value != "" ? $startdate_year = $html->find("#add_anime_start_date_year option[selected]", 0)->value : $startdate_year = "----"; // Selected (int) or nothing
  $html->find("#add_anime_finish_date_month option[selected]", 0) && $html->find("#add_anime_finish_date_month option[selected]", 0)->value != "" ? $enddate_month = substr("0" . $html->find("#add_anime_finish_date_month option[selected]", 0)->value, -2) : $enddate_month = "--"; // Selected (int) or nothing
  $html->find("#add_anime_finish_date_day option[selected]", 0) && $html->find("#add_anime_finish_date_day option[selected]", 0)->value != "" ? $enddate_day = substr("0" . $html->find("#add_anime_finish_date_day option[selected]", 0)->value, -2) : $enddate_day = "--"; // Selected (int) or nothing
  $html->find("#add_anime_finish_date_year option[selected]", 0) && $html->find("#add_anime_finish_date_year option[selected]", 0)->value != "" ? $enddate_year = $html->find("#add_anime_finish_date_year option[selected]", 0)->value : $enddate_year = "----"; // Selected (int) or nothing
  $tags = html_entity_decode($html->find("#add_anime_tags", 0)->innertext, ENT_QUOTES); // empty or something - decode quotes and all that
  $html->find("#add_anime_priority option[selected]", 0) ? $priority = $html->find("#add_anime_priority option[selected]", 0)->value : $priority = "0"; // There is no empty situation, 0 is the default.
  $html->find("#add_anime_storage_type option[selected]", 0) && $html->find("#add_anime_storage_type option[selected]", 0)->value != "" ? $storage = $html->find("#add_anime_storage_type option[selected]", 0)->value : $storage = "";
  $storage_value = $html->find("#add_anime_storage_value", 0)->value;
  $rewatch_times = $html->find("#add_anime_num_watched_times", 0)->value; // 0 or something
  $html->find("#add_anime_rewatch_value option[selected]", 0) && $html->find("#add_anime_rewatch_value option[selected]", 0)->value != "" ? $rewatch_value = $html->find("#add_anime_rewatch_value option[selected]", 0)->value : $rewatch_value = "";
  $comments = html_entity_decode($html->find("#add_anime_comments", 0)->innertext, ENT_QUOTES); // Decode quotes and all that
  
  $startdate = $startdate_year . $startdate_month . $startdate_day;
  $enddate = $enddate_year . $enddate_month . $enddate_day;
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "status" => $status,
    "episodes" => $episodes,
    "score" => $score,
    "startdate" => "string_" . $startdate,
    "enddate" => "string_" . $enddate,
    "tags" => $tags,
    "priority" => $priority,
    "storage" => $storage,
    "storage_value" => $storage_value,
    "rewatch_times" => $rewatch_times,
    "rewatch_value" => $rewatch_value,
    "comments" => $comments
  );
  
  // Remove string_ after parse
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo str_replace("string_", "", json_encode($output, JSON_NUMERIC_CHECK));
  http_response_code(200);

});
?>