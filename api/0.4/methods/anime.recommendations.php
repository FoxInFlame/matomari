<?php
/*

Get latest anime recommendations.

Method: GET
        /anime/recommendations
Authentication: None Required.
Parameters:
  - page: [Optional] Page number. If page doesn't exist, becomes 1. (defaults to 1)

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
  // [+] ---------GETTING THE RECOMMENDATIONS---------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : "1";
  $show = ($page - 1) * 100;
  $page_param = "?show=" . $show;
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/recommendations.php" . $page_param);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response_string = curl_exec($ch);
  
  if(!$response_string) {
    if($page != 1) {
      curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/recommendations.php");
      $response_string = curl_exec($ch);
      curl_close($ch);
      if(!$response_string) {
        echo json_encode(array(
          "error" => "MAL is offline, or their code changed."
        ));
        return;
      }
    } else {
      curl_close($ch);
      echo json_encode(array(
        "error" => "MAL is offline, or their code changed."
      ));
      return;
    }
  }
  
  $html = str_get_html($response_string);
  
  $recommendations = $html->find("#contentWrapper #content", 0)->children(2)->children();
  $recommendations_arr = array();
  foreach($recommendations as $key => $recommendation) {
    if($key === 0) {
      continue;
    }
    if($key === (count($recommendations) - 1)) {
      continue;
    }
    $from = $recommendation->find("table td", 0);
    $from_id = substr($from->find(".picSurround a", 0)->id, 9);
    $from_picture = $from->find(".picSurround a img", 0)->{'data-srcset'};
    $from_picture_1x = explode(" 1x,", $from_picture)[0];
    $from_picture_2x = substr(explode(" 1x,", $from_picture)[1], 0, -3);
    $from_title = $from->find("a strong", 0)->innertext;
    $to = $recommendation->find("table td", 1);
    $to_id = substr($to->find(".picSurround a", 0)->id, 9);
    $to_picture = $to->find(".picSurround a img", 0)->{'data-srcset'};
    $to_picture_1x = explode(" 1x,", $to_picture)[0];
    $to_picture_2x = substr(explode(" 1x,", $to_picture)[1], 0, -3);
    $to_title = $to->find("a strong", 0)->innertext;
    $reason = $recommendation->children(1)->innertext;
    $author = $recommendation->children(2)->find("a", 1)->innertext;
    $time_1 = explode(" - ", $recommendation->children(2)->innertext);
    $time = end($time_1);
    array_push($recommendations_arr, array(
      "from" => array(
        "id" => $from_id,
        "image_1x" => $from_picture_1x,
        "image_2x" => $from_picture_2x,
        "title" => $from_title
      ),
      "to" => array(
        "id" => $to_id,
        "image_1x" => $to_picture_1x,
        "image_2x" => $to_picture_2x,
        "title" => $to_title
      ),
      "reason" => $reason,
      "author" => $author,
      "time" => getAbsoluteTimeGMT($time)->format("c")
    ));
  }
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = $recommendations_arr;
  
  // Remove string_ after parse
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo str_replace("string_", "", json_encode($output, JSON_NUMERIC_CHECK));
  
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
    $date = date_create_from_format("M j, Y", $string, new DateTimeZone("Etc/GMT+8"))->setTimeZone(new DateTimeZone("Etc/GMT"));
    return $date;
  }
}
?>