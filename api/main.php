<?php
// Headers are sent from individual files, so no need to declare them here.

$request = $_GET["url"];
$request_parts = explode("/", $request);

require("json_to_xml.php");

dieIfNotSet($request_parts[0]);

switch($request_parts[0]) {
  case "anime": // anime/
    dieIfNotSet($request_parts[1]);
    dieIfNotSet($request_parts[2]);
    $_GET["id"] = $request_parts[2];
    switch($request_parts[1]) {
      case "info": // anime/info/
        showOutput("anime/info.php", $_GET["type"]);
        break;
      case "episodes": // anime/episodes/
        break;
      default:
        die("Invalid Request");
        break;
    }
    break;
  case "user": // user/
    dieIfNotSet($request_parts[1]);
    dieIfNotSet($request_parts[2]);
    $_GET["username"] = $request_parts[2];
    switch($request_parts[1]) {
      case "info": // user/info/
        showOutput("user/info.php", $_GET["type"]);
        break;
      case "notifications": // user/notifications/
        showOutput("user/notifications.php", $_GET["type"]);
        break;
      case "history": // user/history/
        showOutput("user/history.php", $_GET["type"]);
        break;
      default:
        die("Invalid Request");
        break;
    }
    break;
  case "forum": // forum/
    dieIfNotSet($request_parts[1]);
    dieIfNotSet($request_parts[2]);
    $_GET["topicid"] = $request_parts[2];
    switch($request_parts[1]) {
      case "topic": // forum/topic/
        showOutput("forum/topic.php", $_GET["type"]);
        break;
      default:
        die("Invalid Request.");
        break;
    }
    break;
  case "general": // general/
    // Files in the general category should be accessed directory through the file and not the REST interface.
    die("Invalid Request.");
    break;
  case "club": // club/
    dieIfNotSet($request_parts[1]);
    dieIfNotSet($request_parts[2]);
    $_GET["clubid"] = $request_parts[2];
    switch($request_parts[1]) {
      case "info": // club/info/
        showOutput("club/info.php", $_GET["type"]);
        break;
      default:
        die("Invalid Request.");
        break;
    }
    break;
  default:
    die("Invalid Request.");
    break;
}

function dieIfNotSet($part) {
  if(empty($part) || !isset($part)) {
    die("Invalid Request");
    break;
  }
}

function showOutput($request_file, $request_filetype) {
  ob_start();
  include($request_file);
  $response_json = ob_get_clean();
  switch(trim(strtolower($request_filetype))) {
    case "json":
      header("Content-Type: application/json");
      echo $response_json;
      break;
    case "xml":
      header("Content-Type: application/xml");
      echo json_to_xml($response_json);
      break;
    default:
      die("Invalid File Type.");
      break;
  }
}
?>
