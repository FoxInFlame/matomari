
<?php
/*

Shows the original images/userimages/:id.jpg (https://myanimelist.net/images/userimages/:id.jpg) with
Access-Control-Allow-Origin header.

Method: GET
Authentication: None Required.
Parameters:
  - None

Created by FoxInFlame.
A Part of the matomari API.

*/

// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] -------------------HEADERS-------------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

header("access-control-allow-origin: *");
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

imagejpeg($image);
?>