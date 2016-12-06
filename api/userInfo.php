<?php
// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] -------------------HEADERS-------------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]
header("access-control-allow-origin: *");
header('Content-Type: application/json');
require("../SimpleHtmlDOM.php");


// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] --------------GETTING THE VALUES-------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

$parts = isset($_GET['username']) ? explode('/',$_GET['username']) : array();
$html = file_get_html("https://myanimelist.net/profile/" . $parts[0]);
if(!$html) {
  echo "{";
    echo "\"error\": \"Username was not found or MAL is offline.\"";
  echo "}";
}


//    [+] ============================================== [+]
//    [+] --------------SETTING THE VALUES-------------- [+]
//    [+] ============================================== [+]

$username = $parts[0];
$mal_link = "https://myanimelist.net/profile/" . $username;
$html_rightside = $html->find("div#contentWrapper div.container-right", 0);
$html_leftside = $html->find("div#contentWrapper div.container-left", 0);
$image_url = $html_leftside->find("div.user-profile div.user-image img", 0)->src;
$id = explode(".", end(explode("/", $image_url)))[0];
$userstatus = $html_leftside->find("div.user-profile ul.user-status li.clearfix");
$gender = "null";
$birthday = "null";
$location = "null";
$join_date = "null";
foreach($userstatus as $value) {
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
$alsoat = $html_leftside->find("div.user-profile div.user-profile-sns", 0)->find("a");
$alsoat_arr = array();
foreach($alsoat as $value) {
  array_push($alsoat_arr, $value->href);
}
unset($value);
$rss = $html_leftside->find("div.user-profile div.user-profile-sns", 1)->find("a");
$rss_recentanime = "null";
$rss_recentanime_byepisode = "null";
$rss_recentmanga = "null";
$rss_recentmanga_byepisode = "null";
$rss_blogfeed = "null";
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
$about = htmlspecialchars_decode(str_replace("\"", "'", trim($html_rightside->find("div.user-profile-about div.profile-about-user table tr td div.word-break", 0)->innertext, " ")));

$favourites_anime_arr = array();
$favourites_manga_arr = array();
$favourites_characters_arr = array();
$favourites_people_arr = array();
$favourites = $html_rightside->find("div.user-favorites", 0)->children();
foreach($favourites as $value) {
  if($value->find("h5", 0)->innertext == "Anime") {
    $favourites_anime = $value->find("ul.anime", 0);
    if(!empty($favourites_anime)) {
      foreach($favourites_anime->find("li") as $value) {
        array_push($favourites_anime_arr, trim(explode("/", $value->find("div", 1)->find("a", 0)->href)[4]));
      }
    }
  } else if($value->find("h5", 0)->innertext == "Manga") {
    $favourites_manga = $value->find("ul.manga", 0);
    if(!empty($favourites_manga)) {
      foreach($favourites_manga->find("li") as $value) {
        array_push($favourites_manga_arr, trim(explode("/", $value->find("div", 1)->find("a", 0)->href)[4]));
      }
    }
  } else if($value->find("h5", 0)->innertext == "Characters") {
    $favourites_characters = $value->find("ul.characters", 0);
    if(!empty($favourites_characters)) {
      foreach($favourites_characters->find("li") as $value) {
        array_push($favourites_characters_arr, trim(explode("/", $value->find("div", 1)->find("a", 0)->href)[4]));
      }
    }
  } else if($value->find("h5", 0)->innertext == "People") {
    $favourites_people = $value->find("ul.people", 0);
    if(!empty($favourites_people)) {
      foreach($favourites_people->find("li") as $value) {
        array_push($favourites_people_arr, trim(explode("/", $value->find("div", 1)->find("a", 0)->href)[4]));
      }
    }
  }
}


//    [+] ============================================== [+]
//    [+] --------------SETTING THE EMPTY--------------- [+]
//    [+] ============================================== [+]

if(!isset($type)) {
  $type = "null";
}



// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] ------------DISPLAYING THE VALUES------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

echo "{";
  echo "\"id\": " . $id . ",";
  echo "\"username\": \"" . $username . "\",";
  echo "\"url\": \"".$mal_link."\",";
  echo "\"image_url\": \"" . $image_url . "\",";
  echo "\"gender\": \"" . $gender . "\",";
  echo "\"birthday\": \"" . $birthday . "\",";
  echo "\"location\": \"" . $location . "\",";
  echo "\"join_date\": \"" . $join_date . "\",";
  echo "\"animelist\": \"" . $animelist . "\",";
  echo "\"mangalist\": \"" . $mangalist . "\",";
  echo "\"history\": {";
    echo "\"history_all\": \"" . $history . "\",";
    echo "\"history_anime\": \"" . $history_anime . "\",";
    echo "\"history_manga\": \"" . $history_manga . "\"";
  echo "},";
  echo "\"general_stats\": {";
    echo "\"forum_posts\": " . $generalStats_forumposts . ",";
    echo "\"reviews\": " . $generalStats_reviews . ",";
    echo "\"recommendations\": " . $generalStats_recommendations . ",";
    echo "\"blog_posts\": " . $generalStats_blogposts . ",";
    echo "\"clubs\": " . $generalStats_clubs;
  echo "},";
  echo "\"also_at\": [";
    foreach($alsoat_arr as $key => $value) {
      if((count($alsoat_arr) - 1) == $key) {
        echo "\"" . $value . "\"";
      } else {
        echo "\"" . $value . "\",";
      }
    }
  echo "],";
  echo "\"rss\": {";
    echo "\"recent_anime\": \"" . $rss_recentanime . "\",";
    echo "\"recent_anime_byepisode\": \"" . $rss_recentanime_byepisode . "\",";
    echo "\"recent_manga\": \"" . $rss_recentmanga . "\",";
    echo "\"recent_manga_bychapter\": \"" . $rss_recentmanga_bychapter . "\",";
    echo "\"blogfeed\": \"" . $rss_blogfeed . "\"";
  echo "},";
  echo "\"summary\": \"" . $about . "\",";
  echo "\"favourites\": {";
    echo "\"anime\": [";
      foreach($favourites_anime_arr as $key => $value) {
        if((count($favourites_anime_arr) - 1) == $key) {
          echo "\"".$value."\"";
        } else {
          echo "\"".$value."\",";
        }
      }
    echo "],";
    echo "\"manga\": [";
      foreach($favourites_manga_arr as $key => $value) {
        if((count($favourites_manga_arr) - 1) == $key) {
          echo "\"".trim($value, " ")."\"";
        } else {
          echo "\"".trim($value, " ")."\",";
        }
      }
    echo "],";
    echo "\"characters\": [";
      foreach($favourites_characters_arr as $key => $value) {
        if((count($favourites_characters_arr) - 1) == $key) {
          echo "\"".trim($value, " ")."\"";
        } else {
          echo "\"".trim($value, " ")."\",";
        }
      }
    echo "],";
    echo "\"people\": [";
      foreach($favourites_people_arr as $key => $value) {
        if((count($favourites_people_arr) - 1) == $key) {
          echo "\"".trim($value, " ")."\"";
        } else {
          echo "\"".trim($value, " ")."\",";
        }
      }
    echo "]";
  echo "}";
echo "}";
?>