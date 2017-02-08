<?php
/*

Shows the history for a MAL user for the past 3 weeks (27 days).

Method: GET
        /user/history/:username
Authentication: None Required.
Parameters:
  - type: [Optional] Anime or manga (Defaults to anime)

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
require_once(dirname(__FILE__) . "/../absoluteGMT.php");

call_user_func(function() {

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------GETTING THE VALUES-------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $parts = isset($_GET['username']) ? explode("/",$_GET['username']) : array();
  if(empty($parts)) {
    echo json_encode(array(
      "message" => "The username parameter is not defined."
    ));
    http_response_code(400);
    return;
  }
  $type = "anime";
  if(isset($_GET['type'])) {
    switch(strtolower($_GET['type'])) {
      case "anime":
        $type = "anime";
        break;
      case "manga":
        $type = "manga";
        break;
      default:
        $type = "anime";
    }
  }
  $html = @file_get_html("https://myanimelist.net/history/" . $parts[0] . "/" . $type);
  if(!$html) {
    echo json_encode(array(
      "message" => "Username was not found or MAL is offline."
    ));
    http_response_code(404);
    return;
  }
  
  
  //    [+] ============================================== [+]
  //    [+] --------------SETTING THE VALUES-------------- [+]
  //    [+] ============================================== [+]
  
  $username = $parts[0];
  $mal_link = "https://myanimelist.net/profile/" . $username;
  $history_tr = $html->find("div#contentWrapper div#content table tbody tr");
  $list = array();
  foreach($history_tr as $tr) {
    if(!$tr->find("td.borderClass")) {
      continue;
    } else {
      if($type == "anime") {
        array_push($list, array(
          "title" => $tr->find("td.borderClass a", 0)->innertext,
          "id" => substr($tr->find("td.borderClass a", 0)->href, 14),
          "episode" => $tr->find("td.borderClass strong", 0)->innertext,
          "time" => getAbsoluteTimeGMT($tr->find("td.borderClass", 1)->plaintext, "M j, g:i A")->format("c")
        ));
      } else if($type == "manga") {
        array_push($list, array(
          "title" => $tr->find("td.borderClass a", 0)->innertext,
          "id" => substr($tr->find("td.borderClass a", 0)->href, 14),
          "chapter" => $tr->find("td.borderClass strong", 0)->innertext,
          "time" => getAbsoluteTimeGMT($tr->find("td.borderClass", 1)->plaintext, "M j, g:i A")->format("c")
        ));
      }
      continue;
    }
  }


  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "items" => $list
  );
  
  // Remove string_ after parse
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo str_replace("string_", "", json_encode($output, JSON_NUMERIC_CHECK));
  http_response_code(200);
  
});

?>