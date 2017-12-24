<?php
/*

Shows the original malappinfo.php (https://myanimelist.net/malappinfo.php) with
Access-Control-Allow-Origin header.

Method: GET
Authentication: None Required.
Parameters:
  - u: [Required] MAL Username.
  - type: [Optional] Anime or manga (Defaults to anime)
  - status: [Optional] No idea what this does.

Created by FoxInFlame.
A Part of the matomari API.

*/

// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] -------------------HEADERS-------------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache, must-revalidate");
header("Content-Type: application/xml");

// Global Variables (and their defaults).
$username;
$type = "anime";
$status = "all";

// Check for username
if(!isset($_GET["u"]) || empty($_GET["u"])){
  echo "<myanimelist/>";
  http_response_code(400);
  return;
} else {
  $username = $_GET["u"];
}

// Check for type
if(isset($_GET["type"]) && !empty($_GET["type"])) {
  // I really love switch/case...
  switch($_GET["type"]) {
    case "anime":
      $type = "anime";
      break;
    case "manga":
      $type = "manga";
      break;
    default:
      $type = "anime";
  }
}

// Check for status
if(isset($_GET["status"]) && !empty($_GET["status"])) {
  $status = $_GET["status"];
}

// cURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://myanimelist.net/malappinfo.php?u=".$username."&type=".$type."&status=".$status); // Set the URL
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // Return the content
echo curl_exec($curl); // Execute the request, and show the response
http_response_code(200);
?>