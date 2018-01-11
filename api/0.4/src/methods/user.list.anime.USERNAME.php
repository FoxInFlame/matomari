<?php
/*

Status: Completed and Tested.
Displays a user's anime list with pagination.

This method is cached for an hour. Set the nocache parameter to true to use a fresh version (slower).
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
header("Cache-Control: max-age=3600, public");
header("Content-Type: application/json");
require_once(dirname(__FILE__) . "/../classes/class.cache.php");
require_once(dirname(__FILE__) . "/../parsers/parser.user.list.anime.USERNAME.php");

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
        "message" => "The provided status is not valid."
      ));
      http_response_code(400);
      return;
    }
    $status = $_GET['status'];
  } else {
    $status = "7";
  }

  $url = "https://myanimelist.net/malappinfo.php?u=" . $_GET['username'] . "&type=anime&status=all";
  $data = new Data();
  
  if($data->getCache($url, 60, ".xml")) { // One hour server cache.
    // Use cache if there is one
    $malresponse_xml = $data->data;
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
    if(curl_getinfo($ch, CURLINFO_HTTP_CODE) === 429) {
      echo json_encode(array(
        "message" => "Too many requests."
      ));
      http_response_code(429);
      return; // return so don't save cache
    }
    if(strpos($response, "<myanimelist></myanimelist>") !== false) {
      echo json_encode(array(
        "message" => "The provided user could not be found."
      ));
      http_response_code(404);
      return;
    }
    curl_close($ch);
    
    $data->saveCache($url, $response, ".xml");
    $malresponse_xml = $response;
  }

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ------------GETTING THE JSON List------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]

  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

  $offset = ($page - 1) * 300;
  
  $url = "https://myanimelist.net/animelist/" . $_GET['username'] . "/load.json?offset=" . $offset . "&status=" . $status;
  $data = new Data();

  if($data->getCache($url, 60, ".json")) { // One hour server cache.
    // Use cache if there is one
    $malresponse_json = $data->data;
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
    if(curl_getinfo($ch, CURLINFO_HTTP_CODE) === 429) {
      echo json_encode(array(
        "message" => "Too many requests."
      ));
      http_response_code(429);
      return; // return so don't save cache
    }
    curl_close($ch);

    $data->saveCache($url, $response, ".json");
    $malresponse_json = $response;
  }
  
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ---------------------PARSE-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]

  $anime = UserListAnimeUSERNAMEParser::parse($malresponse_xml, $malresponse_json);

  if(!$anime) return;


  header("matomari-Total-Count: " . (string)$anime[0]["total"]);
  $page_parameters = $_GET;
  $link_headers = [];
  if($page > 1) {
    $page_parameters['page'] = 1;
    array_push($link_headers, "<https://www.matomari.tk" . $_SERVER['PHP_SELF'] . http_build_query($page_parameters) . ">; rel=\"first\"");
    $page_parameters['page'] = $page - 1;
    array_push($link_headers, "<https://www.matomari.tk" . $_SERVER['PHP_SELF'] . http_build_query($page_parameters) . ">; rel=\"prev\"");
  }
  if($page + 1 <= ceil($anime[0]["total"] / 300)) {// If the next page still exists
    $page_parameters['page'] = $page + 1;
    array_push($link_headers, "<https://www.matomari.tk" . $_SERVER['PHP_SELF'] . http_build_query($page_parameters) . ">; rel=\"next\"");
  }
  $page_parameters['page'] = ceil($anime[0]["total"] / 300); // Round up.
  array_push($link_headers, "<https://www.matomari.tk" . $_SERVER['PHP_SELF'] . http_build_query($page_parameters) . ">; rel=\"last\"");
  
  header("Link: " . implode(", ", $link_headers));

  echo json_encode(array(
    "stats" => $anime[0],
    "items" => $anime[1]
  ));

  http_response_code(200);
});
?>