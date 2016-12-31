<?php
/*

Quick Search. Always returns 10 items.

Method: GET
        /general/quickSearch/:query
Parameters:
  - filter: [Optional] "all", anime", "manga", "person", "character", "news", "featured", "forum", "club", or "user"

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

call_user_func(function() {

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ------------------GET JSON-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $query = isset($_GET['q']) && !empty($_GET['q']) ? $_GET['q'] : "";

  if(!isset($_GET['q']) || empty($_GET['q'])) {
    echo json_encode(array(
      "error" => "Parameter q is not supplied."
    ));
    http_response_code(400);
    return;
  }
  $filter = isset($_GET['filter']) && !empty($_GET['filter']) ? $_GET['filter'] : "all";
  $filter_param = "all";
  switch(strtolower($filter)) {
    case "all":
      $filter_param = "all";
      break;
    case "anime":
      $filter_param = "anime";
      break;
    case "manga":
      $filter_param = "manga";
      break;
    case "person":
      $filter_param = "person";
      break;
    case "character":
      $filter_param = "character";
      break;
    case "news":
      $filter_param = "news";
      break;
    case "featured":
      $filter_param = "featured";
      break;
    case "forum":
      $filter_param = "forum";
      break;
    case "club":
      $filter_param = "club";
      break;
    case "user":
      $filter_param = "user";
      break;
  }
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/search/prefix.json?type=" . $filter_param . "&keyword=" . $query . "&v=1"); // No idea what the v parameter does. Experimented different values until 10 with no difference in results.
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == 404) {
    echo json_encode(array(
      "error" => "MAL is offline or their code changed."
    ));
    http_response_code(404);
    return;
  }
  
  $json = json_decode($response);
  $categories_arr = array();
  if(isset($json->errors)) {
    echo json_encode(array(
      "error" => $json->errors[0]->message
    ));
    http_response_code(400);
    return;
  }
  foreach($json->categories as $category) {
    $items_arr = array();
    foreach($category->items as $item) {
      if(strpos($item->url, "http") === false && $item->url[0] == "/") {
        $item->url = "https://myanimelist.net" . $item->url;
      }
      array_push($items_arr, array(
        "id" => $item->id,
        "type" => $item->type,
        "name" => $item->name,
        "url" => $item->url,
        "image_url" => $item->image_url,
        "image_thumbnail_url" => $item->thumbnail_url,
        "details" => $item->payload,
        "es_score" => $item->es_score
      ));
    }
    $categories_arr[$category->type] = $items_arr;
  }

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "results" => $categories_arr
  );
  
  // Remove string_ after parse
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo json_encode($output, JSON_NUMERIC_CHECK);
  http_response_code(200);
    
});
?>