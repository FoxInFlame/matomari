<?php
/*

Set messages as read or unread for a specific MAL user.
Note: This script can not verify if a message exists or not.

Method: POST
        /user/messages
Authentication: HTTP Basic Auth with MAL Credentials.
Parameters:
  - None.
Data:
  - id: Message 'action_id's, seperated by comma if multiple
  - action: Action to be done to the messages, delete, unread or read

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

  if($_SERVER['REQUEST_METHOD'] !== "POST") {
    echo json_encode(array(
      "message" => "This request must be sent by a POST request."
    ));
    http_response_code(400);
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
  // [+] --------------VALIDATE REQUEST---------------- [+]
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
  
  if(!in_array(strtolower($json['action']), array("read", "unread", "delete"), true)) {
    echo json_encode(array(
      "message" => "Invalid action."
    ));
    http_response_code(400);
    return;
  }
  
  $action_ids = explode(",", $json['id']);
  $action_ids_arr = array();
  foreach($action_ids as $action_id) {
    if(!is_numeric(trim($action_id))) {
      echo json_encode(array(
        "message" => "One or more ids are not numerical."
      ));
      http_response_code(400);
      return;
    }
    array_push($action_ids_arr, "0-" . trim($action_id));
  }
  
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ----------------SEND REQUEST------------------ [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $post_fields = array(
    'csrf_token' => $MALsession['csrf_token'],
    'checkSelector' => $json['action'],
    'msg' => $action_ids_arr
  );

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/mymessages.php");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_COOKIE, $MALsession['cookie_string']);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
  $response = curl_exec($ch);
  
  $curlerror = curl_error($ch); // Seperate in two lines because:
  if(!empty($curlerror)) { // http://stackoverflow.com/questions/17139264/cant-use-function-return-value-in-write-context
    echo json_encode(array(
      "message" => "Could not connect to MAL successfully."
    ));
    http_response_code(500);
    return;
  }
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  if($json['action'] == "delete") {
    $json['action'] = $json['action'] . "d";
  }
  echo json_encode(array(
    "message" => "Marked " . count($action_ids_arr) . " message(s) as " . $json['action'] . "."
  ));
  http_response_code(200); // Use 200 instead of 201, because it's not 'creating' anything.

});
?>