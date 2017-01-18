<?php
/*

Shows the favorites a MAL user has.
I personally prefer the british spelling but for consistency across the internet, I'm using the American spelling.

Method: GET
        /user/favorites/:username
Authentication: None Required.
Parameters:
  - None.
  
Method: GET
        /user/favorites
Authentication: HTTP Basic Auth with MAL Credentials.
Parameters::
  - None.
  
Method: POST/DELETE
        /user/favorites
Authentication: HTTP Basic Auth with MAL Credentials.
Data: {
  type: Anime, Manga, Character or People,
  id: ID.
}
This method cannot check if the favorite is already in the list or not.

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
  
  $mode;
  // 0 is GET /user/favorites/:username
  // 1 is GET /user/favorites
  // 2 is POST/DELETE /user/favorites
  if(isset($_GET['username']) && !empty($_GET['username'])) {
    $mode = "0";
  } else {
    if($_SERVER['REQUEST_METHOD'] === "POST" || $_SERVER['REQUEST_METHOD'] === "DELETE") {
      $mode = "2";
    } else {
      $mode = "1";
    }
  }
  
  if($mode == "0") { // GET /user/favorites/:username
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/profile/" . $_GET['username']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close();
    $html = str_get_html($response);
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
    
    $favorites_anime_arr = array();
    $favorites_manga_arr = array();
    $favorites_characters_arr = array();
    $favorites_people_arr = array();
    $html_rightside = $html->find("div#contentWrapper div.container-right", 0);
    $favorites = $html_rightside->find("div.user-favorites", 0)->children();
    foreach($favorites as $value) {
      if($value->find("h5", 0)->innertext == "Anime") {
        $favorites_anime = $value->find("ul.anime", 0);
        if(!empty($favorites_anime)) {
          foreach($favorites_anime->find("li") as $value) {
            // Add string_ to make sure PHP doesn't parse this as an integer
            array_push($favorites_anime_arr, "string_".trim(explode("/", $value->find("div", 1)->find("a", 0)->href)[4]));
          }
        }
      } else if($value->find("h5", 0)->innertext == "Manga") {
        $favorites_manga = $value->find("ul.manga", 0);
        if(!empty($favorites_manga)) {
          foreach($favorites_manga->find("li") as $value) {
            array_push($favorites_manga_arr, "string_".trim(explode("/", $value->find("div", 1)->find("a", 0)->href)[4]));
          }
        }
      } else if($value->find("h5", 0)->innertext == "Characters") {
        $favorites_characters = $value->find("ul.characters", 0);
        if(!empty($favorites_characters)) {
          foreach($favorites_characters->find("li") as $value) {
            array_push($favorites_characters_arr, "string_".trim(explode("/", $value->find("div", 1)->find("a", 0)->href)[4]));
          }
        }
      } else if($value->find("h5", 0)->innertext == "People") {
        $favorites_people = $value->find("ul.people", 0);
        if(!empty($favorites_people)) {
          foreach($favorites_people->find("li") as $value) {
            array_push($favorites_people_arr, "string_".trim(explode("/", $value->find("div", 1)->find("a", 0)->href)[4]));
          }
        }
      }
    }
    unset($value);
    $output = array(
      "favorites" => array(
        "anime" => $favorites_anime_arr,
        "manga" => $favorites_manga_arr,
        "characters" => $favorites_characters_arr,
        "people" => $favorites_people_arr
      )
    );
  } else if($mode == "1") { // GET /user/favorites
  
    require(dirname(__FILE__) . "/../authenticate_base.php");
    if(!isset($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || empty($_SERVER['PHP_AUTH_PW'])) {
      header("WWW-Authenticate: Basic realm=\"myanimelist.net\"");
      echo json_encode(array(
        "message" => "Authorisation Required."
      ));
      http_response_code(401);
      return;
    } else {
      $username = $_SERVER['PHP_AUTH_USER'];
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/profile/" . $_SERVER['PHP_AUTH_USER']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close();
    $html = str_get_html($response);
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
    
    $favorites_anime_arr = array();
    $favorites_manga_arr = array();
    $favorites_characters_arr = array();
    $favorites_people_arr = array();
    $html_rightside = $html->find("div#contentWrapper div.container-right", 0);
    $favorites = $html_rightside->find("div.user-favorites", 0)->children();
    foreach($favorites as $value) {
      if($value->find("h5", 0)->innertext == "Anime") {
        $favorites_anime = $value->find("ul.anime", 0);
        if(!empty($favorites_anime)) {
          foreach($favorites_anime->find("li") as $value) {
            // Add string_ to make sure PHP doesn't parse this as an integer
            array_push($favorites_anime_arr, "string_".trim(explode("/", $value->find("div", 1)->find("a", 0)->href)[4]));
          }
        }
      } else if($value->find("h5", 0)->innertext == "Manga") {
        $favorites_manga = $value->find("ul.manga", 0);
        if(!empty($favorites_manga)) {
          foreach($favorites_manga->find("li") as $value) {
            array_push($favorites_manga_arr, "string_".trim(explode("/", $value->find("div", 1)->find("a", 0)->href)[4]));
          }
        }
      } else if($value->find("h5", 0)->innertext == "Characters") {
        $favorites_characters = $value->find("ul.characters", 0);
        if(!empty($favorites_characters)) {
          foreach($favorites_characters->find("li") as $value) {
            array_push($favorites_characters_arr, "string_".trim(explode("/", $value->find("div", 1)->find("a", 0)->href)[4]));
          }
        }
      } else if($value->find("h5", 0)->innertext == "People") {
        $favorites_people = $value->find("ul.people", 0);
        if(!empty($favorites_people)) {
          foreach($favorites_people->find("li") as $value) {
            array_push($favorites_people_arr, "string_".trim(explode("/", $value->find("div", 1)->find("a", 0)->href)[4]));
          }
        }
      }
    }
    unset($value);
    $output = array(
      "favorites" => array(
        "anime" => $favorites_anime_arr,
        "manga" => $favorites_manga_arr,
        "characters" => $favorites_characters_arr,
        "people" => $favorites_people_arr
      )
    );
  } else if($mode == "2") { // POST/DELETE /user/favorites
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
    
    $input = file_get_contents("php://input");
  
    $json = json_decode($input, true); // true parameter makes it return array and not stdClass
    if(json_last_error() != JSON_ERROR_NONE) {
      echo json_encode(array(
        "message" => "Not valid JSON object."
      ));
      http_response_code(400);
      return;
    }
    
    if(!isset($json['type']) || empty($json['type']) || !isset($json['id']) || empty($json['id'])) {
      echo json_encode(array(
        "message" => "One or more values missing in JSON."
      ));
      http_response_code(400);
      return;
    }
    if(!is_numeric($json['id'])) {
      echo json_encode(array(
        "mesage" => "'id' value in JSON must be numerical."
      ));
      http_response_code(400);
      return;
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/favorite/" . strtolower($json['type']) . "/" . $json['id'] . ".json");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, $MALsession['cookie_string'] . "anime_update_advanced=1");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $_SERVER['REQUEST_METHOD']);
    $postFields = http_build_query(array(
      "csrf_token" => $MALsession['csrf_token']
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Accept: application/json",
      "Content-Type: application/x-www-form-urlencoded",
      "Content-Length: " . strlen($postFields)
    ));
    $response = curl_exec($ch);
    $response_json = json_decode($response);
    switch(curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
      case 400:
        $output = array(
          "message" => "Only maxmimum of " . $response_json->max_favs . " favorites allowed."
        );
        if(!$response_json->is_supporter) {
          $output['message'] .= " Become a supporter to get more.";
        }
        http_response_code(403);
        break;
      case 401:
        $output = array(
          "message" => "You are not authorised to do this action."
        );
        http_response_code(403);
        break;
      case 404:
        if($json['type'] !== "anime" && $json['type'] !== "manga" && $json['type'] !== "character" && $json['type'] !== "people") {
          $output = array(
            "message" => "Not valid 'type' parameter."
          );
          http_response_code(400);
        } else {
          $output = array(
            "message" => "MAL is offline or their code changed."
          );
          http_response_code(404);
        }
        break;
      case 200:
        if($_SERVER['REQUEST_METHOD'] == "POST") {
          $output = array(
            "message" => "Successfully added " . strtolower($json['type']) . $json['id'] . " to favorites.",
            "time" => date_create_from_format("U", json_decode($response)->created_at)->format("c")
          );
          http_response_code(200);
        } else if($_SERVER['REQUEST_METHOD'] == "DELETE") {
          $output = array(
            "message" => "Successfully deleted " . strtolower($json['type']) . $json['id'] . " from favorites.",
            "time" => date_create_from_format("U", json_decode($response)->created_at)->format("c")
          );
          http_response_code(200);
        }
        break;
      default:
        if(!$response) {
          $output = array(
            "message" => "An unknown error occured."
          );
        } else {
          $output = array(
            "message" => $response_json->errors[0]->message
          );
        }
        http_response_code(500);
        break;
    }
    curl_close($ch);
  }


  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  // Remove string_ after parse
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo json_encode($output, JSON_NUMERIC_CHECK);
  // http_response_code() is set in each case and not here
  
});
?>