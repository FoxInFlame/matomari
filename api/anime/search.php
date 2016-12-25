<?php
/*

Shows anime search results for a query. Maximum 50 results. Filtering supported.

Method: GET
        /api/anime/search/QUERY.(json|xml)
Authentication: None Required.
Supported Filetypes: json, xml.
Parameters:
  - page: [Optional] Page number. If page doesn't exist, becomes 1. (Defaults to 1)
  - filter: [Optional] Filters, seperated by comma.
    - Examples:
      /search/Pokemon.json?filter=inc-genre:action,score=9 // Includes genre "Action" with score of 9 with query  of Pokemon as JSON
      /search/Naruto.xml?filter=type:tv,rating:pg // TV with rating of PG-13, with query of Pokemon as XML
      /search/.json?filter=inc-genre:action // All anime with action genre as JSON

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
require(dirname(__FILE__) . "/../SimpleHtmlDOM.php");

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------GETTING THE VALUES-------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $parts = isset($_GET['q']) ? explode("/",$_GET['q']) : array();
  $filter = isset($_GET['filter']) ? $_GET['filter'] : "";
  $filters = explode(",", $filter);
  $filter_param = "";
  if(isset($_GET['filter']) && strpos($_GET['filter'], ":") !== false) {
    $filters = explode(",", $filter);
    foreach($filters as $filter) {
      $filterparts = explode(":", $filter);
      if(!isset($filterparts[0]) || !isset($filterparts[1]) || trim($filterparts[0]) == "" || trim($filterparts[1]) == "" || count($filterparts) != 2) {
        continue; // Parameter is not valid.
      }
      switch(strtolower($filterparts[0])) {
        case "type":
          // type: TV, OVA, Movie, Special, ONA, Music.
          switch(strtolower($filterparts[1])) {
            case "tv":
              $filter_param .= "&type=1";
              break;
            case "ova":
              $filter_param .= "&type=2";
              break;
            case "movie":
              $filter_param .= "&type=3";
              break;
            case "special":
              $filter_param .= "&type=4";
              break;
            case "ona":
              $filter_param .= "&type=5";
              break;
            case "music":
              $filter_param .= "&type=6";
              break;
            default:
              $filter_param .= "&type=0";
              break;
          }
          break;
        case "score":
          switch(strtolower($filterparts[1])) {
            case "1":
              $filter_param .= "&score=1";
              break;
            case "2":
              $filter_param .= "&score=2";
              break;
            case "3":
              $filter_param .= "&score=3";
              break;
            case "4":
              $filter_param .= "&score=4";
              break;
            case "5":
              $filter_param .= "&score=5";
              break;
            case "6":
              $filter_param .= "&score=6";
              break;
            case "7":
              $filter_param .= "&score=7";
              break;
            case "8":
              $filter_param .= "&score=8";
              break;
            case "9":
              $filter_param .= "&score=9";
              break;
            case "10":
              $filter_param .= "&score=10";
              break;
            default:
              $filter_param .= "&score=0";
              break;
          }
          break;
        case "status":
          switch(strtolower($filterparts[1])) {
            case "finishedairing":
              $filter_param .= "&status=2";
              break;
            case "currentlyairing":
              $filter_param .= "&status=1";
              break;
            case "notyetaired":
              $filter_param .= "&status=3";
              break;
            default:
              $filter_param .= "&status=0";
              break;
          }
          break;
        case "producer":
          // Too much to handle right now... Maybe later.
          break;
        case "rating":
          switch(strtolower($filterparts[1])) {
            case "g":
              $filter_param .= "&r=1";
              break;
            case "pg":
              $filter_param .= "&r=2";
              break;
            case "pg13":
              $filter_param .= "&r=3";
              break;
            case "r":
              $filter_param .= "&r=4";
              break;
            case "r+":
              $filter_param .= "&r=5";
              break;
            case "rx":
              $filter_param .= "&r=6";
              break;
            default:
              $filter_param .= "&r=0";
              break;
          }
          break;
        case "startdate":
          if(strlen($filterparts[1]) != 8) {
            $filter_param .= "&sm=0&sd=0&sy=0";
            break;
          }
          if(is_numeric(substr($filterparts[1], 0, 4))) {
            $filter_param .= "&sy=" . substr($filterparts[1], 0, 4);
          } else {
            $filter_param .= "&sy=0";
          }
          if(is_numeric(substr($filterparts[1], 4, 2))) {
            $filter_param .= "&sm=" . substr($filterparts[1], 4, 2);
          } else {
            $filter_param .= "&sm=0";
          }
          if(is_numeric(substr($filterparts[1], 6, 2))) {
            $filter_param .= "&sd=" . substr($filterparts[1], 6, 2);
          } else {
            $filter_param .= "&sd=0";
          }
          break;
        case "enddate":
          if(strlen($filterparts[1]) != 8) {
            $filter_param .= "&em=0&ed=0&ey=0";
            break;
          }
          if(is_numeric(substr($filterparts[1], 0, 4))) {
            $filter_param .= "&ey=" . substr($filterparts[1], 0, 4);
          } else {
            $filter_param .= "&ey=0";
          }
          if(is_numeric(substr($filterparts[1], 4, 2))) {
            $filter_param .= "&em=" . substr($filterparts[1], 4, 2);
          } else {
            $filter_param .= "&em=0";
          }
          if(is_numeric(substr($filterparts[1], 6, 2))) {
            $filter_param .= "&ed=" . substr($filterparts[1], 6, 2);
          } else {
            $filter_param .= "&ed=0";
          }
          break;
        case "inc-genre":
          if(strpos($filter_param, "&gx=0") === false) $filter_param .= "&gx=0";
          switch(strtolower($filterparts[1])) {
            case "action":
              $filter_param .= "&genre[]=1";
              break;
            case "adventure":
              $filter_param .= "&genre[]=2";
              break;
            case "cars":
              $filter_param .= "&genre[]=3";
              break;
            case "comedy":
              $filter_param .= "&genre[]=4";
              break;
            case "demantia":
              $filter_param .= "&genre[]=5";
              break;
            case "demons":
              $filter_param .= "&genre[]=6";
              break;
            case "mystery":
              $filter_param .= "&genre[]=7";
              break;
            case "drama":
              $filter_param .= "&genre[]=8";
              break;
            case "ecchi":
              $filter_param .= "&genre[]=9";
              break;
            case "fantasy":
              $filter_param .= "&genre[]=10";
              break;
            case "game":
              $filter_param .= "&genre[]=11";
              break;
            case "hentai":
              $filter_param .= "&genre[]=12";
              break;
            case "historical":
              $filter_param .= "&genre[]=13";
              break;
            case "horror":
              $filter_param .= "&genre[]=14";
              break;
            case "kids":
              $filter_param .= "&genre[]=15";
              break;
            case "magic":
              $filter_param .= "&genre[]=16";
              break;
            case "martialarts":
              $filter_param .= "&genre[]=17";
              break;
            case "mecha":
              $filter_param .= "&genre[]=18";
              break;
            case "music":
              $filter_param .= "&genre[]=19";
              break;
            case "parody":
              $filter_param .= "&genre[]=20";
              break;
            case "samurai":
              $filter_param .= "&genre[]=21";
              break;
            case "romance":
              $filter_param .= "&genre[]=22";
              break;
            case "school":
              $filter_param .= "&genre[]=23";
              break;
            case "scifi":
              $filter_param .= "&genre[]=24";
              break;
            case "shoujo":
              $filter_param .= "&genre[]=25";
              break;
            case "shoujoai":
              $filter_param .= "&genre[]=26";
              break;
            case "shounen":
              $filter_param .= "&genre[]=27";
              break;
            case "shounenai":
              $filter_param .= "&genre[]=28";
              break;
            case "space":
              $filter_param .= "&genre[]=29";
              break;
            case "sports":
              $filter_param .= "&genre[]=30";
              break;
            case "superpower":
              $filter_param .= "&genre[]=31";
              break;
            case "vampire":
              $filter_param .= "&genre[]=32";
              break;
            case "yaoi":
              $filter_param .= "&genre[]=33";
              break;
            case "yuri":
              $filter_param .= "&genre[]=34";
              break;
            case "harem":
              $filter_param .= "&genre[]=35";
              break;
            case "sliceoflife":
              $filter_param .= "&genre[]=36";
              break;
            case "supernatural":
              $filter_param .= "&genre[]=37";
              break;
            case "military":
              $filter_param .= "&genre[]=38";
              break;
            case "police":
              $filter_param .= "&genre[]=39";
              break;
            case "psychological":
              $filter_param .= "&genre[]=40";
              break;
            case "thriller":
              $filter_param .= "&genre[]=41";
              break;
            case "seinen":
              $filter_param .= "&genre[]=42";
              break;
            case "josei":
              $filter_param .= "&genre[]=43";
              break;
          }
          break;
        case "exc-genre":
          if(strpos($filter_param, "&gx=1") === false) $filter_param .= "&gx=1";
          switch(strtolower($filterparts[1])) {
            case "action":
              $filter_param .= "&genre[]=1";
              break;
            case "adventure":
              $filter_param .= "&genre[]=2";
              break;
            case "cars":
              $filter_param .= "&genre[]=3";
              break;
            case "comedy":
              $filter_param .= "&genre[]=4";
              break;
            case "demantia":
              $filter_param .= "&genre[]=5";
              break;
            case "demons":
              $filter_param .= "&genre[]=6";
              break;
            case "mystery":
              $filter_param .= "&genre[]=7";
              break;
            case "drama":
              $filter_param .= "&genre[]=8";
              break;
            case "ecchi":
              $filter_param .= "&genre[]=9";
              break;
            case "fantasy":
              $filter_param .= "&genre[]=10";
              break;
            case "game":
              $filter_param .= "&genre[]=11";
              break;
            case "hentai":
              $filter_param .= "&genre[]=12";
              break;
            case "historical":
              $filter_param .= "&genre[]=13";
              break;
            case "horror":
              $filter_param .= "&genre[]=14";
              break;
            case "kids":
              $filter_param .= "&genre[]=15";
              break;
            case "magic":
              $filter_param .= "&genre[]=16";
              break;
            case "martialarts":
              $filter_param .= "&genre[]=17";
              break;
            case "mecha":
              $filter_param .= "&genre[]=18";
              break;
            case "music":
              $filter_param .= "&genre[]=19";
              break;
            case "parody":
              $filter_param .= "&genre[]=20";
              break;
            case "samurai":
              $filter_param .= "&genre[]=21";
              break;
            case "romance":
              $filter_param .= "&genre[]=22";
              break;
            case "school":
              $filter_param .= "&genre[]=23";
              break;
            case "scifi":
              $filter_param .= "&genre[]=24";
              break;
            case "shoujo":
              $filter_param .= "&genre[]=25";
              break;
            case "shoujoai":
              $filter_param .= "&genre[]=26";
              break;
            case "shounen":
              $filter_param .= "&genre[]=27";
              break;
            case "shounenai":
              $filter_param .= "&genre[]=28";
              break;
            case "space":
              $filter_param .= "&genre[]=29";
              break;
            case "sports":
              $filter_param .= "&genre[]=30";
              break;
            case "superpower":
              $filter_param .= "&genre[]=31";
              break;
            case "vampire":
              $filter_param .= "&genre[]=32";
              break;
            case "yaoi":
              $filter_param .= "&genre[]=33";
              break;
            case "yuri":
              $filter_param .= "&genre[]=34";
              break;
            case "harem":
              $filter_param .= "&genre[]=35";
              break;
            case "sliceoflife":
              $filter_param .= "&genre[]=36";
              break;
            case "supernatural":
              $filter_param .= "&genre[]=37";
              break;
            case "military":
              $filter_param .= "&genre[]=38";
              break;
            case "police":
              $filter_param .= "&genre[]=39";
              break;
            case "psychological":
              $filter_param .= "&genre[]=40";
              break;
            case "thriller":
              $filter_param .= "&genre[]=41";
              break;
            case "seinen":
              $filter_param .= "&genre[]=42";
              break;
            case "josei":
              $filter_param .= "&genre[]=43";
              break;
          }
          break;
      }
    }
  } else {
    $filter_param = "";
  }
  
  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : "1";
  $show = ($page - 1) * 50;
  $page_param = "&show=" . $show;
    
  $html = @file_get_html("https://myanimelist.net/anime.php?q=" . urlencode($parts[0]) . $filter_param . "&c[]=a&c[]=b&c[]=c&c[]=d&c[]=e&c[]=f&c[]=g" . $page_param); // c[] parameter for showing all columns
  if(!$html) {
    if($page != 1) {
      $html = @file_get_html("https://myanimelist.net/anime.php?q=" . urlencode($parts[0]) . $filter_param . "&c[]=a&c[]=b&c[]=c&c[]=d&c[]=e&c[]=f&c[]=g&show=0"); // c[] parameter for showing all columns
      if(!$html) {
        echo json_encode(array(
          "error" => "MAL is offline, or their code changed."
        ));
        die();
      }
    } else {
      echo json_encode(array(
        "error" => "MAL is offline, or their code changed."
      ));
      die();
    }
  }
  
  $tr = $html->find("#contentWrapper #content div.list table tbody tr");
  if(count($tr) == 0) {
    echo json_encode(array(
      "parameter" => "q=" . urlencode($parts[0]) . $filter_param,
      "results" => array()
    ));
    die();
  }
  array_shift($tr);
  $results_arr = array();
  foreach($tr as $value) {
    $pictd = $value->find("td", 0);
    $infotd = $value->find("td", 1);
    $typetd = $value->find("td", 2);
    $episodestd = $value->find("td", 3);
    $scoretd = $value->find("td", 4);
    $startdatetd = $value->find("td", 5);
    $enddatetd = $value->find("td", 6);
    $membercounttd = $value->find("td", 7);
    $ratingtd = $value->find("td", 8);
    
    $id = trim(substr(trim($pictd->find("div.picSurround a", 0)->id), 5));
    $image = trim($pictd->find("div.picSurround a img", 0)->srcset);
    $url = trim($infotd->find("a.hoverinfo_trigger", 0)->href);
    $title = trim($infotd->find("a.hoverinfo_trigger strong", 0)->innertext);
    $synopsis = trim(str_replace("read more.", "", $infotd->find("div.pt4", 0)->plaintext));
    $type = trim($typetd->innertext);
    $episodes = trim($episodestd->innertext);
    $score = trim($scoretd->innertext);
    $startdate = trim($startdatetd->innertext);
    $enddate = trim($enddatetd->innertext);
    $membercount = trim($membercounttd->innertext);
    $rating = trim($ratingtd->innertext);
    
    foreach(explode("-", $startdate) as $index => $number) {
      if($index == 0) {
        
      }
      if($index == 1) {
        
      }
      if($index == 2) {
        
      }
    }
    array_push($results_arr, array(
      "id" => $id,
      "image" => $image,
      "url" => $url,
      "title" => $title,
      "synopsis" => $synopsis,
      "type" => $type,
      "episodes" => $episodes,
      "score" => $score,
      "startdate" => $startdate,
      "enddate" => $enddate,
      "member_count" => $membercount,
      "rating" => $rating
    ));
  }
  
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "parameter" => "q=" . urlencode($parts[0]) . $filter_param,
    "results" => $results_arr
  );
  
  // Remove string_ after parse
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo str_replace("string_", "", json_encode($output, JSON_NUMERIC_CHECK));
  
});
?>
