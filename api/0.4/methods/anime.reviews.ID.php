<?php
/*

Shows reviews on an anime.

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

ini_set("display_errors", true);
ini_set("display_startup_errors", true);
error_reporting(E_ALL);

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
  
  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : "1";
  
  $id = isset($_GET['id']) ? $_GET['id'] : "";
  if(empty($id)) {
    echo json_encode(array(
      "error" => "The id parameter is not defined."
    ));
    return;
  }
  if(!is_numeric($id)) {
    echo json_encode(array(
      "error" => "Specified anime id is not a number."
    ));
    return;
  }
  $html = @file_get_html("https://myanimelist.net/anime/" . $id . "/FoxInFlameIsAwesome/reviews?p=" . $page);
  if(!$html) {
    echo json_encode(array(
      "error" => "Anime with specified id was not found."
    ));
    return;
  }
  
  //    [+] ============================================== [+]
  //    [+] --------------SETTING THE VALUES-------------- [+]
  //    [+] ============================================== [+]

  $contentWrapper = $html->find("#contentWrapper", 0);
  
  $reviews = $contentWrapper->find("#content tr", 0)->children()[1]->find("div.borderDark");
  
  $reviews_arr = array();
  foreach($reviews as $review) {
    $review_id = substr($review->find(".textReadability div", 0)->id, 5);
    $review_author_username = trim($review->find(".borderLight td", 1)->find("a", 0)->innertext);
    $review_author_url = "https://myanimelist.net" . trim($review->find(".borderLight td", 1)->find("a", 0)->href);
    $review_author_image_url = $review->find(".borderLight td img", 0)->{'data-src'};
    $review_helpful_count = $review->find(".borderLight td", 1)->find(".lightLink strong span", 0)->innertext;
    $review_time = getAbsoluteTimeGMT(trim($review->find(".borderLight td", 2)->find("div", 0)->innertext))->format("c");
    $review_episodes_seen = explode(" of ", $review->find(".borderLight td", 2)->find("div", 1)->innertext)[0];
    $review_overall_rating = substr($review->find(".borderLight td", 2)->find("div", 2)->plaintext, -2);
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
    $review_text_1 = array_slice(explode("\r\n", $review->find(".textReadability", 0)->plaintext), 7);
    $review_text = htmlspecialchars_decode(html_entity_decode(substr(trim(join("<br>", $review_text_1)), 0, -11), 0, "UTF-8"));
    array_push($reviews_arr, array(
      "id" => $review_id,
      "author" => array(
        "username" => $review_author_username,
        "url" => $review_author_url,
        "image_url" => $review_author_image_url
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
});

function getAbsoluteTimeGMT($string) {
  $string = trim($string); // Super important! :)
  if(strpos($string, "ago") !== false) {
    /*Note: These are returning approximate values */
    $date = new DateTime(null);
    $date->setTimeZone(new DateTimeZone("Etc/GMT"));
    if(strpos($string, "hour") !== false) {
      if(strpos($string, "hours") !== false) {
        $hours = substr($string, 0, -10);
        $date->modify("-" . $hours . " hours");
      } else {
        $hour = substr($string, 0, -9);
        $date->modify("-" . $hour . " hour");
      }
    }
    if(strpos($string, "minute") !== false) {
      if(strpos($string, "minutes") !== false) {
        $minutes = substr($string, 0, -12);
        $date->modify("-" . $minutes . " minutes");
      } else {
        $minute = substr($string, 0, -11);
        $date->modify("-" . $minute . " minute");
      }
    }
    if(strpos($string, "second") !== false) {
      if(strpos($string, "seconds") !== false) {
        $seconds = substr($string, 0, -12);
        $date->modify("-" . $seconds . " seconds");
      } else {
        $second = substr($string, 0, -11);
        $date->modify("-" . $second . " second");
      }
    }
    return $date;
  } else if(strpos($string, "Today") !== false) {
    $date = date_create_from_format("g:i A", substr($string, 7), new DateTimeZone("Etc/GMT+8"))->setTimeZone(new DateTimeZone("Etc/GMT"));
    return $date;
  } else if(strpos($string, "Yesterday") !== false) {
    $date = date_create_from_format("g:i A", substr($string, 11), new DateTimeZone("Etc/GMT+8"))->setTimeZone(new DateTimeZone("Etc/GMT"));
    $date->modify("-1 day");
    return $date;
  } else {
    // "M j, g:i A" is the date type MAL shows
    $date = date_create_from_format("M j, Y", $string, new DateTimeZone("Etc/GMT+8"))->setTimeZone(new DateTimeZone("Etc/GMT"));
    return $date;
  }
}
?>