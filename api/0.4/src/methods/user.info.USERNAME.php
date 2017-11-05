<?php
/*

Shows detailed information about a MAL user.

Method: GET
        /user/info/:username
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
  
  $parts = isset($_GET['username']) ? explode("/",$_GET['username']) : array();
  if(empty($parts)) {
    echo json_encode(array(
      "message" => "The username parameter is not defined."
    ));
    http_response_code(400);
    return;
  }
  $html = @file_get_html("https://myanimelist.net/profile/" . $parts[0]);
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
  
  $username = $parts[0];
  $mal_link = "https://myanimelist.net/profile/" . $username;
  $html_rightside = $html->find("div#contentWrapper div.container-right", 0);
  $html_leftside = $html->find("div#contentWrapper div.container-left", 0);
  $image_url =  $html_leftside->find("div.user-profile div.user-image img", 0) ? $html_leftside->find("div.user-profile div.user-image img", 0)->src : null;
  $end = explode("/", $image_url);
  $id = $image_url !== null ? explode(".", end($end))[0] : "";
  $userstats = $html_leftside->find("div.user-profile ul.user-status li.clearfix");
  $gender = null;
  $birthday = null;
  $location = null;
  $join_date = null;
  foreach($userstats as $value) {
    if(strpos($value->plaintext, "Gender") !== false) {
      $gender = trim(substr($value->plaintext, 7), " ");
    } else if(strpos($value->plaintext, "Birthday") !== false) {
      $birthday = trim(substr($value->plaintext, 9));
    } else if(strpos($value->plaintext, "Location") !== false) {
      $location = trim(substr($value->plaintext, 9));
    } else if(strpos($value->plaintext, "Joined") !== false) {
      $join_date = trim(substr($value->plaintext, 7));
    }
  }
  unset($value);
  $animelist = "https://myanimelist.net/animelist/" . $username;
  $mangalist = "https://myanimelist.net/mangalist/" . $username;
  $history = "https://myanimelist.net/history/" . $username;
  $history_anime = "https://myanimelist.net/history/" . $username . "/anime";
  $history_manga = "https://myanimelist.net/history/" . $username . "/manga";
  $generalStats = $html_leftside->find("div.user-profile ul.user-status", 2)->find("li.link");
  $generalStats_forumposts = "0";
  $generalStats_reviews = "0";
  $generalStats_recommendations = "0";
  $generalStats_blogposts = "0";
  $generalStats_clubs = "0";
  foreach($generalStats as $value) {
    if(strpos($value->plaintext, "Forum Posts") !== false) {
      $generalStats_forumposts = trim(substr($value->plaintext, 12));
    } else if(strpos($value->plaintext, "Reviews") !== false) {
      $generalStats_reviews = trim(substr($value->plaintext, 8));
    } else if(strpos($value->plaintext, "Recommendations") !== false) {
      $generalStats_recommendations = trim(substr($value->plaintext, 16));
    } else if(strpos($value->plaintext, "Blog Posts") !== false) {
      $generalStats_blogposts = trim(substr($value->plaintext, 11));
    } else if(strpos($value->plaintext, "Clubs") !== false) {
      $generalStats_clubs = trim(substr($value->plaintext, 6));
    }
  }
  unset($value);
  $alsoat_arr = array();
  if(strpos($html_leftside->find(".user-profile .user-profile-sns a", 0)->innertext, "Recent Anime") !== false) {
    // User hasn't set any Also At links.
    $alsoat_arr = array();
  } else {
    $alsoat = $html_leftside->find(".user-profile .user-profile-sns", 0)->find("a");
    foreach($alsoat as $value) {
      array_push($alsoat_arr, $value->href);
    }
  }
  unset($value);
  if(empty($alsoat_arr)) {
    $rss = $html_leftside->find(".user-profile .user-profile-sns", 0)->find("a");
  } else {
    $rss = $html_leftside->find(".user-profile .user-profile-sns", 1)->find("a");
  }
  $rss_recentanime = null;
  $rss_recentanime_byepisode = null;
  $rss_recentmanga = null;
  $rss_recentmanga_byepisode = null;
  $rss_blogfeed = null;
  foreach($rss as $value) {
    if(strpos($value->plaintext, "Recent Anime by Episode") !== false) {
      $rss_recentanime_byepisode = htmlspecialchars_decode($value->href);
    } else if(strpos($value->plaintext, "Recent Anime") !== false) {
      $rss_recentanime = htmlspecialchars_decode($value->href);
    } else if(strpos($value->plaintext, "Recent Manga by Chapter") !== false) {
      $rss_recentmanga_bychapter = htmlspecialchars_decode($value->href);
    } else if(strpos($value->plaintext, "Recent Manga") !== false) {
      $rss_recentmanga = htmlspecialchars_decode($value->href);
    } else if(strpos($value->plaintext, "Blog Feed") !== false) {
      $rss_blogfeed = htmlspecialchars_decode($value->href);
    }
  }
  unset($value);
  if(empty($id)) {
    $id = explode("id=", $rss_blogfeed)[1];
  }
  $about = $html_rightside->find(".user-profile-about .profile-about-user table tr td .word-break", 0) ? htmlspecialchars_decode(str_replace("\"", "'", trim($html_rightside->find(".user-profile-about .profile-about-user table tr td .word-break", 0)->innertext, " "))) : "";


  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "id" => $id,
    "username" => $username,
    "url" => $mal_link,
    "image_url" => $image_url,
    "gender" => $gender,
    "birthday" => $birthday,
    "location" => $location,
    "join_date" => $join_date,
    "animelist" => $animelist,
    "mangalist" => $mangalist,
    "history" => array(
      "history_all" => $history,
      "history_anime" => $history_anime,
      "history_manga" => $history_manga
    ),
    "general_stats" => array(
      "forum_posts" => $generalStats_forumposts,
      "reviews" => $generalStats_reviews,
      "recommendations" => $generalStats_recommendations,
      "blog_posts" => $generalStats_blogposts,
      "clubs" => $generalStats_clubs
    ),
    "also_at" => $alsoat_arr,
    "rss" => array(
      "recent_anime" => $rss_recentanime,
      "recent_anime_byepisodes" => $rss_recentanime_byepisode,
      "recent_manga" => $rss_recentmanga,
      "recent_manga_bychapter" => $rss_recentmanga_bychapter,
      "blogfeed" => $rss_blogfeed
    ),
    "summary" => $about,
  );
  
  // Remove string_ after parse
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo str_replace("string_", "", json_encode($output, JSON_NUMERIC_CHECK));
  http_response_code(200);
  
});
?>