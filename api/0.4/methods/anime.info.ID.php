<?php
/*

Shows detailed information about an anime.

This method is cached for a week. Set the nocache parameter to true to use a fresh version (slower).
Method: GET
        /anime/info/:id
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
header("Content-Type: application/json");
header("Cache-Control: no-cache, must-revalidate");
require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");
require_once(dirname(__FILE__) . "/../class/class.anime.php");
require_once(dirname(__FILE__) . "/../class/class.cache.php");

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------GETTING THE VALUES-------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $parts = isset($_GET['id']) ? explode("/",$_GET['id']) : array();
  if(empty($parts)) {
    echo json_encode(array(
      "message" => "The id parameter is not defined."
    ));
    http_response_code(400);
    return;
  }
  if(!is_numeric($parts[0])) {
    echo json_encode(array(
      "message" => "Specified anime id is not a number."
    ));
    http_response_code(400);
    return;
  }
  
  $url = "https://myanimelist.net/anime/" . $parts[0];
  $data = new Data();
  
  if($data->getCache($url)) {
    $html = str_get_html($data->data);
  } else {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if(!$response) {
       echo json_encode(array(
        "message" => "MAL is offline."
      ));
      http_response_code(404);
      return;
    }
    if(curl_getinfo($ch, CURLINFO_HTTP_CODE) === 429) {
      echo json_encode(array(
        "message" => "Too many requests."
      ));
      http_response_code(429);
      return; // return so don't save cache
    }
    if(curl_getinfo($ch, CURLINFO_HTTP_CODE) === 404) {
      echo json_encode(array(
        "message" => "Anime with specified id could not be found."
      ));
      http_response_code(404);
      return;
    }
    curl_close($ch);
    
    $data->saveCache($url, $response);
    $html = str_get_html($response);
  }
  
  if(!is_object($html)) {
    echo json_encode(array(
      "message" => "The code for MAL is not valid HTML markup.",
    ));
    http_response_code(500);
    return;
  }
  
  //    [+] ============================================== [+]
  //    [+] --------------SETTING THE VALUES-------------- [+]
  //    [+] ============================================== [+]
  
  $anime = new Anime();
  
  $anime->set("id", $parts[0]);
  $anime->set("title", $html->find("div#contentWrapper div h1.h1 span", 0)->plaintext);
  $alternativeTitles = $html->find("div#contentWrapper div#content table div.js-scrollfix-bottom .spaceit_pad");
  $alternativeTitles_eng = array(); // Changed from string because there can be multiple
  $alternativeTitles_jap = array();
  $alternativeTitles_syn = array();
  foreach($alternativeTitles as $value) {
    if(strpos($value->plaintext, "English:") !== false) {
      $alternativeTitles_eng = explode(", ", trim(substr($value->plaintext, 15), " "));
    } else if(strpos($value->plaintext, "Japanese:") !== false) {
      $alternativeTitles_jap = explode(", ", trim(substr($value->plaintext, 16), " "));
    } else if(strpos($value->plaintext, "Synonyms:") !== false) {
      $alternativeTitles_syn = explode(", ", trim(substr($value->plaintext, 16), " "));
    }
  }
  unset($value);
  $anime->set("other_titles", array(
    "english" => $alternativeTitles_eng,
    "japanese" => $alternativeTitles_jap,
    "synonyms" => $alternativeTitles_syn
  ));
  $html->find("div#contentWrapper div#content div.anime-detail-header-stats span.ranked strong", 0)->plaintext == "N/A" ? $anime->set("rank", null) : $anime->set("rank", substr($html->find("div#contentWrapper div#content div.anime-detail-header-stats span.ranked strong", 0)->plaintext, 1));
  $anime->set("popularity", substr($html->find("div#contentWrapper div#content div.anime-detail-header-stats span.popularity strong", 0)->plaintext, 1));
  $anime->set("image", $html->find("div#contentWrapper div#content table div a img.ac", 0)->src);
  $anime->set("mal_url", trim($html->find("div#contentWrapper div#content table div.js-scrollfix-bottom-rel div#horiznav_nav ul li a", 0)->href));
  trim($html->find("div#contentWrapper div#content div.anime-detail-header-stats .score", 0)->plaintext) == "N/A" ? $anime->set("score", null) : $anime->set("score", trim($html->find("div#contentWrapper div#content div.anime-detail-header-stats .score", 0)->plaintext));
  $html->find("div#contentWrapper div#content div.js-scrollfix-bottom-rel table td span[itemprop=description]", 0) ? $anime->set("synopsis", htmlspecialchars_decode(html_entity_decode(trim($html->find("div#contentWrapper div#content div.js-scrollfix-bottom-rel table td span[itemprop=description]", 0)->innertext, " "), 0, "UTF-8"))) : $anime->set("synopsis", null);
  $information = $html->find("div#contentWrapper div#content div.js-scrollfix-bottom div");
  foreach($information as $value) {
    if(strpos($value->plaintext, "Type:") !== false) {
      // MAL has an ending tag without a starting tag bug so remove that
      strpos($value->plaintext, "Unknown") !== false ? $anime->set("type", null) : $anime->set("type", str_replace("</a>", "", substr($value->plaintext, 9)));
    }
    if(strpos($value->plaintext, "Episodes:") !== false) {
      strpos($value->innertext, "Unknown") !== false ? $anime->set("episodes", null) : $anime->set("episodes", substr($value->plaintext, 13));
    }
    if(strpos($value->plaintext, "Status:") !== false) {
      strpos($value->innertext, "Unknown") !== false ? $anime->set("status", null) : $anime->set("status", substr($value->plaintext, 11));
    }
    if(strpos($value->plaintext, "Duration:") !== false) {
      if(strpos($value->innertext, "Unknown") !== false) {
        $anime->set("duration", null);
      } else {
        if(strpos($value->plaintext, "hr.") !== false) {
          preg_match("/\d+(?= hr.)/", $value->plaintext, $matches);
          $hour = trim($matches[0], " ");
          $hour_minutes = intval($hour) * 60;
          $minutes = $hour_minutes;
        }
        if(strpos($value->plaintext, "min.") !== false) {
          preg_match("/\d+(?= min.)/", $value->plaintext, $matches);
          $minutes = trim($matches[0], " ");
          if(isset($hour_minutes)) {
            $minutes = intval($minutes) + intval($hour_minutes);
          }
        }
        if(strpos($value->plaintext, "sec.") !== false) { // Example id: 33902
          preg_match("/\d+(?= sec.)/", $value->plaintext, $matches);
          $seconds = trim($matches[0], " ");
          $seconds_minutes = $seconds / 60;
          if(isset($minutes)) {
            $minutes = intval($minutes) + intval($seconds_minutes);
          } else {
            $minutes = $seconds_minutes;
          }
        }
        $anime->set("duration", $minutes);
      }
      $anime->get("duration") !== null && $anime->get("episodes") !== null ? $anime->set("total_duration", intval($anime->get("duration")) * intval($anime->get("episodes"))) : $anime->set("total_duration", null); // Set total duration only if duration and episodes are defined
    }
    if(strpos($value->plaintext, "Rating:") !== false) {
      strpos($value->innertext, "Unknown") !== false ? $anime->set("rating", null) : $anime->set("rating", substr($value->plaintext, "11"));
    }
    if(strpos($value->plaintext, "Score:") !== false) {
      if(strpos($value->plaintext, "users") !== false) {
        preg_match("/\d(,?\d?)+(?=  users)/", $value->plaintext, $matches);
        $anime->set("score_count", str_replace(",", "", $matches[0]));
      } else {
        $anime->set("score_count", null);
      }
    }
    if(strpos($value->plaintext, "Members:") !== false) {
      $anime->set("members_count", str_replace(",", "", trim(substr($value->plaintext, 12), " ")));
    }
    if(strpos($value->plaintext, "Favorites:") !== false) {
      $anime->set("favorites_count", str_replace(",", "", trim(substr($value->plaintext, 14), " ")));
    }
    if(strpos($value->plaintext, "Genres:") !== false) {
      if(strpos($value->innertext, "No genres have been added yet.") !== false) {
        $anime->set("genres", array());
      } else {
        $genres_str = trim(substr($value->plaintext, 11), " ");
        $genres_arr = explode(", ", $genres_str);
        $anime->set("genres", array_map("trim", $genres_arr));
      }
    }
    if(strpos($value->plaintext, "Source:") !== false) {
      $anime->set("source", trim(substr($value->plaintext, 11), " "));
    }
    if(strpos($value->plaintext, "Producers:") !== false) {
      if(strpos($value->innertext, "None found") !== false) {
        $anime->set("producers", array());
      } else {
        $producers_str = trim(substr($value->plaintext, 16), " ");
        $producers_arr = explode(", ", $producers_str);
        $anime->set("producers", array_map("trim", $producers_arr));
      }
    }
    if(strpos($value->plaintext, "Studios:") !== false) {
      if(strpos($value->innertext, "None found") !== false) {
        $anime->set("studios", array());
      } else {
        $studios_str = trim(substr($value->plaintext, 14), " ");
        $studios_arr = explode(", ", $studios_str);
        $anime->set("studios", array_map("trim", $studios_arr));
      }
    }
    if(strpos($value->plaintext, "Licensors:") !== false) {
      if(strpos($value->innertext, "None found") !== false) {
        $anime->set("licensors", array());
      } else {
        $licensors_str = trim(substr($value->plaintext, 16), " ");
        $licensors_arr = explode(", ", $licensors_str);
        $anime->set("licensors", array_map("trim", $licensors_arr));
      }
    }
  }
  unset($value);

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "id" => $anime->get("id"),
    "title" => $anime->get("title"),
    "other_titles" => $anime->get("other_titles"),
    "rank" => $anime->get("rank"),
    "popularity" => $anime->get("popularity"),
    "image" => $anime->get("image"),
    "source" => $anime->get("source"),
    "url" => $anime->get("mal_url"),
    "type" => $anime->get("type"),
    "episodes" => $anime->get("episodes"),
    "duration" => $anime->get("duration"),
    "total_duration" => $anime->get("total_duration"),
    "score" => $anime->get("score"),
    "score_count" => $anime->get("score_count"),
    "members_count" => $anime->get("members_count"),
    "favorites_count" => $anime->get("favorites_count"),
    "genres" => $anime->get("genres"),
    "producers" => $anime->get("producers"),
    "studios" => $anime->get("studios"),
    "licensors" => $anime->get("licensors"),
    "synopsis" => $anime->get("synopsis")
  );
  
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo json_encode($output, JSON_NUMERIC_CHECK);
  http_response_code(200);
    
});
?>