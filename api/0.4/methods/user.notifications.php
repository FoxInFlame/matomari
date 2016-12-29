<?php
/*

Shows unread notifications for a specific MAL user.

Method: GET
        /user/notifications
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

ini_set("display_errors", true);
ini_set("display_startup_errors", true);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Cache-Control: no-cache, must-revalidate");
require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");
require_once(dirname(__FILE__) . "/../class/class.notification.php");

call_user_func(function() {

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------LOGIN--------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  require(dirname(__FILE__) . "/../authenticate_base.php");
  
  if(!isset($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || empty($_SERVER['PHP_AUTH_PW'])) {
    header("WWW-Authenticate: Basic realm=\"myanimelist.net\"");
    header("HTTP/1.0 401 Unauthorized");
    echo json_encode(array(
      "error" => "Authorisation Required."
    ));
    return;
  } else {
    $MALsession = getSession($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
  }


  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------GET NOTIFICATIONS--------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/notification/all/new");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_COOKIE, $MALsession['cookie_string']);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('csrf_token' => $MALsession['csrf_token'])));
  $response = curl_exec($ch);
  
  list($header, $body) = explode("\r\n\r\n", $response, 2);
  
  $html = @str_get_html($body);
  $children = $html->find("#contentWrapper", 0)->last_child();
  
  $notificationJS = $children->innertext;
  $notificationJS_0 = substr($notificationJS, 27);
  $notificationJS_1 = explode("; window.MAL.notification.hideEmpty = false; window.MAL.notification.templates = ", $notificationJS_0);

  // JavaScript object window.MAL.notification
  $window_MAL_notification = $notificationJS_1[0];
  
  // JavaScript object window.MAL.notification.templates
  $window_MAL_notification_templates = $notificationJS_1[1];
  
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $notifications = json_decode($window_MAL_notification)->items;
  $notifications_arr = array();
  foreach($notifications as $value) {
    $notification = new Notification();
    $notification->loadJSON($value);
    array_push($notifications_arr, $notification->saveJSON());
  }
  
   // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo json_encode($notifications_arr, JSON_NUMERIC_CHECK);
  
});
?>