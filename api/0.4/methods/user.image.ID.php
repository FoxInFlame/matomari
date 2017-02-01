<?php
/*

Shows the original images/userimages/:id.jpg (https://myanimelist.net/images/userimages/:id.jpg) with
Access-Control-Allow-Origin header.

This method is made for use in QuickMyAnimeList, and is therefore outputted in lower quality.

Method: GET
Authentication: None Required.
Parameters:
  - quality [Optional] - 1-100

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
header("Content-Type: image/jpeg");

if(!isset($_GET["id"]) || empty($_GET["id"])){
  http_response_code(400);
  return;
} else {
  $userid = $_GET["id"];
}

$image = @imagecreatefromjpeg("https://myanimelist.cdn-dena.com/images/userimages/" . $userid . ".jpg");
if(!$image) {
  http_response_code(400);
  return;
}

if(isset($_GET['quality']) && !empty($_GET['quality']) && !is_numeric($_GET['quality']) && $_GET['quality'] > 0 && $_GET['quality'] < 100) {
  imagejpeg($image, null, $_GET['qualiyt']); // Optional quality between 1 and 100
} else {
  imagejpeg($image, null, 30); // Half quality (1-100; default 75) so QuickMyAnimeList can handle it
}
?>