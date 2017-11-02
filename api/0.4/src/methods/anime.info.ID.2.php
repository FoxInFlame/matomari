<?php
/*

Updates information about an anime.

Method: POST
        /anime/info/:id
Authentication: HTTP Basic Auth with MAL Credentials.
Parameters:
  - None.
Data:
  -

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

call_user_func(function() {
  
  if($_SERVER['REQUEST_METHOD'] !== "POST") {
    echo json_encode(array(
      "message" => "This method must be sent by a POST request."
    ));
    http_response_code(400);
    return;
  }
  
  $parts = isset($_GET['id']) ? explode("/",$_GET['id']) : array();
  if(empty($parts)) {
    echo json_encode(array(
      "message" => "The id parameter is not defined."
    ));
    http_response_code(400);
    return;
  }
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------LOGIN--------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  require_once(dirname(__FILE__) . "/../authenticate_base.php");
  
  if(!isset($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || empty($_SERVER['PHP_AUTH_PW'])) {
    header("WWW-Authenticate: Basic realm=\"myanimelist.net\"");
    http_response_code(401);
    echo json_encode(array(
      "message" => "Authorisation Required."
    ));
    return;
  } else {
    $MALsession = getSession($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
  }


  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] -------------SEND NOTIFICATIONS--------------- [+]
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
  
  if(!isset($json['type']) || empty($json['type']) || !isset($json['value']) || empty($json['value'])) {
    echo json_encode(array(
      "message" => "One or more values missing in JSON."
    ));
    http_response_code(400);
    return;
  }
  
  $input_field = array();
  switch($json['type']) {
    case "synopsis":
      // synopsis: "Monkey D Luffy etc whatever happened to go on a voyage on the sea...blah blah."
      if(!isset($json['value']['synopsis'])) {
        echo json_encode(array(
          "message" => "One or more values missing in JSON."
        ));
        http_response_code(400);
        return;
      }
      $input_field['synopsis'] = $json['value']['synopsis'];
      break;
    case "background":
      // background: "Won the Oscar awards for the best coded API"
      if(!isset($json['value']['background'])) {
        echo json_encode(array(
          "message" => "One or more values missing in JSON."
        ));
        http_response_code(400);
        return;
      }
      !isset($json['value']['background']) ?: $input_field['background'] = $json['value']['background'];
      break;
    case "alternative_titles":
      // synonyms: "Synonym1; Syonym2"
      // english_title: "Sinonaime"
      // japanese_title: "東雲"
      if(!isset($json['value']['synonyms']) || !isset($json['value']['english_title']) || !isset($json['value']['japanese_title'])) {
        echo json_encode(array(
          "message" => "One or more values missing in JSON."
        ));
        http_response_code(400);
        return;
      }
      !isset($json['value']['synonyms']) ?: $input_field['synonyms'] = $json['value']['synonyms'];
      !isset($json['value']['english_title']) ?: $input_field['english_title'] = $json['value']['english_title'];
      !isset($json['value']['japanese_title']) ?: $input_field['japanese_title'] = $json['value']['japanese_title'];
      break;
    case "picture":
      // image: "data:image/png;base64,asdasdiqlakI112)ASDKJ!"#kajsd9kJADSHKJHADS"
      if(!isset($json['value']['image'])) {
        echo json_encode(array(
          "message" => "One or more values missing in JSON."
        ));
        http_response_code(400);
        return;
      }
      !isset($json['value']['image']) ?: $input_field['file'] = '@' . $json['value']['image'] . ';filename=Image';
      break;
    case "airing_dates":
      // start_date: "20161230"
      // end_date: "20170115"
      if(!isset($json['value']['start_date']) || !isset($json['value']['start_date']) || !isset($json['value']['start_date']) || !isset($json['value']['start_date'])) {
        echo json_encode(array(
          "message" => "One or more values missing in JSON."
        ));
        http_response_code(400);
        return;
      }
      if(isset($json['value']['start_date']) && strlen($json['value']['start_date']) == 8 && is_numeric($json['value']['start_date'])) {
        $input_field['series_start_month'] = substr(substr($json['value']['start_date'], 0, -2), 4);
        $input_field['series_start_day'] = substr($json['value']['start_date'], 6);
        $input_field['series_start_year'] = substr($json['value']['start_date'], 0, -4);
      }
      if(isset($json['value']['end_date']) && strlen($json['value']['end_date']) == 8 && is_numeric($json['value']['end_date'])) {
        $input_field['series_end_month'] = substr(substr($json['value']['end_date'], 0, -2), 4);
        $input_field['series_end_day'] = substr($json['value']['end_date'], 6);
        $input_field['series_end_year'] = substr($json['value']['end_date'], 0, -4);
      }
      break;
    case "producers":
      // todo
      break;
    case "relations":
      // todo
      break;
    case "rating":
      if(!isset($json['value']['rating'])) {
        echo json_encode(array(
          "message" => "One or more values missing in JSON."
        ));
        http_response_code(400);
        return;
      }
      switch($json['value']['rating']) {
        case "g":
          $input_field['rating'] = "1";
          break;
        case "pg":
          $input_field['rating'] = "2";
          break;
        case "pg13":
          $input_field['rating'] = "3";
          break;
        case "r":
          $input_field['rating'] = "4";
          break;
        case "r+":
          $input_field['rating'] = "5";
          break;
        case "rx":
          $input_field['rating'] = "6";
          break;
        default:
          break;
      }
    case "duration":
      !isset($json['value']['duration']) ?: $input_field['duration'] = $json['value']['duration'];
      break;
    case "source":
      if(!isset($json['value']['source'])) {
        echo json_encode(array(
          "message" => "One or more values missing in JSON."
        ));
        http_response_code(400);
        return;
      }
      switch($json['value']['source']) {
        case "original":
          $input_field['source_type_id'] = "1";
          break;
        case "manga":
          $input_field['source_type_id'] = "2";
          break;
        case "4komamanga":
          $input_field['source_type_id'] = "3";
          break;
        case "webmanga":
          $input_field['source_type_id'] = "4";
          break;
        case "digitalmanga":
          $input_field['source_type_id'] = "5";
          break;
        case "novel":
          $input_field['source_type_id'] = "6";
          break;
        case "lightnovel":
          $input_field['source_type_id'] = "7";
          break;
        case "visualnovel":
          $input_field['source_type_id'] = "8";
          break;
        case "game":
          $input_field['source_type_id'] = "9";
          break;
        case "cardgame":
          $input_field['source_type_id'] = "10";
          break;
        case "book":
          $input_field['source_type_id'] = "11";
          break;
        case "picture":
          $input_field['source_type_id'] = "12";
          break;
        case "radio":
          $input_field['source_type_id'] = "13";
          break;
        case "music":
          $input_field['source_type_id'] = "14";
          break;
        case "other":
          $input_field['source_type_id'] = "-1";
          break;
      }
      break;
    case "broadcast":
      // todo
      break;
    default:
      return;
      break;
  }
  
  $post_fields = array(
    'csrf_token' => $MALsession['csrf_token'],
    'subsyn' => 'Submit'
  ) + $input_field;
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/dbchanges.php?aid=" . $id . "&t=" . urlencode($json['type']));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_COOKIE, $MALsession['cookie_string']);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
  $response = curl_exec($ch);
  
  list($header, $body) = explode("\r\n\r\n", $response, 2);
  
  $html = @str_get_html($body);
  $contentWrapper = $html->find("#contentWrapper", 0);
  
  if($contentWrapper->find(".badresult", 0)) {
    echo json_encode(array(
      "message" => $contentWrapper->find(".badresult", 0)->innertext
    ));
    http_response_code(400);
    return;
  }
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  header("Location: https://myanimelist.net/anime/" . substr($contentWrapper->find(".goodresult a", 0)->href, 13));
  echo json_encode(array(
    "message" => "Changes sent to moderators for review successfully.",
  ));
  http_response_code(201);
  
});