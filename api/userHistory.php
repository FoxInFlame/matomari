<?php
// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] -------------------HEADERS-------------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("access-control-allow-origin: *");
header('Content-Type: application/json');
require("../SimpleHtmlDOM.php");


// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] --------------GETTING THE VALUES-------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

$parts = isset($_GET["username"]) ? explode("/",$_GET["username"]) : array();
if(empty($parts)) {
  echo json_encode(array(
    "error" => "The username parameter is not defined."
  ));
  die();
}
$type = "anime";
if(isset($_GET["type"])) {
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
$html = @file_get_html("https://myanimelist.net/history/" . $parts[0] . "/" . $type);
if(!$html) {
  echo json_encode(array(
    "error" => "Username was not found or MAL is offline."
  ));
  die();
}


//    [+] ============================================== [+]
//    [+] --------------SETTING THE VALUES-------------- [+]
//    [+] ============================================== [+]

$username = $parts[0];
$mal_link = "https://myanimelist.net/profile/" . $username;
$history_tr = $html->find("div#contentWrapper div#content table tbody tr");
$list = array();
foreach($history_tr as $tr) {
  if(!$tr->find("td.borderClass")) {
    continue;
  } else {
    if($type == "anime") {
      array_push($list, array(
        "title" => $tr->find("td.borderClass a", 0)->innertext,
        "id" => substr($tr->find("td.borderClass a", 0)->href, 14),
        "episode" => $tr->find("td.borderClass strong", 0)->innertext,
        "time" => getAbsoluteTimeGMT($tr->find("td.borderClass", 1)->plaintext)->format("c")
      ));
    } else if($type == "manga") {
      array_push($list, array(
        "title" => $tr->find("td.borderClass a", 0)->innertext,
        "id" => substr($tr->find("td.borderClass a", 0)->href, 14),
        "chapter" => $tr->find("td.borderClass strong", 0)->innertext,
        "time" => getAbsoluteTimeGMT($tr->find("td.borderClass", 1)->plaintext)->format("c")
      ));
    }
    continue;
  }
}

function getAbsoluteTimeGMT($string) {
  $string = trim($string); // Super important! :)
  if(strpos($string, "ago") !== false) {
    /*Note: These are returning approximate values */
    $date = new DateTime(null);
    $date->setTimeZone(new DateTimeZone("Etc/GMT"));
    if(strpos($string, "hour") !== false) {
      if(strpos($string, "hours") !== false) {
        $hours = substr($string, 0, -10);
        $date->modify("-" . $hours . " hours");
      } else {
        $hour = substr($string, 0, -9);
        $date->modify("-" . $hour . " hour");
      }
    }
    if(strpos($string, "minute") !== false) {
      if(strpos($string, "minutes") !== false) {
        $minutes = substr($string, 0, -12);
        $date->modify("-" . $minutes . " minutes");
      } else {
        $minute = substr($string, 0, -11);
        $date->modify("-" . $minute . " minute");
      }
    }
    if(strpos($string, "second") !== false) {
      if(strpos($string, "seconds") !== false) {
        $seconds = substr($string, 0, -12);
        $date->modify("-" . $seconds . " seconds");
      } else {
        $second = substr($string, 0, -11);
        $date->modify("-" . $second . " second");
      }
    }
    return $date;
  } else if(strpos($string, "Today") !== false) {
    $date = date_create_from_format("g:i A", substr($string, 7), new DateTimeZone("Etc/GMT+8"))->setTimeZone(new DateTimeZone("Etc/GMT"));
    return $date;
  } else if(strpos($string, "Yesterday") !== false) {
    $date = date_create_from_format("g:i A", substr($string, 11), new DateTimeZone("Etc/GMT+8"))->setTimeZone(new DateTimeZone("Etc/GMT"));
    $date->modify("-1 day");
    return $date;
  } else {
    // "M j, g:i A" is the date type MAL shows
    $date = date_create_from_format("M j, g:i A", $string, new DateTimeZone("Etc/GMT+8"))->setTimeZone(new DateTimeZone("Etc/GMT"));
    return $date;
  }
}

// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] ------------DISPLAYING THE VALUES------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

// Remove string_ after parse
// JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
echo str_replace("string_", "", json_encode($list, JSON_NUMERIC_CHECK));
?>