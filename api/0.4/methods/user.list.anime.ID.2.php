<?php
/*

Add or update a new anime in the user's list.
Will return 200 if updating, 201 if adding.

Method: POST
        /user/list/anime/:id
Authentication: HTTP Basic Auth with MAL Credentials.
Parameters:
  - None.
Data:
  - status: Status of the anime, numerical or 'watching', 'completed', 'on_hold', 'dropped', or 'plan_to_watch'
  - rewatching: Rewatching right now (boolean, true/false)
  - episodes: Watched episodes (integer)
  - score: Score, 1-10, or empty to set as default (nothing)
  - start_date: YYYYMMDD format date (-- if unknown, example: 20160316, 2017--07)
  - end_date: YYYYMMDD format date (-- if unknown)
  - tags: Tags, separated by comma
  - priority: Priority in list, 'low', 'medium' or 'high'
  - storage: Storage type, 'hard_drive', 'dvd_cd', 'none', 'retail_dvd', 'vhs', 'external_hd', 'nas', 'bluray', or empty to set as default (nothing)
  - storage_value: Storage value, whatver GB or something (integer)
  - rewatch_times: Total rewatched times (integer)
  - rewatch_value: Value for rewatching, 'verylow', 'low', 'medium', 'high', 'veryhigh', or empty to set as default (nothing)
  - comments: Comments, BBCode (string)

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
  // [+] --------------VALIDATE REQUEST---------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $submit_url;
  
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
  
  $input = file_get_contents("php://input");

  $json = json_decode($input, true); // true parameter makes it return array and not stdClass
  if(json_last_error() != JSON_ERROR_NONE) {
    echo json_encode(array(
      "message" => "Not valid JSON object."
    ));
    http_response_code(400);
    return;
  }
  
  $exists = true;
  
  $curl = curl_init();
  $submit_url = "https://myanimelist.net/ownlist/anime/" . $_GET['id'] . "/edit";
  curl_setopt($curl, CURLOPT_URL, $submit_url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_COOKIE, $MALsession['cookie_string']);
  curl_setopt($curl, CURLOPT_HEADER, true);
  $redirect_response = curl_exec($curl);
  if(!$redirect_response) {
    echo json_encode(array(
      "message" => "MAL is offline."
    ));
    http_response_code(404);
    return;
  }
  if(curl_getinfo($curl, CURLINFO_HTTP_CODE) === 303) {
    // It doesn't exist in the list -> either it doesn't exist or it's just not yet added
    $exists = false;
    $curl = curl_init();
    $submit_url = "https://myanimelist.net/ownlist/anime/add?selected_series_id=" . $_GET['id'];
    curl_setopt($curl, CURLOPT_URL, $submit_url); // Curl to check if it exists
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_COOKIE, $MALsession['cookie_string']);
    $redirect_response = curl_exec($curl);
    if(!$redirect_response) {
      echo json_encode(array(
        "message" => "MAL is offline."
      ));
      http_response_code(404);
      return;
    }
    $html = str_get_html($redirect_response);
    if(!is_object($html)) {
     echo json_encode(array(
        "message" => "The code for MAL is not valid HTML markup."
      ));
      http_response_code(500);
      return;
    }
    if($html->find(".badresult", 0)) {
      echo json_encode(array(
        "message" => $html->find(".badresult strong", 0)->innertext
      ));
      http_response_code(404);
      return;
    }
    
  } else {
    // It already exists in the list -> update
    $html = str_get_html($redirect_response);
    if(!is_object($html)) {
     echo json_encode(array(
        "message" => "The code for MAL is not valid HTML markup."
      ));
      http_response_code(500);
      return;
    }
  }
  
  $anime_id = $html->find("#anime_id", 0)->value;
  $aeps = $html->find("#anime_num_episodes", 0)->value;
  $astatus = $html->find("#anime_airing_status", 0)->value;
  
  if(!isset($anime_id) || !isset($aeps) || !isset($astatus)) {
    echo json_encode(array(
      "message"=> "The code for MAL appears to have changed."
    ));
    http_response_code(500);
    return;
  }
  
  curl_close($curl);
  
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ----------------SEND REQUEST------------------ [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $html->find("#add_anime_status option[selected]", 0) && $html->find("#add_anime_status option[selected]", 0)->value != "" ? $status = $html->find("#add_anime_status option[selected]", 0)->value : $status = ""; // Selected or nothing
  $html->find("#add_anime_is_rewatching", 0)->checked ? $rewatching = "1" : $rewatching = "0"; // checked=checked or nothing
  $episodes = $html->find("#add_anime_num_watched_episodes", 0)->value; // 0 or episode number
  $html->find("#add_anime_score option[selected]", 0) && $html->find("#add_anime_score option[selected]", 0)->value != "" ? $score = $html->find("#add_anime_score option[selected]", 0)->value : $score = ""; // Selected or nothing
  $html->find("#add_anime_start_date_month option[selected]", 0) && $html->find("#add_anime_start_date_month option[selected]", 0)->value != "" ? $start_date_month = $html->find("#add_anime_start_date_month option[selected]", 0)->value : $start_date_month = ""; // Selected (int) or nothing
  $html->find("#add_anime_start_date_day option[selected]", 0) && $html->find("#add_anime_start_date_day option[selected]", 0)->value != "" ? $start_date_day = $html->find("#add_anime_start_date_day option[selected]", 0)->value : $start_date_day = ""; // Selected (int) or nothing
  $html->find("#add_anime_start_date_year option[selected]", 0) && $html->find("#add_anime_start_date_year option[selected]", 0)->value != "" ? $start_date_year = $html->find("#add_anime_start_date_year option[selected]", 0)->value : $start_date_year = ""; // Selected (int) or nothing
  $html->find("#add_anime_finish_date_month option[selected]", 0) && $html->find("#add_anime_finish_date_month option[selected]", 0)->value != "" ? $end_date_month = $html->find("#add_anime_finish_date_month option[selected]", 0)->value : $end_date_month = ""; // Selected (int) or nothing
  $html->find("#add_anime_finish_date_day option[selected]", 0) && $html->find("#add_anime_finish_date_day option[selected]", 0)->value != "" ? $end_date_day = $html->find("#add_anime_finish_date_day option[selected]", 0)->value : $end_date_day = ""; // Selected (int) or nothing
  $html->find("#add_anime_finish_date_year option[selected]", 0) && $html->find("#add_anime_finish_date_year option[selected]", 0)->value != "" ? $end_date_year = $html->find("#add_anime_finish_date_year option[selected]", 0)->value : $end_date_year = ""; // Selected (int) or nothing
  $tags = html_entity_decode($html->find("#add_anime_tags", 0)->innertext); // empty or something  -  Decode quotes and all that
  $html->find("#add_anime_priority option[selected]", 0) ? $priority = $html->find("#add_anime_priority option[selected]", 0)->value : $priority = "0"; // There is no empty situation, 0 is the default.
  $html->find("#add_anime_storage_type option[selected]", 0) && $html->find("#add_anime_storage_type option[selected]", 0)->value != "" ? $storage = $html->find("#add_anime_storage_type option[selected]", 0)->value : $storage = "";
  $storage_value = $html->find("#add_anime_storage_value", 0)->value;
  $rewatch_times = $html->find("#add_anime_num_watched_times", 0)->value; // 0 or something
  $html->find("#add_anime_rewatch_value option[selected]", 0) && $html->find("#add_anime_rewatch_value option[selected]", 0)->value != "" ? $rewatch_value = $html->find("#add_anime_rewatch_value option[selected]", 0)->value : $rewatch_value = "";
  $comments = html_entity_decode($html->find("#add_anime_comments", 0)->innertext, ENT_QUOTES); // Decode quotes and all that
  
  if(!isset($json['status']) || empty($json['status'])) {
    if(!$exists) {
      echo json_encode(array(
        "message" => "JSON key 'status' is not defined."
      ));
      http_response_code(400);
      return;
    }
  } else {
    if(is_numeric($json['status'])) {
      if($json['status'] !== "1" && $json['status'] !== "2" && $json['status'] !== "3" && $json['status'] !== "4" && $json['status'] !== "6") {
        echo json_encode(array(
          "message" => "JSON key 'status' contains an invalid value."
        ));
        http_response_code(400);
        return;
      } else {
        $status = $json['status'];
      }
    } else {
      switch(strtolower($json['status'])) {
        case "watching":
          $status = 1;
          break;
        case "completed":
          $status = 2;
          break;
        case "on_hold":
          $status = 3;
          break;
        case "dropped":
          $status = 4;
          break;
        case "plan_to_watch":
          $status = 6;
          break;
        default:
          echo json_encode(array(
            "message" => "JSON key 'status' contains an invalid value."
          ));
          http_response_code(400);
          return;
      }
    }
  }
  
  if(isset($json['rewatching']) && !empty($json['episodes'])) {
    $rewatching = (int)filter_var(strtolower($json['rewatching']), FILTER_VALIDATE_BOOLEAN);
    echo $rewatching;
    /*switch(strtolower($json['rewatching'])) {
      case "true":
        $rewatching = "1";
        break;
      case "false":
        $rewatching = "0";
        break;
      default:
        echo json_encode(array(
          "message" => "JSON key 'rewatching' contains an invalid value."
        ));
        http_response_code(400);
        return;
    }*/
  }
  
  if(isset($json['episodes']) && !empty($json['episodes'])) {
    if(!is_numeric($json['episodes'])) {
      echo json_encode(array(
        "message" => "JSON key 'episodes' contains an invalid value."
      ));
      http_response_code(400);
      return;
    }
    if($aeps !== "0" && $json['episodes'] > $aeps) {
      echo json_encode(array(
        "message" => "JSON key 'episodes' contains an invalid value."
      ));
      http_response_code(400);
      return;
    }
    $episodes = $json['episodes'];
  }
  
  if(isset($json['score'])) {
    if($json['score'] == "" || $json['score'] == "0" || $json['score'] == null) {
      // Set it as 0
      $score = "";
    } else {
      if(!is_numeric($json['score'])) {
        echo json_encode(array(
          "message" => "JSON key 'score' contains an invalid value."
        ));
        http_response_code(400);
        return;
      }
      $json['score'] = round($json['score']);
      if($json['score'] > 10 || $json['score'] < 1) {
        echo json_encode(array(
          "message" => "JSON key 'score' contains an invalid value."
        ));
        http_response_code(400);
        return;
      }
      $score = $json['score'];
    }
  }
  
  if(isset($json['start_date']) && !empty($json['start_date'])) {
    // $json['start_date'] == "20160131" || "----0131"
    if(strlen($json['start_date']) != 8) {
      echo json_encode(array(
        "message" => "JSON key 'start_date' contains an invalid value."
      ));
      http_response_code(400);
      return;
    }
    if(is_numeric(substr($json['start_date'], 0, 4))) {
      $start_date_year = (int)substr($json['start_date'], 0, 4);
    } else {
      $start_date_year = "";
    }
    if(is_numeric(substr($json['start_date'], 4, 2))) {
      $start_date_month = (int)substr($json['start_date'], 4, 2);
    } else {
      $start_date_month = "";
    }
    if(is_numeric(substr($json['start_date'], 6, 2))) {
      $start_date_day = (int)substr($json['start_date'], 6, 2);
    } else {
      $start_date_day = "";
    }
  }
  
  if(isset($json['end_date']) && !empty($json['end_date'])) {
    // $json['start_date'] == "20160131" || "----0131"
    if(strlen($json['end_date']) != 8) {
      echo json_encode(array(
        "message" => "JSON key 'end_date' contains an invalid value."
      ));
      http_response_code(400);
      return;
    }
    if(is_numeric(substr($json['end_date'], 0, 4))) {
      $end_date_year = (int)substr($json['end_date'], 0, 4);
    } else {
      $end_date_year = "";
    }
    if(is_numeric(substr($json['end_date'], 4, 2))) {
      $end_date_month = (int)substr($json['end_date'], 4, 2);
    } else {
      $end_date_month = "";
    }
    if(is_numeric(substr($json['end_date'], 6, 2))) {
      $end_date_day = (int)substr($json['end_date'], 6, 2);
    } else {
      $end_date_day = "";
    }
  }
  
  if(isset($json['tags']) && !empty($json['tags'])) {
    $tags = $json['tags'];
  }
  
  if(isset($json['priority']) && !empty($json['priority'])) {
    switch(strtolower($json['priority'])) {
      case "low":
        $priority = "0";
        break;
      case "medium":
        $priority = "1";
        break;
      case "high":
        $priority = "2";
        break;
      default:
        echo json_encode(array(
          "message" => "JSON key 'priority' contains an invalid value."
        ));
        http_response_code(400);
        return;
    }
  }
  
  if(isset($json['storage'])) {
    if($json['storage'] == "") {
      // Set to default
      $storage = "";
    } else {
      switch(strtolower($json['storage'])) {
        case "hard_drive":
          $storage = "1";
          break;
        case "dvd_cd":
          $storage = "2";
          break;
        case "none":
          $storage = "3";
          break;
        case "retail_dvd":
          $storage = "4";
          break;
        case "vhs":
          $storage = "5";
          break;
        case "external_hd":
          $storage = "6";
          break;
        case "nas":
          $storage = "7";
          break;
        case "bluray":
          $storage = "8";
          break;
        default:
          echo json_encode(array(
            "message" => "JSON key 'storage' contains an invalid value."
          ));
          http_response_code(400);
          return;
      }
    }
  }
  
  if(isset($json['storage_value']) && !empty($json['storage_value'])) {
    if(!is_numeric($json['storage_value'])) {
      echo json_encode(array(
        "message" => "JSON key 'storage_value' contains an invalid value."
      ));
      http_response_code(400);
      return;
    }
    $storage_value = $json['storage_value'];
  }
  
  if(isset($json['rewatch_times']) && !empty($json['rewatch_times'])) {
    if(!is_numeric($json['rewatch_times'])) {
      echo json_encode(array(
        "message" => "JSON key 'rewatch_times' contains an invalid value."
      ));
      http_response_code(400);
      return;
    }
    $rewatch_times = $json['rewatch_times'];
  }
  
  if(isset($json['rewatch_value'])) {
    if($json['rewatch_value'] == "") {
      // Set to default
      $rewatch_value = "";
    } else {
      switch(strtolower($json['rewatch_value'])) {
        case "verylow":
          $rewatch_value = "1";
          break;
        case "low":
          $rewatch_value = "2";
          break;
        case "medium":
          $rewatch_value = "3";
          break;
        case "high":
          $rewatch_value = "4";
          break;
        case "veryhigh":
          $rewatch_value = "5";
          break;
        default:
          echo json_encode(array(
            "message" => "JSON key 'storage' contains an invalid value."
          ));
          http_response_code(400);
          return;
      }
    }
  }
  
  if(isset($json['comments']) && !empty($json['comments'])) {
    $comments = $json['comments'];
  }
  
  
  $post_fields = array(
    "anime_id" => $anime_id,
    "aeps" => $aeps,
    "astatus" => $astatus,
    "add_anime" => array(
      "status" => $status,
      "is_rewatching" => $rewatching,
      "num_watched_episodes" => $episodes,
      "score" => $score,
      "start_date" => array(
        "month" => $start_date_month,
        "day" => $start_date_day,
        "year" => $start_date_year
      ),
      "finish_date" => array(
        "month" => $end_date_month,
        "day" => $end_date_day,
        "year" => $end_date_year
      ),
      "tags" => $tags,
      "priority" => $priority,
      "storage_type" => $storage,
      "storage_value" => $storage_value,
      "num_watched_times" => $rewatch_times,
      "comments" => $comments,
      "is_asked_to_discuss" => "0",
      "sns_post_type" => "0",
    ),
    "submitIt" => "0",
    "csrf_token" => $MALsession['csrf_token']
  );
  
  if($rewatching) $post_fields['add_anime']['rewatch_value'] = $rewatching; // It doesn't exist in the query if it's not true.

  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $submit_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_COOKIE, $MALsession['cookie_string'] . "; anime_update_advanced=1");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
  $response = curl_exec($ch);
  
  $curlerror = curl_error($ch); // Seperate in two lines because:
  if(!empty($curlerror)) { // http://stackoverflow.com/questions/17139264/cant-use-function-return-value-in-write-context
    echo json_encode(array(
      "message" => "Could not connect to MAL."
    ));
    http_response_code(500);
    return;
  }
  curl_close($ch);
  $html = str_get_html($response);
  
  if($html->find(".badresult", 0)) {
    echo json_encode(array(
      "mesage" => $html->find(".badresult strong", 0)->innertext
    ));
    http_response_code(500);
    return;
  }
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $verb = "added";
  if($exists) $verb = "updated";
  echo json_encode(array(
    "message" => "Successfully " . $verb . " anime in list."
  ));
  if($exists) {
    http_response_code(200); // Use 200 instead of 201, because it's not 'creating' anything.
  } else {
    http_response_code(201); // Use 201 instead of 200, because we created a new entry.
  }

});
?>
