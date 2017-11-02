<?php
/*

Shows stats of an anime.

This method is cached for a week. Set the nocache parameter to true to use a fresh version (slower).
Method: GET
        /anime/stats/:id
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

  $url = "https://myanimelist.net/anime/" . $id . "/FoxInFlameIsAwesome/stats";
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
  
  $summary = array(
    "watching" => 0,
    "completed" => 0,
    "on_hold" => 0,
    "dropped" => 0,
    "plan_to_watch" => 0,
    "total" => 0
  );
  $score = array(
    "1" => array(
      "percentage" => 0,
      "count" => 0
    ),
    "2" => array(
      "percentage" => 0,
      "count" => 0
    ),
    "3" => array(
      "percentage" => 0,
      "count" => 0
    ),
    "4" => array(
      "percentage" => 0,
      "count" => 0
    ),
    "5" => array(
      "percentage" => 0,
      "count" => 0
    ),
    "6" => array(
      "percentage" => 0,
      "count" => 0
    ),
    "7" => array(
      "percentage" => 0,
      "count" => 0
    ),
    "8" => array(
      "percentage" => 0,
      "count" => 0
    ),
    "9" => array(
      "percentage" => 0,
      "count" => 0
    ),
    "10" => array(
      "percentage" => 0,
      "count" => 0
    )
  );
  
  $elem_summary = $html->find("#content table tr", 0)->children(1)->find(".js-scrollfix-bottom-rel", 0);
  $elem_spaceits = $elem_summary->find(".spaceit_pad");
  foreach($elem_spaceits as $elem_spaceit) {
    if(strpos($elem_spaceit, "Watching") !== false) {
      $summary["watching"] = str_replace(",", "", substr($elem_spaceit->plaintext, 11));
      continue;
    }
    if(strpos($elem_spaceit, "Completed") !== false) {
      $summary["completed"] = str_replace(",", "", substr($elem_spaceit->plaintext, 12));
      continue;
    }
    if(strpos($elem_spaceit, "On-Hold") !== false) {
      $summary["on_hold"] = str_replace(",", "", substr($elem_spaceit->plaintext, 10));
      continue;
    }
    if(strpos($elem_spaceit, "Dropped") !== false) {
      $summary["dropped"] = str_replace(",", "", substr($elem_spaceit->plaintext, 10));
      continue;
    }
    if(strpos($elem_spaceit, "Plan to Watch") !== false) {
      $summary["plan_to_watch"] = str_replace(",", "", substr($elem_spaceit->plaintext, 16));
      continue;
    }
    if(strpos($elem_spaceit, "Total") !== false) {
      $summary["total"] = $summary["watching"] + $summary["completed"] + $summary["on_hold"] + $summary["dropped"] + $summary["plan_to_watch"];
      if(str_replace(",", "", substr($elem_spaceit->plaintext, 8)) != $summary["total"]) { // Not triple equal because the first one is string but the second one is int and so the type is different
        echo json_encode(array(
          "message" => "Some weird math going on.... Something about the total amount not being the same as the total on MAL."
        ));
        http_response_code(500);
        return;
      }
      continue;
    }
  }
  
  $elem_table_tr = $elem_summary->find("table tr");
  foreach($elem_table_tr as $tr) {
    if(!$tr->find("td .spaceit_pad .updatesBar", 0)) continue;
    $tr_score = $tr->find("td", 0)->innertext;
    $tr_percentage = substr($tr->find("td span text", 0)->innertext, 6, -2); // Remove &nbsp; and space and percentage sign after
    $tr_count = substr($tr->find("td span small", 0)->innertext, 1, -7); // Remove opening bracket and " votes)"
    foreach($score as $key => $score_1) {
      if($key != $tr_score) continue;
      $score[$key]["percentage"] = $tr_percentage;
      $score[$key]["count"] = $tr_count; // http://stackoverflow.com/questions/15024616/php-foreach-change-original-array-values
    }
  }
  $output = array(
    "list_stats" => $summary,
    "score_distribution" => $score
  );
  
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo json_encode($output, JSON_NUMERIC_CHECK);
  http_response_code(200);
  
});