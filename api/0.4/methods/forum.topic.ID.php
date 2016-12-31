<?php
/*

Shows basic content on a MAL forum thread.
Still WIP.

Method: GET
        /forum/topic/:id
Authentication: None Required.
Parameters:
  - page: [Optional] Page number. If the page doesn't exist, it becomes 1. (Defaults to 1)

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

call_user_func(function() {
  
  // -----------------------------------------------
  // IF TOPIC ID IS NOT DEFINED
  // -----------------------------------------------
  $parts = isset($_GET['topicid']) ? explode("/",$_GET['topicid']) : array();
  if(empty($parts)) {
    echo json_encode(array(
      "error" => "The id parameter is not defined."
    ));
    http_response_code(400);
    return;
  }
  if(!is_numeric($parts[0])) {
    echo json_encode(array(
      "error" => "Specified topic id is not a number."
    ));
    http_response_code(400);
    return;
  }
  $html = @file_get_html("https://myanimelist.net/forum/?topicid=" . $parts[0]);
  if(!$html) {
    echo json_encode(array(
      "error" => "Topic with specified id was not found."
    ));
    http_response_code(404);
    return;
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
        "error" => "Topic has a page that doesn't exist."
      ));
    http_response_code(502);
      return;
    }
    $forum_last = $lastPage->find("#content", 0)->children();
    $last_last = $lastPage->find("#content", 0)->find(".forum_border_around", -1);
    $postCount = $last_last->find(".postnum", 0)->innertext;
  }
  
  $posts = [];
  $page = "1";
  if(isset($_GET['page'])) {
    if(is_numeric($_GET['page']) && $_GET['page'] <= $pageCount) {
      $page = $_GET['page'];
    }
  }
  $htmlpage = @file_get_html("https://myanimelist.net/forum/?topicid=" . $parts[0] . "&show=" . (($page - 1)*50));
  if(!$htmlpage) {
    array_push($posts, array(
      "error" => "Topic with specified id was not found."
    ));
    http_response_code(404);
  } else {
    $forum_posts = $htmlpage->find("#content", 0)->find(".forum_border_around");
    foreach($forum_posts as $post) {
      $date = $post->find(".forum_category", 0)->find("div div", 1)->innertext;
      $author = $post->find(".forum-topic-message-wrapper", 0)->find(".forum_topic_message .forum_boardrow2 div div a strong", 0)->innertext;
      $post_content = $post->find(".forum-topic-message-wrapper", 0)->find(".forum_topic_message .forum_boardrow1 div div", 0);
      array_push($posts, array(
        "author" => $author,
        "time" => $date,
        "content" => $post_content->innertext
      ));
    }
  }
  
  
  // [+] ============================================== [+]
  // [+] ---------------------------------------------- [+]
  // [+] --------------------OUTPUT-------------------- [+]
  // [+] ---------------------------------------------- [+]
  // [+] ============================================== [+]
  
  $output = array(
    "id" => $parts[0],
    "page_count" => $pageCount,
    "post_count" => $postCount,
    "posts" => $posts
  );
  
  // JSON_NUMERIC_CHECK flag requires at least PHP 5.3.3
  echo json_encode($output, JSON_NUMERIC_CHECK);
  http_response_code(200);
  
});
?>