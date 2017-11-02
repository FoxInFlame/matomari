<?php
/*

404 Error when the method doesn't exist.

This is the error that shows when a method doesn't exist.

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

echo json_encode(array(
  "message" => "Method doesn't exist."
));
?>