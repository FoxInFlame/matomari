<?php
/*

Shows unread notifications for a specific MAL user.

Method: GET
        /user/notifications
Authentication: HTTP Basic Auth with MAL Credentials.
Parameters:
  - filter: [Optional] All the filters available on MAL notifications (user_mention, payment, forum_quote, etc) (Defaults to no filter)

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
require_once(dirname(__FILE__) . "/../class/class.notification.php");

call_user_func(function() {

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------LOGIN--------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  require_once(dirname(__FILE__) . "/../authenticate_base.php");
  
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
  // [+] --------------GET NOTIFICATIONS--------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]

  $filter = isset($_GET['filter']) && !empty($_GET['filter']) ? $_GET['filter'] : "";
  $filter_param = "/all/new";
  switch(strtolower($filter)) {
    case "friend_requests":
      $filter_param = "/friend_request";
      break;
    case "profile_comment":
      $filter_param = "/profile_comment";
      break;
    case "blog_comment":
      $filter_param = "/blog_comment";
      break;
    case "forum_quote":
      $filter_param = "/forum_quote";
      break;
    case "user_mention":
      $filter_param = "/user_mention";
      break;
    case "watched_topic_message":
      $filter_param = "/watched_topic_message";
      break;
    case "club_mass_message":
      $filter_param = "/club_mass_message";
      break;
    case "related_anime":
      $filter_param = "/related_anime";
      break;
    case "airing_anime":
      $filter_param = "/on_air";
      break;
    case "payment":
      $filter_param = "/payment";
      break;
    default:
      $filter_param = "/all/new";
      break;
  }
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/notification" . $filter_param);
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
  $notifications_history = json_decode($window_MAL_notification)->historyItems ? json_decode($window_MAL_notification)->historyItems : array();
  $notifications_arr = array();
  $notifications_history_arr = array();
  foreach($notifications as $value) {
    $notification = new Notification();
    $notification->loadJSON($value);
    array_push($notifications_arr, $notification->saveJSON());
  }
  foreach($notifications_history as $value) {
    $notification = new Notification();
    $notification->loadJSON($value);
    array_push($notifications_history_arr, $notification->saveJSON());
  }
  
  $output = array(
    "new" => $notifications_arr,
    "read" => $notifications_history_arr
  );
   // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo json_encode($output, JSON_NUMERIC_CHECK);
  http_response_code(200);
  
});
?>