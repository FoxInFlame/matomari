<?php
/*

Get a random anime. It will 302 redirect to /anime/info/:id

This method is not cached.
Method: GET
        /anime/random
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

call_user_func(function() {
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] ----------GETTING A RANDOM ANIME ID----------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $success_id = false;
  while (!$success_id) {
    $id = mt_rand(1, 35545); // Highest as of May 21, 2017
    
    if(in_array($id, $not_arr)) continue; // Skip if it's in the not_arr
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://myanimelist.net/includes/ajax.inc.php?t=64&id=" . $id); // Pretty light-weight
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
  
    curl_close($ch);
    if(strpos($response, "No such series found") === false) {
      $success_id = $id;
    }
  }
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] -----------------REDIRECTING------------------ [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  http_response_code(302);
  header('Location: /api/0.4/src/methods/anime.info.ID.php?id=' . $success_id, true, 302); // TODO: Change this URL to REST

});