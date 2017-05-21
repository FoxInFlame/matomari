<?php
/*

Get a random anime. It will 302 redirect to /anime/info/:ID

This method is not cached.
Method: GET
        /anime/random
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

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ----------GETTING A RANDOM ANIME ID----------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $success_id = false;
  while (!$success_id) {
    $id = mt_rand(1, 35545); // Highest as of May 21, 2017
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/anime/" . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($httpcode == 404) {
      $success_id = false;
    } else {
      $success_id = $id;
    }
  }
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] -----------------REDIRECTING------------------ [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  http_response_code(302);
  header('Location: /api/0.4/methods/anime.info.php?id=' . $success_id, true, 302);

});