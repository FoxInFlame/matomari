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
          "time" => getAbsoluteTimeGMT($tr->find("td.borderClass", 1)->plaintext)->format("c")
        ));
      } else if($type == "manga") {
        array_push($list, array(
          "title" => $tr->find("td.borderClass a", 0)->innertext,
          "id" => substr($tr->find("td.borderClass a", 0)->href, 14),
          "chapter" => $tr->find("td.borderClass strong", 0)->innertext,
          "time" => getAbsoluteTimeGMT($tr->find("td.borderClass", 1)->plaintext)->format("c")
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

function getAbsoluteTimeGMT($string) {
  $string = trim($string); // Super important! :)
  if(strpos($string, "ago") !== false) {
    /*Note: These are returning approximate values */
    $date = new DateTime(null);
    $date->setTimeZone(new DateTimeZone("Etc/GMT"));
    if(strpos($string, "hour") !== false) {
      if(strpos($string, "hours") !== false) {
        $hours = substr($string, 0, -10);
        $date->modify("-" . $hours . " hours");
      } else {
        $hour = substr($string, 0, -9);
        $date->modify("-" . $hour . " hour");
      }
    }
    if(strpos($string, "minute") !== false) {
      if(strpos($string, "minutes") !== false) {
        $minutes = substr($string, 0, -12);
        $date->modify("-" . $minutes . " minutes");
      } else {
        $minute = substr($string, 0, -11);
        $date->modify("-" . $minute . " minute");
      }
    }
    if(strpos($string, "second") !== false) {
      if(strpos($string, "seconds") !== false) {
        $seconds = substr($string, 0, -12);
        $date->modify("-" . $seconds . " seconds");
      } else {
        $second = substr($string, 0, -11);
        $date->modify("-" . $second . " second");
      }
    }
    return $date;
  } else if(strpos($string, "Today") !== false) {
    $date = date_create_from_format("g:i A", substr($string, 7), new DateTimeZone("Etc/GMT+8"))->setTimeZone(new DateTimeZone("Etc/GMT"));
    return $date;
  } else if(strpos($string, "Yesterday") !== false) {
    $date = date_create_from_format("g:i A", substr($string, 11), new DateTimeZone("Etc/GMT+8"))->setTimeZone(new DateTimeZone("Etc/GMT"));
    $date->modify("-1 day");
    return $date;
  } else {
    // "M j, g:i A" is the date type MAL shows
    $date = date_create_from_format("M j, g:i A", $string, new DateTimeZone("Etc/GMT+8"));
    if(!$date) {
      // Different year.
      $date = date_create_from_format("M j, Y g:i A", $string, new DateTimeZone("Etc/GMT+8"));
    }
    $date->setTimeZone(new DateTimeZone("Etc/GMT"));
    return $date;
  }
}
?>