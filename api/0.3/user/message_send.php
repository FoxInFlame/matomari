<?php
/*

Send a message to a specific MAL user.

Method: POST
        /api/user/message/send/.(json|xml)
Authentication: HTTP Basic Auth with MAL Credentials.
Supported Filetypes: json, xml
Parameters:
  - None.
Data:
  - to: USERNAME
  - subject: SUBJECT
  - message: escaped MESSAGE (Be sure to escape, or it will not recognize it.)

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
require(dirname(__FILE__) . "/../SimpleHtmlDOM.php");

call_user_func(function() {

  if($_SERVER['REQUEST_METHOD'] !== "POST") {
    echo json_encode(array(
      "error" => "This method must be sent by a POST request."
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
    http_response_code(401);
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

  $input = file_get_contents("php://input");

  $json = json_decode($input, true); // true parameter makes it return array and not stdClass
  if(json_last_error() != JSON_ERROR_NONE) {
    echo json_encode(array(
      "error" => "Not valid JSON object."
    ));
    http_response_code(400);
    return;
  }
  
  if(!isset($json['to']) || empty($json['to']) || !isset($json['subject']) || empty($json['subject']) || !isset($json['message']) || empty($json['message'])) {
    echo json_encode(array(
      "error" => "One or more values missing in JSON."
    ));
    http_response_code(400);
    return;
  }
  
  $post_fields = array(
    'csrf_token' => $MALsession['csrf_token'],
    'subject' => $json['subject'],
    'message' => $json['message'],
    'sendmessage' => 'Send Message',
    'preview' => 'Preview'
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/mymessages.php?go=send&toname=" . urlencode($json['to']));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_COOKIE, $MALsession['cookie_string']);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
  $response = curl_exec($ch);
  
  list($header, $body) = explode("\r\n\r\n", $response, 2);
  
  $html = @str_get_html($body);
  $contentWrapper = $html->find("#contentWrapper", 0);
  
  if($contentWrapper->find(".private-message-content .badresult", 0)) {
    echo json_encode(array(
      "error" => $contentWrapper->find(".private-message-content .badresult", 0)->innertext
    ));
    http_response_code(400);
    return;
  }
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  echo json_encode(array(
    "success" => "Message sent to " . urlencode($json['to']) . " successfully."
  ));
  http_response_code(201);

});
?>