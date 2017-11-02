<?php
/*

Get latest anime recommendations.

Method: GET
        /anime/recommendations
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
require_once(dirname(__FILE__) . "/../class/class.anime.php");
require_once(dirname(__FILE__) . "/../class/class.cache.php");

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ---------GETTING THE RECOMMENDATIONS---------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : "1";
  $show = ($page - 1) * 100;
  $page_param = "?show=" . $show;
  
  $url = "https://myanimelist.net/recommendations.php" . $page_param;
  $data = new Data();

  $html = null;
  
  if($data->getCache($url)) {
    $html = str_get_html($data->data);
  } else {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if(!$response) {
      if($page != 1) {
        if($data->getCache("https://myanimelist.net/recommendations.php")) {
          $html = str_get_html($data->data);
        } else {
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/recommendations.php");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $response = curl_exec($ch);
          if(!$response) {
            echo json_encode(array(
              "message" => "MAL is offline."
            ));
            http_response_code(404);
            return;
          }
          $html = str_get_html($response);
        }
      } else {
        $html = str_get_html($response);
      }
      echo json_encode(array(
        "message" => "MAL is offline."
      ));
      http_response_code(404);
      return;
    }
    curl_close($ch);
    
    $data->saveCache($url, $response);
  }

  $recommendations = $html->find("#contentWrapper #content", 0)->children(2)->children();
  $recommendations_arr = array();
  foreach($recommendations as $key => $recommendation) {
    if($key === 0) {
      continue;
    }
    if($key === (count($recommendations) - 1)) {
      continue;
    }
    $from = $recommendation->find("table td", 0);
    $from_anime = new Anime();
    $from_anime->set("id", substr($from->find(".picSurround a", 0)->id, 9));
    $from->find(".picSurround a img", 0)->{'data-srcset'} ? $from_anime->set("image", $from->find(".picSurround a img", 0)->{'data-srcset'}) : $from_anime->set("image", $from->find(".picSurround a img", 0)->{'srcset'});
    $from_anime->set("title", $from->find("a strong", 0)->innertext);
    $to = $recommendation->find("table td", 1);
    $to_anime = new Anime();
    $to_anime->set("id", substr($to->find(".picSurround a", 0)->id, 9));
    $to->find(".picSurround a img", 0)->{'data-srcset'} ? $to_anime->set("image", $to->find(".picSurround a img", 0)->{'data-srcset'}) : $to_anime->set("image", $to->find(".picSurround a img", 0)->{'srcset'});
    $to_anime->set("title", $to->find("a strong", 0)->innertext);
    $reason = $recommendation->children(1)->innertext;
    $author = $recommendation->children(2)->find("a", 1)->innertext;
    $time_1 = explode(" - ", $recommendation->children(2)->innertext);
    $time = end($time_1);
    array_push($recommendations_arr, array(
      "from" => array(
        "id" => $from_anime->get("id"),
        "title" => $from_anime->get("title"),
        "image" => $from_anime->get("image")
      ),
      "to" => array(
        "id" => $to_anime->get("id"),
        "title" => $to_anime->get("title"),
        "image" => $to_anime->get("image")
      ),
      "reason" => $reason,
      "author" => $author,
      "time" => getAbsoluteTimeGMT($time, "M j, Y|")->format("c")
    ));
  }
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "items" => $recommendations_arr
  );
  
  // Remove string_ after parse
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo str_replace("string_", "", json_encode($output, JSON_NUMERIC_CHECK));
  http_response_code(200);
  
});
?>
