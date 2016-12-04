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

$parts = isset($_GET['id']) ? explode('/',$_GET['id']) : array();
if(!is_numeric($parts[0])) {
  echo "Specified anime id is not a number.";
  exit(400);
}
$html = file_get_html("https://myanimelist.net/anime/" . $parts[0]);
if(!$html) {
  echo "Anime with specified id was not found.";
  exit(404);
}


//    [+] ============================================== [+]
//    [+] --------------SETTING THE VALUES-------------- [+]
//    [+] ============================================== [+]

$id = $parts[0];
$title = substr($html->find("div#contentWrapper div h1.h1 span", 0)->plaintext, 0, -1);
$alternativeTitles = $html->find("div#contentWrapper div#content table div.js-scrollfix-bottom .spaceit_pad");
$alternativeTitles_eng = "null";
$alternativeTitles_jap = "null";
$alternativeTitles_syn = "null";
foreach($alternativeTitles as $value) {
  if(strpos($value->plaintext, "English:") !== false) {
    $alternativeTitles_eng = "\"".trim(substr($value->plaintext, 15), " ")."\"";
  } else if(strpos($value->plaintext, "Japanese:") !== false) {
    $alternativeTitles_jap = "\"".trim(substr($value->plaintext, 16), " ")."\"";
  } else if(strpos($value->plaintext, "Synonyms:") !== false) {
    $alternativeTitles_syn = "\"".trim(substr($value->plaintext, 16), " ")."\"";
  }
}
unset($value);
$rank = substr($html->find("div#contentWrapper div#content div.anime-detail-header-stats span.ranked strong", 0)->plaintext, 1);
$popularity_rank = substr($html->find("div#contentWrapper div#content div.anime-detail-header-stats span.popularity strong", 0)->plaintext, 1);
$image_url = $html->find("div#contentWrapper div#content table div a img.ac", 0)->src;
$mal_link = trim($html->find("div#contentWrapper div#content table div.js-scrollfix-bottom-rel div#horiznav_nav ul li a", 0)->href, " ");
$information = $html->find("div#contentWrapper div#content div.js-scrollfix-bottom div");
foreach($information as $value) {
  if(strpos($value->plaintext, "Type:") !== false) {
    $type = trim(substr($value->plaintext, 9), " ");
  }
  if(strpos($value->plaintext, "Episodes:") !== false) {
    $episodes = trim(substr($value->plaintext, 13), " ");
    if($episodes == "Unknown") {
      $episodes = "null";
    }
  }
  if(strpos($value->plaintext, "Duration:") !== false) {
    if(strpos($value->plaintext, "hr.") !== false) {
      preg_match("/\d+(?= hr.)/", $value->plaintext, $matches);
      $hour = trim($matches[0], " ");
      $minutes = intval($hour) * 60;
    }
    if(strpos($value->plaintext, "min.") !== false) {
      preg_match("/\d+(?= min.)/", $value->plaintext, $matches);
      $minutes = trim($matches[0], " ");
      if(isset($hour)) {
        $minutes = intval($minutes) + (intval($hour) * 60);
      }
    }
  }
  if(strpos($value->plaintext, "Score:") !== false) {
    if(strpos($value->plaintext, "users") !== false) {
      preg_match("/\d(,?\d?)+(?=  users)/", $value->plaintext, $matches);
      $members_count = str_replace(",", "", $matches[0]);
    } else {
      $members_count = "null";
    }
  }
  if(strpos($value->plaintext, "Genres:") !== false) {
    $genres_str = trim(substr($value->plaintext, 11), " ");
    $genres_arr = explode(", ", $genres_str);
  }
  if(strpos($value->plaintext, "Source:") !== false) {
    $source = trim(substr($value->plaintext, 11), " ");
  }
  if(strpos($value->plaintext, "Producers:") !== false) {
    $producers_str = trim(substr($value->plaintext, 16), " ");
    $producers_arr = explode(", ", $producers_str);
    if($producers_arr[0] == "None found") {
      $producers_arr = [];
    }
  }
  if(strpos($value->plaintext, "Studios:") !== false) {
    $studios_str = trim(substr($value->plaintext, 14), " ");
    $studios_arr = explode(", ", $studios_str);
    if($studios_arr[0] == "None found") {
      $studios_arr = [];
    }
  }
  if(strpos($value->plaintext, "Licensors:") !== false) {
    $licensors_str = trim(substr($value->plaintext, 16), " ");
    $licensors_arr = explode(", ", $licensors_str);
    if($licensors_arr[0] == "None found") {
      $licensors_arr = [];
    }
  }
}
unset($value);


//    [+] ============================================== [+]
//    [+] --------------SETTING THE EMPTY--------------- [+]
//    [+] ============================================== [+]

if(!isset($type)) {
  $type = "null";
}
if(!isset($episodes)) {
  $episodes = "null";
}
if(!isset($minutes)) {
  $minutes = "null";
}
if(!isset($members_count)) {
  $members_count = "null";
}
if(!isset($genres_arr)) {
  $genres_arr = [];
}
if($episodes != "null") {
  $total_duration = intval($minutes) * intval($episodes);
} else {
  $total_duration = "null";
}
$members_score = trim($html->find("div#contentWrapper div#content div.anime-detail-header-stats .score", 0)->plaintext, " ");
if($rank == "/A") {
  $rank = "null";
}
if($members_score == "N/A") {
  $members_score = "null";
}
$synopsis = trim($html->find("div#contentWrapper div#content div.js-scrollfix-bottom-rel table td span[itemprop=description]", 0)->innertext, " ");




// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] ------------DISPLAYING THE VALUES------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

echo "{";
  echo "\"id\": " . $id . ",";
  echo "\"title\": \"" . $title . "\",";
  echo "\"other_titles\": {";
    echo "\"english\": ".$alternativeTitles_eng.",";
    echo "\"japanese\": ".$alternativeTitles_jap.",";
    echo "\"synonyms\": ".$alternativeTitles_syn;
  echo "},";
  echo "\"rank\": " . $rank . ",";
  echo "\"popularity\": " . $popularity_rank . ",";
  echo "\"image_url\": \"" . $image_url . "\",";
  echo "\"source\": \"".$source."\",";
  echo "\"url\": \"".$mal_link."\",";
  echo "\"type\": \"" . $type . "\",";
  echo "\"episodes\": " . $episodes . ",";
  echo "\"duration\": " . $minutes . ",";
  echo "\"total_duration\": " . $total_duration . ",";
  echo "\"members_score\": " . $members_score . ",";
  echo "\"members_count\": " . $members_count. ",";
  echo "\"genres\": [";
    foreach($genres_arr as $key => $value) {
      if((count($genres_arr) - 1) == $key) {
        echo "\"".$value."\"";
      } else {
        echo "\"".$value."\",";
      }
    }
  echo "],";
  echo "\"producers\": [";
    foreach($producers_arr as $key => $value) {
      if((count($producers_arr) - 1) == $key) {
        echo "\"".trim($value, " ")."\"";
      } else {
        echo "\"".trim($value, " ")."\",";
      }
    }
  echo "],";
  echo "\"studios\": [";
    foreach($studios_arr as $key => $value) {
      if((count($studios_arr) - 1) == $key) {
        echo "\"".trim($value, " ")."\"";
      } else {
        echo "\"".trim($value, " ")."\",";
      }
    }
  echo "],";
  echo "\"licensors\": [";
    foreach($licensors_arr as $key => $value) {
      if((count($licensors_arr) - 1) == $key) {
        echo "\"".trim($value, " ")."\"";
      } else {
        echo "\"".trim($value, " ")."\",";
      }
    }
  echo "],";
  echo "\"synopsis\": \"".$synopsis ."\"";
echo "}";
?>
