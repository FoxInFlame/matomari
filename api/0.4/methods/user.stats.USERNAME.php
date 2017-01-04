<?php
/*

Shows stats for a MAL user.

Method: GET
        /user/stats/:username
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

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------GETTING THE VALUES-------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  if(!isset($_GET['username']) || empty($_GET['username'])) {
    echo json_encode(array(
      "message" => "The username parameter is not defined."
    ));
    http_response_code(400);
    return;
  }
  $html = @file_get_html("https://myanimelist.net/profile/" . $_GET['username']);
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
  
  $username = $_GET['username'];
  $mal_link = "https://myanimelist.net/profile/" . $username;
  $stats_div = $html->find("#contentWrapper #content .container-right #statistics", 0);
  $stats_anime_div = $stats_div->find(".user-statistics-stats", 0);
  $stats_manga_div = $stats_div->find(".user-statistics-stats", 1);
  
  $stats_anime_arr = array();
  $stats_manga_arr = array();
  $updates_anime_arr = array();
  $updates_manga_arr = array();
  
  $stats_anime_status = $stats_anime_div->find(".stats .clearfix .stats-status", 0);
  $stats_anime_totals = $stats_anime_div->find(".stats .clearfix .stats-data", 0);
  $stats_manga_status = $stats_manga_div->find(".stats .clearfix .stats-status", 0);
  $stats_manga_totals = $stats_manga_div->find(".stats .clearfix .stats-data", 0);
  
  
  $updates_anime = $stats_anime_div->find(".updates .statistics-updates");
  $updates_manga = $stats_manga_div->find(".updates .statistics-updates");
  
  //      [+] ++++++++++++++++++++++++++++++++++++++++++++++ [+]
  //      [+] ------------------Anime Stats----------------- [+]
  //      [+] ++++++++++++++++++++++++++++++++++++++++++++++ [+]
  
  foreach($stats_anime_status->find("li") as $value) {
    if($value->find(".watching")) {
      $stats_anime_arr["watching"] = str_replace(",", "", $value->find("span", 0)->innertext);
      continue;
    }
    if($value->find(".completed")) {
      $stats_anime_arr["completed"] = str_replace(",", "", $value->find("span", 0)->innertext);
      continue;
    }
    if($value->find(".on_hold")) {
      $stats_anime_arr["onhold"] = str_replace(",", "", $value->find("span", 0)->innertext);
      continue;
    }
    if($value->find(".dropped")) {
      $stats_anime_arr["dropped"] = str_replace(",", "", $value->find("span", 0)->innertext);
      continue;
    }
    if($value->find(".plan_to_watch")) {
      $stats_anime_arr["plantowatch"] = str_replace(",", "", $value->find("span", 0)->innertext);
      continue;
    }
  }
  
  foreach($stats_anime_totals->find("li") as $value) {
    if(strpos($value->find("span", 0)->innertext, "Total Entries") !== false) {
      $stats_anime_arr["total"] = str_replace(",", "", $value->find("span", 1)->innertext);
      continue;
    }
    if(strpos($value->find("span", 0)->innertext, "Rewatched") !== false) {
      $stats_anime_arr["rewatched"] = str_replace(",", "", $value->find("span", 1)->innertext);
      continue;
    }
    if(strpos($value->find("span", 0)->innertext, "Episodes") !== false) {
      $stats_anime_arr["watched_episodes"] = str_replace(",", "", $value->find("span", 1)->innertext);
      continue;
    }
  }
  
  foreach($stats_anime_div->find(".stats .stat-score div") as $value) {
    if(strpos($value->find("span", 0)->innertext, "Days") !== false) {
      $stats_anime_arr["watched_days"] = str_replace(",", "", substr(trim($value->plaintext), 6));
      continue;
    }
    if(strpos($value->find("span", 0)->innertext, "Mean Score") !== false) {
      $stats_anime_arr["average_score"] = substr(trim($value->plaintext), 12);
      continue;
    }
  }
  
  //      [+] ++++++++++++++++++++++++++++++++++++++++++++++ [+]
  //      [+] -----------------Anime Updates---------------- [+]
  //      [+] ++++++++++++++++++++++++++++++++++++++++++++++ [+]
  
  foreach($updates_anime as $value) {
    $url = $value->find(".data a", 0)->href;
    $title = $value->find(".data a", 0)->innertext;
    $id = explode("/", $url)[4];
    $time = getAbsoluteTimeGMT($value->find(".data .clearfix span", 0)->innertext)->format("c");
    $status = strtolower(trim(explode("<span", $value->find(".data .fn-grey2", 1)->innertext)[0]));
    $episode = $value->find(".data .fn-grey2", 1)->find(".text", 0) ? $value->find(".data .fn-grey2", 1)->find(".text", 0)->innertext : null;
    if(strpos($value->find(".data .fn-grey2", 1)->innertext, "</span>") !== false) {
      $totalepisodes = trim(substr(trim(explode("Scored", trim(explode("</span>", $value->find(".data .fn-grey2", 1)->innertext)[1]))[0]), 1, -9));
    } else {
      $totalepisodes = "?";
    }
    if($episode == "?") $episode = null;
    if($totalepisodes == "?") $totalepisodes = null;
    $score = $value->find(".text", 1) ? $value->find(".text", 1)->innertext : null;
    array_push($updates_anime_arr, array(
      "url" => $url,
      "id" => $id,
      "title" => $title,
      "time" => $time,
      "status" => $status,
      "update_episode" => $episode,
      "episodes" => $totalepisodes,
      "score" => $score
    ));
  }
  
  //      [+] ++++++++++++++++++++++++++++++++++++++++++++++ [+]
  //      [+] ------------------Manga Stats----------------- [+]
  //      [+] ++++++++++++++++++++++++++++++++++++++++++++++ [+]
  foreach($stats_manga_status->find("li") as $value) {
    if($value->find(".reading")) {
      $stats_manga_arr["reading"] = str_replace(",", "", $value->find("span", 0)->innertext);
      continue;
    }
    if($value->find(".completed")) {
      $stats_manga_arr["completed"] = str_replace(",", "", $value->find("span", 0)->innertext);
      continue;
    }
    if($value->find(".on_hold")) {
      $stats_manga_arr["onhold"] = str_replace(",", "", $value->find("span", 0)->innertext);
      continue;
    }
    if($value->find(".dropped")) {
      $stats_manga_arr["dropped"] = str_replace(",", "", $value->find("span", 0)->innertext);
      continue;
    }
    if($value->find(".plan_to_watch")) {
      $stats_manga_arr["plantoread"] = str_replace(",", "", $value->find("span", 0)->innertext);
      continue;
    }
  }
  
  foreach($stats_manga_totals->find("li") as $value) {
    if(strpos($value->find("span", 0)->innertext, "Total Entries") !== false) {
      $stats_manga_arr["total"] = str_replace(",", "", $value->find("span", 1)->innertext);
      continue;
    }
    if(strpos($value->find("span", 0)->innertext, "Reread") !== false) {
      $stats_manga_arr["reread"] = str_replace(",", "", $value->find("span", 1)->innertext);
      continue;
    }
    if(strpos($value->find("span", 0)->innertext, "Chapters") !== false) {
      $stats_manga_arr["read_chapters"] = str_replace(",", "", $value->find("span", 1)->innertext);
      continue;
    }
    if(strpos($value->find("span", 0)->innertext, "Volumes") !== false) {
      $stats_manga_arr["read_volumes"] = str_replace(",", "", $value->find("span", 1)->innertext);
      continue;
    }
  }
  
  foreach($stats_manga_div->find(".stats .stat-score div") as $value) {
    if(strpos($value->find("span", 0)->innertext, "Days") !== false) {
      $stats_manga_arr["read_days"] = str_replace(",", "", substr(trim($value->plaintext), 6));
      continue;
    }
    if(strpos($value->find("span", 0)->innertext, "Mean Score") !== false) {
      $stats_manga_arr["average_score"] = substr(trim($value->plaintext), 12);
      continue;
    }
  }
  
  //      [+] ++++++++++++++++++++++++++++++++++++++++++++++ [+]
  //      [+] -----------------Manga Updates---------------- [+]
  //      [+] ++++++++++++++++++++++++++++++++++++++++++++++ [+]
  
  foreach($updates_manga as $value) {
    $url = $value->find(".data a", 0)->href;
    $title = $value->find(".data a", 0)->innertext;
    $id = explode("/", $url)[4];
    $time = getAbsoluteTimeGMT($value->find(".data .clearfix span", 0)->innertext)->format("c");
    $status = strtolower(trim(explode("<span", $value->find(".data .fn-grey2", 1)->innertext)[0]));
    $chapter = $value->find(".data .fn-grey2", 1)->find(".text", 0) ? $value->find(".data .fn-grey2", 1)->find(".text", 0)->innertext : null;
    if(strpos($value->find(".data .fn-grey2", 1)->innertext, "</span>") !== false) {
      $totalchapters = trim(substr(trim(explode("Scored", trim(explode("</span>", $value->find(".data .fn-grey2", 1)->innertext)[1]))[0]), 1, -9));
    } else {
      $totalchapters = "?";
    }
    if($chapter == "?") $chapter = null;
    if($totalchapters == "?") $totalchapters = null;
    $score = $value->find(".text", 1) ? $value->find(".text", 1)->innertext : null;
    array_push($updates_manga_arr, array(
      "url" => $url,
      "id" => $id,
      "title" => $title,
      "time" => $time,
      "status" => $status,
      "update_chapter" => $chapter,
      "chapters" => $totalchapters,
      "score" => $score
    ));
  }
  
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "anime" => array(
      "stats" => $stats_anime_arr,
      "latest_updates" => $updates_anime_arr
    ),
    "manga" => array(
      "stats" => $stats_manga_arr,
      "latest_updates" => $updates_manga_arr
    )
  );
  
  // Remove string_ after parse
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo str_replace("string_", "", json_encode($output, JSON_NUMERIC_CHECK));
  http_response_code(200);
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
    $date = date_create_from_format("M j, g:i A", $string, new DateTimeZone("Etc/GMT+8"));
    if(!$date) {
      // Different year.
      $date = date_create_from_format("M j, Y g:i A", $string, new DateTimeZone("Etc/GMT+8"));
    }
    $date->setTimeZone(new DateTimeZone("Etc/GMT"));
    return $date;
  }
}