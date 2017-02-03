<?php
/*

Do an action to a notification for a specific MAL user.

Method: POST
        /user/notifications
Authentication: HTTP Basic Auth with MAL Credentials.
Data: {
  id: Comma separated notification ids
  action: "read" to mark as read, "accept_friend" to accept a friend request, "deny_friend" to deny a friend request
}
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

  if($_SERVER['REQUEST_METHOD'] !== "POST") {
    echo json_encode(array(
      "message" => "This request must be sent by a POST request."
    ));
    http_response_code(405);
    return;
  }

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
  // [+] --------------GET NOTIFICATIONS--------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]

  $input = file_get_contents("php://input");
  $json = json_decode($input, true); // true parameter makes it return array and not stdClass
  if(json_last_error() != JSON_ERROR_NONE) {
    echo json_encode(array(
      "message" => "Not valid JSON object."
    ));
    http_response_code(400);
    return;
  }

  if(!isset($json['id']) || empty($json['id']) || !isset($json['action']) || empty($json['action'])) {
    echo json_encode(array(
      "message" => "One or more values missing in JSON."
    ));
    http_response_code(400);
    return;
  }

  $ids = explode(",", $json['id']);
  $ids_arr = array();
  foreach($ids as $id) {
    if(!is_numeric(trim($id))) {
      echo json_encode(array(
        "message" => "One or more ids are not numerical."
      ));
      http_response_code(400);
      return;
    }
    array_push($ids_arr, trim($id));
  }

  switch(strtolower($json['action'])) {
    case "read":
      action($ids_arr, "https://myanimelist.net/notification/api/check-items-as-read.json", "Successfully marked [number] notification(s) as read.", "Could not mark [number] notification(s) as read.");
      break;
    case "accept_friend":
      action($ids_arr, "https://myanimelist.net/notification/api/accept-friend-request.json", "Successfully marked [number] notification(s) as read.", "Could not mark [number] notification(s) as read.");
      break;
    case "deny_friend":
      action($ids_arr, "https://myanimelist.net/notification/api/deny-friend-request.json", "Successfully marked [number] notification(s) as read.", "Could not mark [number] notification(s) as read.");
      break;
  }
  function action($ids, $url, $successMessage, $failMessage) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/notification/api/check-items-as-read.json");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, $MALsession['cookie_string']);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Content-Type: application/json"
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
      "csrf_token" => $MALsession['csrf_token'],
      "notification_ids" => $ids
    )));
    $response = curl_exec($ch);

    if(curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200) {
      $output = json_encode(array(
        "message" => str_replace("[number]", count($ids_arr), $successMessage)
      ));
    } else {
      echo json_encode(array(
        "message" => str_replace("[number]", count($ids_arr), $failMessage)
      ));
      http_response_code(502);
      return;
    }
  }

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]

  echo $output;
  http_response_code(200);

});
