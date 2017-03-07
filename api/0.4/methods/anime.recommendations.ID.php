<?php
/*

Shows recommendations from an anime.

Method: GET
        /anime/recommendations/:id
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

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------READING THE REQUEST------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $id = isset($_GET['id']) ? $_GET['id'] : "";
  if(empty($id)) {
    echo json_encode(array(
      "message" => "The id parameter is not defined."
    ));
    http_response_code(400);
    return;
  }
  if(!is_numeric($id)) {
    echo json_encode(array(
      "message" => "Specified anime id is not a number."
    ));
    http_response_code(400);
    return;
  }

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/anime/" . $id . "/FoxInFlameIsAwesome/userrecs");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response_string = curl_exec($ch);
  if(!$response_string) {
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
  
  $html = str_get_html($response_string);
  
  if(!is_object($html)) {
    echo json_encode(array(
      "message" => "The code for MAL is not valid HTML markup."
    ));
    http_response_code(500);
    return;
  }

  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------GET/SET THE VALUES-------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $str = $html->find("#content table tbody tr", 0);
  $recommendations = $html->find("div");
  
  $recommendations_arr = array();
  foreach($recommendations as $recommendation) {
    if(strpos($recommendation->class, "borderClass") === false) continue;
    $to = $recommendation->find("table td", 0);
    if(!$to) continue;
    if(!$to->find("a.hoverinfo_trigger", 0)) continue;
    
    $to_anime = new Anime();
    
    $to_anime->set("id", explode("/", $to->find(".picSurround a", 0)->href)[2]);
    $to_anime->set("image", $to->find(".picSurround a img", 0)->{'data-srcset'});
    $to_anime->set("title", $recommendation->find("table td a strong", 0)->innertext);
    $reason = explode("\r\n", substr($recommendation->find("table td", 1)->children(2)->find("div", 0)->plaintext, 0, -6));
    $reason = str_replace(" &nbspread more", "", htmlspecialchars_decode(html_entity_decode(join("<br>", $reason), 0, "UTF-8")));
    $author = trim($recommendation->find("table td", 1)->children(2)->children(1)->find("a", 1)->innertext);
    $other = count($recommendation->find("table td a strong")) !== 1 ? $recommendation->find("table td a strong", 1)->innertext : "0";
    $other_arr = array();
    $other_elem = $recommendation->find("[id^=simaid]", 0);
    if($other_elem) {
      foreach($other_elem->find(".borderClass") as $otherrec) {
        array_push($other_arr, array(
          "reason" => str_replace(" &nbspread more", "", htmlspecialchars_decode(html_entity_decode(join("<br>", explode("\r\n", substr($otherrec->find(".spaceit_pad", 0)->plaintext, 0, -6))), 0, "UTF-8"))),
          "author" => $otherrec->find(".spaceit_pad", 1)->find("a", 1)->innertext
        ));
      }
    }
    array_push($recommendations_arr, array(
      "to" => array(
        "id" => $to_anime->get("id"),
        "image" => array(
          "full" => $to_anime->get("image")[0],
          "min" => $to_anime->get("image")[1]
        ),
        "title" => $to_anime->get("title")
      ),
      "reason" => $reason,
      "author" => $author,
      "other_reviews" => array(
        "total" => $other,
        "items" => $other_arr
      )
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
  
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo json_encode($output, JSON_NUMERIC_CHECK);
  http_response_code(200);
  
});

?>