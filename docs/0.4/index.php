<?php
ini_set("display_errors", true);
ini_set("display_startup_errors", true);
error_reporting(E_ALL);


$time_start = microtime(true);
$showFile = "index.html"; // Default page - 0.4/index.html
$showFile_method = "";
$filenames = array( // Available methods
  "anime",
  "anime/top",
  "anime/search/:query",
  "anime/recommendations",
  "anime/info/:id",
  "anime/reviews/:id",
  "anime/recommendations/:id",
  "anime/stats/:id",
  "anime/recent/:id",
  "anime/characters/:id",
  "anime/staff/:id",
  "anime/news/:id",
  "anime/forum/:id",
  "anime/articles/:id",
  "anime/clubs/:id",
  "anime/pictures/:id",
  "anime/moreinfo/:id",
  
  "users",
  "users/search/:query",
  "users/recent",
  "users/recommendations",
  "user/info/:username",
  "user/stats/:username",
  "user/favorites",
  "user/reviews/:username",
  "user/recommendations/:username",
  "user/clubs/:username",
  "user/friends/:username",
  "user/comments/:username",
  "user/conversation/:username",
  "user/list/anime/:id",
  "user/list/manga/:id",
  "user/list/history/anime/:id",
  "user/list/history/manga/:id",
  "user/history/:username",
  "user/notifications",
  "user/notifications/:id",
  "user/messages",
  "user/messages/:id",
  "user/message",
  "user/message/thread/:id",
  
  "settings",
  "settings/profile",
  "settings/favorites",
  "settings/forum",
  "settings/image",
  
  "clubs",
  "clubs/recent",
  "clubs/me",
  "club/info/:id",
  "club/comments/:id",
  "club/members/:id",
  "club/forum/:id",
  "club/forum/:id",
  
  "forum",
  "forum/top",
  "forum/recent",
  "forum/search/:query",
  "forum/board/:id",
  "forum/topic/:id",
  "forum/watched",
  "forum/ignored",
  
  "blogs",
  "blogs/recent",
  "blog/posts/:username",
  "blog/post",
  "blog/post/:id",
  "blog/comments/:id",
  "blog/comments/:id",
  
  "news",
  "news/top",
  "news/team",
  
  "articles",
  "articles/top",
  "articles/columnists",
  "articles/search/:query",
  "article/:id",
  
  "people",
  "people/top",
  "people/search/:query",
  "people/info/:id",
  "people/news/:id",
  "people/pictures/:id",
  
  "characters",
  "characters/top",
  "characters/search/:query",
  "character/info/:id",
  "character/particles/:id",
  "character/pictures/:id",
  "character/clubs/:id",
  
  "general",
  "general/quickSearch/:query",
  "general/wallpaper",
  "general/malappinfo.php",
  
  "responsecodes"
);
if(isset($_GET['file'])) {
  foreach($filenames as $filename) {
    $tmp1 = str_replace("/", ".", $filename); // Reformat method URL to file URL
    if(strpos($tmp1, ":") === false) {
      $tmp2 = $tmp1;
    } else {
      $tmp2 = explode(":", $tmp1)[0] . strtoupper(explode(":", $tmp1)[1]);
    }
    if($tmp2 == $_GET['file']) {
      $showFile_method = $filename;
      $showFile = $tmp2 . ".html";
      break;
    }
  }
}
?>
<!DOCTYPE HTML>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    if($showFile == "index.html") {
    ?>
    <title>Version 0.4 - matomari API Docs</title>
    <?php
    } else if($showFile == "responsecodes.html") {
    ?>
    <title>Response Codes - 0.4 | matomari API Docs</title>
    <?php
    } else {
    ?>
    <title><?=$showFile_method?> - 0.4 | matomari API Docs</title>
    <?php
    }
    ?>
    <link href="../../semantic/semantic.min.css" rel="stylesheet">
    <link href="../../css/docs.css" rel="stylesheet">
  </head>
  <body>
    <div class="ui fixed large menu">
      <div class="ui container">
        <div class="item">
          <img class="logo" src="../../favicon.ico">
        </div>
        <a class="item" href="https://github.com/FoxInFlame/matomari">GitHub</a>
        <div class="ui dropdown item">
          0.3
          <div class="menu">
            <div class="item">
              Methods
              <div class="menu">
                <a href="<?=dirname($_SERVER["PHP_SELF"])?>/../0.3/general" class="item">General</a>
                <a href="<?=dirname($_SERVER["PHP_SELF"])?>/../0.3/anime" class="item">Anime</a>
                <a href="<?=dirname($_SERVER["PHP_SELF"])?>/../0.3/users" class="item">Users</a>
                <a href="<?=dirname($_SERVER["PHP_SELF"])?>/../0.3/clubs" class="item">Clubs</a>
                <a href="<?=dirname($_SERVER["PHP_SELF"])?>/../0.3/forum" class="item">Forum</a>
              </div>
            </div>
            <a href="<?=dirname($_SERVER["PHP_SELF"])?>/../0.3/responses" class="item">Responses</a>
          </div>
        </div>
        <div class="ui dropdown item">
          0.4
          <div class="menu">
            <div class="item">
              Methods
              <div class="menu">
                <?php
                $fixedMenuItems = array("General", "Anime", "Users", "Settings", "Clubs", "Forum", "Blogs", "News", "Articles", "People", "Characters");
                foreach($fixedMenuItems as $item) {
                  if($item == "Users") {
                    $item2 = "User";
                  } else {
                    $item2 = $item;
                  }
                  if(strpos($showFile, strtolower($item2)) !== false) {
                    echo "<a href=\"" . dirname($_SERVER["PHP_SELF"]) . "/../0.4/" . strtolower($item) . "\" class=\"item active\">" . $item . "</a>\n";
                  } else {
                    echo "<a href=\"" . dirname($_SERVER["PHP_SELF"]) . "/../0.4/" . strtolower($item) . "\" class=\"item\">" . $item . "</a>\n";
                  }
                }
                ?>
              </div>
            </div>
            <a href="<?=dirname($_SERVER["PHP_SELF"])?>/../0.4/responsecodes" class="item">Responses</a>
          </div>
        </div>
      </div>
    </div>
    <div class="main ui container">
      <div class="ui warning icon message">
        <i class="warning sign icon"></i>
        <div class="content">
          <div class="header">Attention!</div>
          <p>Since the redirects have not been setup yet, the methods will not work unless you call the exact file name as in the source code.</p>
        </div>
      </div>
      <?php
      if($showFile == "index.html" || $showFile == "responsecodes.html") {
        include($showFile);
      } else {
      ?>
      <div class="ui grid">
        <div class="column sixteen wide mobile sixteen wide tablet four wide computer" style="word-wrap:break-word">
          <div class="ui fluid vertical menu secondary pointing">
            <?php
            function showSidebar($basename) {
              global $filenames;
              global $showFile_method;
              foreach($filenames as $filename) {
                $tmp1 = str_replace("/", ".", $filename);
                if(strpos($tmp1, ":") === false) {
                  $tmp2 = $tmp1;
                } else {
                  $tmp2 = explode(":", $tmp1)[0] . strtoupper(explode(":", $tmp1)[1]);
                }
                if(substr($filename, 0, strlen($basename)) !== $basename) continue; // Don't show if the method doesn't start with basename. Used this instead of strpos() because some methods have basenames in their method names (e.g. user/history/anime/:id).
                if($showFile_method == $filename) {
                  echo "<a class=\"item active\">" . $showFile_method . "</a>\n";
                } else {
                  echo "<a href=\"" . dirname($_SERVER["PHP_SELF"]) . "/../0.4/" . $tmp2 . "\" class=\"item\">" . $filename . "</a>\n";
                }
              }
            }
            switch(strtolower(explode("/", $filename)[0])) {
              case "anime":
                showSidebar("anime");
                break;
              case "users":
              case "user":
                showSidebar("user");
                break;
              case "settings":
                showSidebar("settings");
                break;
              case "clubs":
              case "club":
                showSidebar("club");
                break;
              case "forum":
                showSidebar("forum");
                break;
              case "blogs":
              case "blog":
                showSidebar("blog");
                break;
              case "news":
                showSidebar("news");
                break;
              case "articles":
              case "article":
                showSidebar("article");
                break;
              case "people":
                showSidebar("people");
                break;
              case "characters":
              case "character":
                showSidebar("character");
                break;
              case "general":
                showSidebar("general");
                break;
            }
            ?>
          </div>
        </div>
        <div class="column sixteen wide mobile sixteen wide tablet twelve wide computer">
          <div class="ui segment">
            <?php
            if(file_exists($showFile)) {
              echo "<h1 class=\"ui header\">" . $showFile_method . " <a title=\"View Source for method\" href=\"https://github.com/FoxInFlame/matomari/tree/0.4/api/0.4/methods/" . substr($showFile, 0, -5) . ".php\"><i class=\"icon external\" style=\"float:right\"></i></a></h1>";
              include($showFile);
            } else {
              echo "No documentation has been created on this page.";
            }
            echo "\n";
            ?>
          </div>
        </div>
      </div>
      <?php
      }
      ?>
    </div>
    <script src="../../js/jquery.min.js"></script>
    <script src="../../semantic/semantic.min.js"></script>
    <script src="../../js/docs.js"></script>
  </body>
</html>
<?php
echo "<!--File loaded: " . $showFile . "-->\n";
$time_end = microtime(true);
echo "<!--Dynamic documentation page generated in " . ($time_end - $time_start) . " seconds.-->";
?>