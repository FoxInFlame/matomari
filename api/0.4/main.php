<?php
// Headers are sent from individual files, so no need to declare them here.

$request = $_GET["url"];
dieIfNotSet($_GET["url"]);

$request_parts = explode("/", $request);

print_r($request_parts);
print_r($_GET);

require("json_to_xml.php");

dieIfNotSet($request_parts[0]);

switch($request_parts[0]) {
  case "anime": // anime/
    dieIfNotSet($request_parts[1]);
    switch($request_parts[1]) {
      case "top": // anime/top
        break;
      case "search": // anime/search/:query
        break;
      case "recommendations": // anime/recommendations
        break;
      case "info": // anime/info/:id
        break;
      case "reviews": // anime/reviews/:id
        break;
      case "recommendations": // anime/recommendations/:id
        break;
      case "stats": // anime/stats/:id
        break;
      case "recent": // anime/recent/:id
        break;
      case "characters": // anime/characters/:id
        break;
      case "staff": // anime/staff/:id
        break;
      case "news": // anime/news/:id
        break;
      case "forum": // anime/forum/:id
        break;
      case "articles": // anime/articles/:id
        break;
      case "clubs": // anime/clubs/:id
        break;
      case "pictures": // anime/pictures/:id
        break;
      case "moreinfo": // anime/moreinfo/:id
        break;
    }
    break;
  case "users":
    dieIfNotSet($request_parts[1]);
    switch($request_parts[1]) {
      case "search": // users/search/:query
        break;
      case "recent": // users/recent
        break;
      case "recommendations": // users/recommendations
        break;
    }
    break;
  case "user":
    dieIfNotSet($request_parts[1]);
    switch($request_parts[1]) {
      case "info": // user/info/:username
        break;
      case "stats": // user/stats/:username
        break;
      case "reviews": // user/reviews/:username
        break;
      case "recommendations": // user/recommendations/:username
        break;
      case "clubs": // user/clubs/:username
        break;
      case "friends": // user/friends/:username
        break;
      case "comments": // user/comments/:username
        break;
      case "conversation": // user/conversation/:username
        break;
      case "list":
        dieIfNotSet($request_parts[2]);
        switch($request_parts[2]) {
          case "anime": // user/list/anime/:id
            break;
          case "manga": // user/list/manga/:id
            break;
          case "animelist": // user/list/animelist/:username
            break;
          case "mangalist": // user/list/mangalist/:username
            break;
          case "history":
            dieIfNotSet($request_parts[3]);
            switch($request_parts[3]) {
              case "anime": // user/list/history/anime/:id
                break;
              case "manga": // user/list/history/manga/:id
                break;
            }
            break;
        }
        break;
      case "history": // user/history/:username
        break;
      case "notifications": // user/notifications
        break;
      case "messages": // user/messages
        break;
      case "message":
        if(!isset($request_parts[2])) { // user/message
          
        } else if(is_numeric($request_parts[2])) { // user/message/:id
          
        } else if($request_parts[2] == "thread") { // user/message/thread/:id
          
        }
        break;
    }
    break;
  case "settings":
    dieIfNotSet($request_parts[1]);
    switch($request_parts[1]) {
      case "profile": // settings/profile
        break;
      case "favorites": // settings/favorites
        break;
      case "forum": // settings/forum
        break;
      case "image": // settings/image
        break;
    }
    break;
  case "clubs":
    dieIfNotSet($request_parts[1]);
    switch($request_parts[1]) {
      case "recent": // clubs/recent
        break;
      case "me": // clubs/me
        break;
    }
    break;
  case "club":
    dieIfNotSet($request_parts[1]);
    switch($request_parts[1]) {
      case "info": // club/info/:id
        break;
      case "comments": // club/comments/:id
        break;
      case "members": // club/members/:id
        break;
      case "forum": // club/forum/:id
        break;
    }
    break;
  case "forum":
    dieIfNotSet($request_parts[1]);
    switch($request_parts[1]) {
      case "top": // forum/top
        break;
      case "recent": // forum/recent
        break;
      case "search": // forum/search/:query
        break;
      case "board": // forum/board/:id
        break;
      case "watched": // forum/watched
        break;
      case "ignored": // forum/ignored
        break;
    }
    break;
  case "blogs":
    dieIfNotSet($request_parts[1]);
    switch($request_parts[1]) {
      case "recent": // blogs/recent
        break;
    }
    break;
  case "blog":
    dieIfNotSet($request_parts[1]);
    switch($request_parts[1]) {
      case "posts": // blog/posts/:username
        break;
      case "post":
        if(!isset($request_parts[2])) { // blog/post
          
        } else { // blog/post/:id
          
        }
        break;
      case "comments": // blog/comments/:id
        break;
    }
    break;
  case "news":
    dieIfNotSet($request_parts[1]);
    switch($request_parts[1]) {
      case "top": // news/top
        break;
      case "team": // news/team
        break;
    }
    break;
  case "articles":
    dieIfNotSet($request_parts[1]);
    switch($request_parts[1]) {
      case "top": // articles/top
        break;
      case "columnists": // articles/columnists
        break;
      case "search": // articles/search/:query
        break;
    }
    break;
  case "article": // article/:id
    dieIfNotSet($request_parts[1]);
    break;
  case "people":
    dieIfNotSet($request_parts[1]);
    switch($request_parts[1]) {
      case "top": // people/top
        break;
      case "search": // people/search/:query
        break;
      case "info": // people/info/:id
        break;
      case "news": // people/news/:id
        break;
      case "pictures": // people/pictures/:id
        break;
    }
    break;
  case "characters":
    dieIfNotSet($request_parts[1]);
    switch($request_parts[1]) {
      case "top": // characters/top
        break;
      case "search": // characters/search/:query
        break;
      case "info": // characters/info/:id
        break;
      case "articles": // characters/articles/:id
        break;
      case "pictures": // characters/pictures/:id
        break;
      case "clubs": // characters/clubs/:id
        break;
    }
    break;
  
  
  case "anime":
    dieIfNotSet($request_parts[2]);
    switch($request_parts[1]) {
      case "info": // anime/info/
        $_GET["id"] = $request_parts[2];
        showOutput("anime/info.php", $_GET["type"]);
        break;
      case "search": // anime/search/
        $_GET["q"] = $request_parts[2];
        if(isset($request_parts[3]) || !empty($request_parts[3])) {
          $_GET["filter"] = $request_parts[3];
        }
        showOutput("anime/search.php", $_GET["type"]);
        break;
      case "episodes": // anime/episodes/
        break;
      default:
        die("Invalid Request.");
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
      case "friends":
        break;
      case "notifications": // user/notifications/
        showOutput("user/notifications.php", $_GET["type"]);
        break;
      case "history": // user/history/
        showOutput("user/history.php", $_GET["type"]);
        break;
      case "messages":
        showOutput("user/messages.php", $_GET["type"]);
        break;
      case "message":
        dieIfNotSet($request_parts[3]);
        switch($request_parts[2]) {
          case "send":
            showOutput("user/message_send.php", $_GET["type"]);
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
  if(!isset($part)) {
    die("Invalid Request.");
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