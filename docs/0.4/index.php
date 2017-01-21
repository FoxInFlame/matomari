<?php
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
  "user/reviews/:username",
  "user/recommendations/:username",
  "user/clubs/:username",
  "user/friends/:username",
  "user/comments/:username",
  "user/conversation/:username",
  "user/favorites",
  "user/favorites/:username",
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
  "user/message/thread/:id"
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
    <title>Anime Methods - matomari API Docs</title>
    <link href="../../semantic/semantic.min.css" rel="stylesheet">
    <link href="../../css/docs.css" rel="stylesheet">
  </head>
  <body>
    <div class="ui fixed large menu">
      <div class="ui container">
        <div class="item">
          <img class="logo" src="../../favicon.ico">
          matomari API
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
                  if(strpos($showFile, strtolower($item)) !== false) {
                    echo "<a href=\"" . dirname($_SERVER["PHP_SELF"]) . "/../0.4/" . strtolower($item) . "\" class=\"item active\">" . $item . "</a>\n";
                  } else {
                    echo "<a href=\"" . dirname($_SERVER["PHP_SELF"]) . "/../0.4/" . strtolower($item) . "\" class=\"item\">" . $item . "</a>\n";
                  }
                }
                ?>
              </div>
            </div>
            <a href="<?=dirname($_SERVER["PHP_SELF"])?>/../0.4/responses" class="item">Responses</a>
          </div>
        </div>
      </div>
    </div>
    <div class="main ui container">
      <div class="ui warning icon message">
        <i class="warning sign icon"></i>
        <div class="content">
          <div class="header">Time to get secure!</div>
          <p>matomari is dropping regular HTTP support for requests on Feburary the 23rd. Use HTTPS for everything, baby!</p>
        </div>
      </div>
      <?php
      if($showFile == "index.html") {
        include($showFile);
      } else {
      ?>
      <div class="ui grid">
        <div class="column sixteen wide mobile sixteen wide tablet four wide computer">
          <div class="ui vertical menu secondary pointing">
            <?php
            if(strpos($filename, "anime") !== false) {
              foreach($filenames as $filename) {
                $tmp1 = str_replace("/", ".", $filename);
                if(strpos($tmp1, ":") === false) {
                  $tmp2 = $tmp1;
                } else {
                  $tmp2 = explode(":", $tmp1)[0] . strtoupper(explode(":", $tmp1)[1]);
                }
                if(strpos($filename, "anime") === false) break; // Don't show if the method has nothing to do with this.
                if($showFile_method == $filename) {
                  echo "<a class=\"item active\">" . $showFile_method . "</a>\n";
                } else {
                  echo "<a href=\"" . dirname($_SERVER["PHP_SELF"]) . "/../0.4/" . $tmp2 . "\" class=\"item\">" . $filename . "</a>\n";
                }
              }
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
$time_end = microtime(true);
echo "<!--Dynamic documentation page generated in " . ($time_end - $time_start) . " seconds.-->";
?>