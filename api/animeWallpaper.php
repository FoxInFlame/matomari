<?php
// ===============================================
// -----------------------------------------------
// HEADERS
// -----------------------------------------------
// ===============================================
header("access-control-allow-origin: *");

// -----------------------------------------------
// IF SORT IS NOT DEFINED
// -----------------------------------------------
if(isset($_GET["sort"])) {
  if($_GET["sort"] == "hot") {
    $sort = "hot";
  } else if($_GET["sort"] == "new") {
    $sort = "new";
  } else {
    $sort = "new";
  }
} else {
  $sort = "new";
}
// -----------------------------------------------
// IF NSFW IS NOT DEFINED
// -----------------------------------------------
if(isset($_GET["nsfw"])) {
  if($_GET["nsfw"] == "true") {
    $nsfw = "";
  } else if($_GET["nsfw"] == "only") {
    $nsfw = "+nsfw%3Ayes";
  } else {
    $nsfw = "+nsfw%3Ano";
  }
} else {
  $nsfw = "";
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.reddit.com/r/Animewallpaper/search.rss?q=flair%3ADesktop" . $nsfw . "&restrict_sr=on&sort=" . $sort . "&t=all");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

$xml = new SimpleXMLElement($response);

foreach($xml->entry as $entry) {
  $load = loadImage($entry->content);
  if($load !== false) {
    break;
  }
}
function loadImage($content) {
  $dom = new DOMDocument;
  $dom->loadHTML($content);
  $return;
  foreach($dom->getElementsByTagName("a") as $node) {
    if($node->hasAttribute("href")) {
      $link = $node->getAttribute("href");
      if(strpos($link, "reddit.com") !== false) {
        $return = false;
        continue;
      } else {
        if(strpos($link, ".png") !== false) {
          $image = imagecreatefrompng($link);
          header("Content-Type: image/png");
          imagepng($image);
          $return = "png";
          break;
        } else if((strpos($link, ".jpg") !== false) || (strpos($link, ".jpeg") !== false)) {
          $image = imagecreatefromjpeg($link);
          header("Content-Type: image/jpeg");
          imagejpeg($image);
          $return = "jpeg";
          break;
        } else {
          $return = false;
          break;
        }
      }
    }
  }
  return $return;
}
?>