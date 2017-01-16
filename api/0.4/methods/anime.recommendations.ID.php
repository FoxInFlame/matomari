<?php
/*

Shows recommendations from an anime.

Method: GET
        /anime/recommendations/:id
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
//header("Content-Type: application/json");
header("Cache-Control: no-cache, must-revalidate");
require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------GETTING THE VALUES-------------- [+]
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
  curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/anime/" . $id . "/FoxInFlameIsAwesome/userrecs");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response_string = curl_exec($ch);
  curl_close($ch);
  
  if(!$response_string) {
    echo json_encode(array(
      "message" => "MAL is offline, or their code changed."
    ));
    http_response_code(404);
    return;
  }
  
  $html = str_get_html($response_string);
  
  //    [+] ============================================== [+]
  //    [+] --------------GETTING THE VALUES-------------- [+]
  //    [+] ============================================== [+]

  $contentWrapper = $html->find("#contentWrapper", 0);
  
  //echo $html->outertext;
  
  $str = $html->find("#content table tbody tr", 0);
  $recommendations = $contentWrapper->find("#content table tbody tr", 0)->children(1)->children(0)->children();
  
  $recommendations_arr = array();
  foreach($recommendations as $recommendation) {
    if(strpos($recommendation->class, "borderClass") === false) continue;
    $to = $recommendation->find("table td", 0);
    $to_id = explode("/", $to->find(".picSurround a", 0)->href)[2];
    $to_picture = $to->find(".picSurround a img", 0)->{'data-srcset'};
    $to_picture_1x = explode(" 1x,", $to_picture)[0];
    $to_picture_2x = substr(explode(" 1x,", $to_picture)[1], 0, -3);
    $to_title = $recommendation->find("table td a strong", 0)->innertext;
    $reason = explode("\r\n", substr($recommendation->find("table td", 1)->children(2)->find("div", 0)->plaintext, 0, -6));
    $reason = htmlspecialchars_decode(html_entity_decode(join("<br>", $reason), 0, "UTF-8"));
    $author = trim($recommendation->find("table td", 1)->children(2)->children(1)->find("a", 1)->innertext);
    $other = $recommendation->find("table td a strong", 1) ? $recommendation->find("table td a strong", 1)->innertext : "0";
    $other_arr = array();
    $other_elem = $recommendation->find("[id^=simaid]", 0);
    if($other_elem) {
      foreach($other_elem->find(".borderClass") as $otherrec) {
        array_push($other_arr, array(
          "reason" => htmlspecialchars_decode(html_entity_decode(join("<br>", explode("\r\n", substr($otherrec->find(".spaceit_pad", 0)->plaintext, 0, -6))), 0, "UTF-8")),
          "author" => $otherrec->find(".spaceit_pad", 1)->find("a", 1)->innertext
        ));
      }
    }
    array_push($recommendations_arr, array(
      "to" => array(
        "id" => $to_id,
        "image_1x" => $to_picture_1x,
        "image_2x" => $to_picture_2x,
        "title" => $to_title
      ),
      "reason" => $reason,
      "author" => $author,
      "other_reviews" => array(
        "total" => $other,
        "items" => $other_arr
      )
    ));
  }

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "items" => $recommendations_arr
  );
  
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo json_encode($output, JSON_NUMERIC_CHECK);
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
    $date = date_create_from_format("M j, Y", $string, new DateTimeZone("Etc/GMT+8"))->setTimeZone(new DateTimeZone("Etc/GMT"));
    return $date;
  }
}
?>