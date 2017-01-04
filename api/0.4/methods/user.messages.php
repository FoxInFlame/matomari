<?php
/*

Shows messages for a specific MAL user.

Method: GET
        /user/messages
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
  
  require(dirname(__FILE__) . "/../authenticate_base.php");
  
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
  // [+] -----------------GET MESSAGES----------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]

  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : "1";
  $show = ($page - 1) * 20;
  $show_param = "?show=" . $show;
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/mymessages.php" . $show_param);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_COOKIE, $MALsession['cookie_string']);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('csrf_token' => $MALsession['csrf_token'])));
  $response = curl_exec($ch);
  
  list($header, $body) = explode("\r\n\r\n", $response, 2);
  
  $html = @str_get_html($body);
  $contentWrapper = $html->find("#contentWrapper", 0);
  
  $total_messages = str_replace(",", "", explode(" ", trim($contentWrapper->find(".total_messages .di-ib", 0)->innertext))[2]);
  
  $messages = $contentWrapper->find("form[name=messageForm] .message-container .message");
  $messages_arr = array();
  
  foreach($messages as $message) {
    if(strpos($message->class, "unread") !== false) {
      // Unread
      $read = false;
    } else {
      // Read
      $read = true;
    }
    $id = substr($message->find(".mym_subject a", 0)->href, 12);
    $action_id = substr($message->id, 8);
    $thread_id = explode("&", explode("threadid=", $message->find(".mym_option .mym_actions a", 0)->href)[1])[0];
    $sender = $message->find(".mym_user a", 0)->innertext;
    $sender_url = "https://myanimelist.net" . $message->find(".mym_user a", 0)->href;
    $text = $message->find(".mym_subject a", 0)->plaintext;
    $body = $message->find(".mym_subject a .text", 0)->innertext;
    $subject = substr(str_lreplace($body, "", $text), 0, -4);
    $relative_date = $message->find(".mym_option .mym_date", 0)->innertext;
    $absolute_date = getAbsoluteTimeGMT($relative_date)->format("c");
    array_push($messages_arr, array(
      "id" => $id,
      "action_id" => $action_id,
      "thread_id" => $thread_id,
      "read" => $read,
      "sender" => array(
        "username" => $sender,
        "url" => $sender_url
      ),
      "subject" => $subject,
      "body_preview" => $body,
      "time" => $absolute_date
    ));
  }
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "total" => $total_messages,
    "messages" => $messages_arr
  );
  
  // Remove string_ after parse
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo str_replace("string_", "", json_encode($output, JSON_NUMERIC_CHECK));
  http_response_code(200);
  
});

function str_lreplace($search, $replace, $subject) {
  $pos = strrpos($subject, $search);

  if($pos !== false) {
    $subject = substr_replace($subject, $replace, $pos, strlen($search));
  }
  return $subject;
}

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