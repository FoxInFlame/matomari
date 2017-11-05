<?php
/*

Shows reviews on an anime.

This method is cached for a week. Set the nocache parameter to true to use a fresh version (slower).
Method: GET
        /anime/reviews/:id
Authentication: None Required.
Parameters:
  - page: [Optional] Page number. If page doesn't exist, becomes 1. (defaults to 1)

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
require_once(dirname(__FILE__) . "/../absoluteGMT.php");
require_once(dirname(__FILE__) . "/../cache/class.cache.php");

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------GETTING THE VALUES-------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : "1";
  
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
  
  $url = "https://myanimelist.net/anime/" . $parts[0] . "/FoxInFlameIsAwesome/reviews?p=" . $page;
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

  $contentWrapper = $html->find("#contentWrapper", 0);
  
  $reviews = $contentWrapper->find("#content tr", 0)->children()[1]->find("div.borderDark");
  
  function changeInCode() {
    echo json_encode(array(
      "message" => "MAL has changed their code. Please notify FoxInFlame about this."
    ));
    http_response_code(500);
    return;
  }
  $reviews_arr = array();
  foreach($reviews as $review) {
    if($review->find(".textReadability div", 0)) {
      $review_id = substr($review->find(".textReadability div", 0)->id, 5);
    } else {
      return changeInCode();
    }
    if($review->find(".spaceit table td", 1) && $review->find(".spaceit table td", 1)->find("a", 0)) {
      // $review_author_username = trim($review->find(".borderLight td", 1)->find("a", 0)->innertext);
      $review_author_username = trim($review->find(".spaceit table td", 1)->find("a", 0)->innertext);
      $review_author_url = trim($review->find(".spaceit table td", 1)->find("a", 0)->href);
    } else {
      return changeInCode();
    }
    if($review->find(".spaceit table td img", 0)) {
      $review_author_image_url = $review->find(".spaceit table td img", 0)->{'data-src'};
    } else {
      return changeInCode();
    }
    if($review->find(".spaceit table td", 1) && $review->find(".lightLink strong span", 0)) {
      $review_helpful_count = $review->find(".spaceit table td", 1)->find(".lightLink strong span", 0)->innertext;
    } else {
      return changeInCode();
    }
    if($review->find(".spaceit .mb8", 0) && $review->find(".spaceit .mb8 .lightLink", 0)) {
      $review_time = getAbsoluteTimeGMT(trim(explode("<div", $review->find(".spaceit .mb8", 0)->innertext)[0]), "M j, Y")->format("c");
      $review_episodes_seen = explode(" of ", $review->find(".spaceit .mb8 .lightLink", 0)->innertext)[0];
      $review_overall_rating = substr($review->find(".spaceit .mb8 div", 1)->plaintext, -2);
    } else {
      return changeInCode();
    }
    $review_ratings = $review->find(".textReadability div table", 0);
    $review_overall_rating = null;
    $review_story_rating = null;
    $review_animation_rating = null;
    $review_sound_rating = null;
    $review_character_rating = null;
    $review_enjoyment_rating = null;
    foreach($review_ratings->find("tr") as $tr) {
      if(strpos($tr->find("td", 0)->innertext, "Overall") !== false) {
        $review_overall_rating = $tr->find("td strong", 1)->innertext;
      } else if(strpos($tr->find("td", 0)->innertext, "Story") !== false) {
        $review_story_rating = $tr->find("td", 1)->innertext;
      } else if(strpos($tr->find("td", 0)->innertext, "Animation") !== false) {
        $review_animation_rating = $tr->find("td", 1)->innertext;
      } else if(strpos($tr->find("td", 0)->innertext, "Sound") !== false) {
        $review_sound_rating = $tr->find("td", 1)->innertext;
      } else if(strpos($tr->find("td", 0)->innertext, "Character") !== false) {
        $review_character_rating = $tr->find("td", 1)->innertext;
      } else if(strpos($tr->find("td", 0)->innertext, "Enjoyment") !== false) {
        $review_enjoyment_rating = $tr->find("td", 1)->innertext;
      }
    }
    $review_text_1 = explode("\r\n", str_replace($review->find(".textReadability #score" . $review_id, 0)->plaintext, "", $review->find(".textReadability", 0)->plaintext));
    $review_text = htmlspecialchars_decode(html_entity_decode(substr(preg_replace('!\s+!', " ", trim(join("<br>", $review_text_1))), 0, -18), 0, "UTF-8"));
    array_push($reviews_arr, array(
      "id" => $review_id,
      "author" => array(
        "username" => $review_author_username,
        "url" => $review_author_url,
        "image" => $review_author_image_url
      ),
      "episodes_seen" => $review_episodes_seen,
      "helpful_count" => $review_helpful_count,
      "time" => $review_time,
      "rating" => array(
        "overall" => $review_overall_rating,
        "story" => $review_story_rating,
        "animation" => $review_animation_rating,
        "sound" => $review_sound_rating,
        "character" => $review_character_rating,
        "enjoyment" => $review_enjoyment_rating
      ),
      "review" => $review_text
    ));
  }

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "items" => $reviews_arr
  );
  
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo json_encode($output, JSON_NUMERIC_CHECK);
  http_response_code(200);
  
});
?>