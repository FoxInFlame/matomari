<?php
header("access-control-allow-origin: *");
header("Content-Type: application/json");
require("../SimpleHtmlDOM.php");

// -----------------------------------------------
// IF TOPIC ID IS NOT DEFINED
// -----------------------------------------------
$parts = isset($_GET["id"]) ? explode("/",$_GET["id"]) : array();
if(empty($parts)) {
  echo json_encode(array(
    "error" => "The id parameter is not defined."
  ));
  die();
}
if(!is_numeric($parts[0])) {
  echo json_encode(array(
    "error" => "Specified topic id is not a number."
  ));
  die();
}
$html = @file_get_html("https://myanimelist.net/forum/?topicid=" . $parts[0]);
if(!$html) {
  echo json_encode(array(
    "error" => "Topic with specified id was not found."
  ));
  die();
}
    
$forum = $html->find("#content", 0)->children();
$bottom = $forum[count($forum) - 1];
$count = $bottom->find("div", 1);

if(empty($count->innertext)) {
  $pageCount = 1;
  $last = $html->find("#content", 0)->find(".forum_border_around", -1);
  $postCount = $last->find(".postnum", 0)->innertext;
} else {
  $pageCount = explode(")", explode("(", $count->innertext)[1])[0];
  $lastPage = @file_get_html("https://myanimelist.net/forum/?topicid=" . $parts[0] . "&show=" . ($pageCount - 1)*50);
  if(!$lastPage) {
    echo json_encode(array(
      "error" => "Topic has an page that doesn't exist."
    ));
    exit(404);
  }
  $forum_last = $lastPage->find("#content", 0)->children();
  $last_last = $lastPage->find("#content", 0)->find(".forum_border_around", -1);
  $postCount = $last_last->find(".postnum", 0)->innertext;
}

$output = array(
  "id" => $parts[0],
  "page_count" => $pageCount,
  "post_count" => $postCount
);

// JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
echo json_encode($output, JSON_NUMERIC_CHECK);
?>