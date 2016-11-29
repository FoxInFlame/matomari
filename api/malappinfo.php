<?php
header("Content-Type: text/xml");
header("access-control-allow-origin: *");

// Global Variables (and their defaults).
$username;
$type = "anime";
$status = "all";

// Check for username
if(!isset($_GET["u"]) || empty($_GET["u"])){
  echo "<myanimelist/>";
  exit();
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
?>
