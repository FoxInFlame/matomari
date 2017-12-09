<?php
ini_set("display_errors", true);
ini_set("display_startup_errors", true);
error_reporting(E_ALL);


$time_start = microtime(true);
$showFile = "main.php"; // Default page - 0.4/index.html
$showFile_method = "";
require_once("methods.php");
array_push($filenames, "responsecodes");
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
      $showFile = $tmp2 . ".php";
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
    <link href="../semantic/semantic.min.css" rel="stylesheet">
    <link href="../css/0.4/docs.css" rel="stylesheet">

    <script src="../js/jquery.min.js"></script>
    <script src="../js/jquery.address-1.6.js"></script>
    <script src="../semantic/semantic.min.js"></script>
  </head>
  <body>
    <div class="ui fixed large menu">
      <div class="ui container">
        <a href="/" class="item">
          <img class="logo" src="../../favicon.ico">
        </a>
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
            <a href="<?=dirname($_SERVER["PHP_SELF"])?>/../0.4/" class="item">Main Page</a>
            <div class="item">
              Methods
              <div class="menu">
                <?php
                $fixedMenuItems = array("General", "Anime", "@me", "Users", "Settings", "Clubs", "Forum", "Blogs", "News", "Articles", "People", "Characters");
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
      if($showFile == "main.php" || $showFile == "responsecodes.php") {
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
              case "@me":
                showSidebar("@me");
                break;
              case "users":
              case "user":
                showSidebar("user");
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
              if(count(explode("/", $showFile_method)) == 1) {
                echo "<h1 class=\"ui header\">" . $showFile_method . "</h1><br>";
                include($showFile);
              } else {
                echo "<h1 class=\"ui header\">" . $showFile_method . " <a title=\"View Source for method\" href=\"https://github.com/FoxInFlame/matomari/tree/0.4/api/0.4/src/methods/" . substr($showFile, 0, -5) . ".php\"><i class=\"icon external\" style=\"float:right\"></i></a></h1><br>";
                include($showFile);
              }
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
    <script src="../js/docs.js"></script>
  </body>
</html>
<?php
echo "<!--File loaded: " . $showFile . "-->\n";
$time_end = microtime(true);
echo "<!--Dynamic documentation page generated in " . ($time_end - $time_start) . " seconds.-->";
?>