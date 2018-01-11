<?php
/*

Status: Completed and Tested.
Shows anime search results for a query. Maximum 50 results. Filtering supported.

This method is cached for a day. Set the nocache parameter to true to use a fresh version (slower).
Method: GET
        /anime/search/:query
Authentication: None Required.
Can someone please find out what the o parameter does in the MAL anime search?
Parameters:
  - page: [Optional] Page number. If page doesn't exist, becomes 1. (Defaults to 1)
  - filter: [Optional] Filters, seperated by comma.
    - Examples:
      /search/Pokemon?filter=inc-genre:action,score=9 // Includes genre "Action" with score of 9 with query  of Pokemon as JSON
      /search/Naruto?filter=type:tv,rating:pg // TV with rating of PG-13, with query of Pokemon as XML
      /search/?filter=inc-genre:action // All anime with action genre as JSON

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
header("Cache-Control: max-age=604800, public"); // 1 week
require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");
require_once(dirname(__FILE__) . "/../parsers/parser.anime.search.QUERY.php");
require_once(dirname(__FILE__) . "/../classes/class.cache.php");

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------GETTING THE VALUES-------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
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
            case "pg-13":
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
          $sy = $sm = $sd = "0";
          if(is_numeric(substr($filterparts[1], 0, 4))) {
            $sy = (int)substr($filterparts[1], 0, 4);
          }
          if(is_numeric(substr($filterparts[1], 4, 2))) {
            $sm = (int)substr($filterparts[1], 4, 2);
          }
          if(is_numeric(substr($filterparts[1], 6, 2))) {
            $sd = (int)substr($filterparts[1], 6, 2);
          }
          $filter_param .= "&sy=" . $sy . "&sm=" . $sm . "&sd=" . $sd;
          break;
        case "enddate":
          if(strlen($filterparts[1]) != 8) {
            $filter_param .= "&em=0&ed=0&ey=0";
            break;
          }
          $ey = $em = $ed = "0";
          if(is_numeric(substr($filterparts[1], 0, 4))) {
            $ey = (int)substr($filterparts[1], 0, 4);
          }
          if(is_numeric(substr($filterparts[1], 4, 2))) {
            $em = (int)substr($filterparts[1], 4, 2);
          }
          if(is_numeric(substr($filterparts[1], 6, 2))) {
            $ed = (int)substr($filterparts[1], 6, 2);
          }
          $filter_param .= "&ey=" . $ey . "&em=" . $em . "&ed=" . $ed;
          break;
        case "startswithletter":
          if(strlen($filterparts[1]) != 1) {
            break;
          }
          if(!preg_match("/^[a-zA-Z]$/", $filterparts[1])) {
            break;
          }
          $filter_param .= "&letter=" . $filterparts[1];
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
            case "dementia":
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
    if(!isset($_GET['q'])) {
      echo json_encode(array(
        "message" => "The q parameter is not defined."
      ));
      http_response_code(400);
      return;
    }
    $filter_param = "";
  }

  if(!isset($_GET['q'])) $_GET['q'] = "";
  if(strlen($_GET['q']) < 3 && isset($_GET['q'])) {
    switch(strlen($_GET['q'])) {
      // MAL doesn't accept queries less than 3 characters (doesn't load)
      // but if you add spaces, it still shows the error message but results will load.
      case 2:
        $_GET['q'] = $_GET['q'] . " "; // Add one space to make it three letters, don't percent encode it, because it will automatically
        break;
      case 1:
        $_GET['q'] = $_GET['q'] . "  "; // Add two spaces to make it three letters
        break;
    }
  }
  
  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : "1";
  $show = ($page - 1) * 50;
  $page_param = "&show=" . $show;
  
  $url = "https://myanimelist.net/anime.php?q=" . urlencode($_GET['q']) . $filter_param . "&c[]=a&c[]=b&c[]=c&c[]=d&c[]=e&c[]=f&c[]=g" . $page_param; // c[] parameter for showing all columns
  $data = new Data();
  
  if($data->getCache($url)) {
    $content = $data->data;
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
      if($page != 1) { // If page isn't one, try one
        if($data->getCache("https://myanimelist.net/anime.php?q=" . urlencode($_GET['q']) . $filter_param . "&c[]=a&c[]=b&c[]=c&c[]=d&c[]=e&c[]=f&c[]=g")) {
          $response = $data->data;
        } else {
          curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/anime.php?q=" . urlencode($_GET['q']) . $filter_param . "&c[]=a&c[]=b&c[]=c&c[]=d&c[]=e&c[]=f&c[]=g");
          $response = curl_exec($ch);
          curl_close($ch);
        }
      } else {
        curl_close($ch);
        echo json_encode(array(
          "message" => "MAL is offline."
        ));
        http_response_code(404);
        return;
      }
    }
    
    $data->saveCache($url, $response);
    $content = $response;
  }
    
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ---------------------PARSE-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]  
  
  $results_arr = AnimeSearchQUERYParser::parse($content);
  
  if(!$results_arr) return;

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "parameter" => "q=" . urlencode($_GET['q']) . $filter_param,
    "page" => (int)$page,
    "items" => $results_arr
  );
  
  echo json_encode($output);
  http_response_code(200);
  
});
?>