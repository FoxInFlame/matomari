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
  
  $staffs_arr = array();
  
  $charstaffs = $html->find("#content tbody tr", 0)->children(1)->find(".js-scrollfix-bottom-rel", 0)->children();
  $process = false;
  foreach($charstaffs as $charstaff) {
    if(strpos($charstaff->name, "staff") !== false) $process = true; // Don't start processing before staffs is mentioned (there is an empty element with the name staff right before staff section)
    if(!$process) continue;
    if($charstaff->tag !== "table") continue; // Only loop tables (there should only be one)
    foreach($charstaff->children() as $staff) { // For some reason all staff are in one table of their own so loop that (apparently tbody is not index in simplehtmldom or it would've been $charstaf->children(0)->children()...)
      $staff_arr = array(); // Not to be confused with plural array. This contains information about one staff.
      $staff_name = $staff->find("td", 1)->find("a", 0)->innertext; // name, not sorted with comma (as it is on MAL)
      $staff_id = explode("/", $staff->find("td", 1)->find("a", 0)->href)[2]; // Assuming everyone has an id
      $staff_roles = $staff->find("td", 1)->find("small", 0) ? explode(", ", $staff->find("td", 1)->find("small", 0)->innertext) : array(); // array with roles or empty if no roles (doesn't have a role)
      $staff_image = strpos($staff->find("td", 1)->find("img", 0)->{'data-src'}, "voiceactors") === false ? $staff->find("td", 1)->find("img", 0)->{'data-src'} : null; // Apparently staff is considered as VAs on MAL
      array_push($staffs_arr, array(
        "name" => $staff_name,
        "id" => $staff_id,
        "image" => $staff_image,
        "roles" => $staff_roles // Beware, roles, not role like characters
      ));
    }
  }
  
  
  $output = array(
    "staff" => $staffs_arr
  );
  
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo json_encode($output, JSON_NUMERIC_CHECK);
  http_response_code(200);
  
});