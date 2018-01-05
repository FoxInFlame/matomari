<?php

require_once(dirname(__FILE__) . "/model.php");

class AnimeReview extends Model {

  public $info = array(
    "id" => null,
    "mal_url" => null,
    "target" => array(
      "id" => null,
      "title" => null
    ),
    "episodes_seen" => null,
    "helpful_count" => null,
    "ratings" => array(
      "overall" => null,
      "story" => null,
      "animation" => null,
      "sound" => null,
      "character" => null,
      "enjoyment" => null
    ),
    "review" => null,
    "author" => array(
      "username" => null,
      "mal_url" => null,
      "image_url" => null
    ),
    "timestamp" => null
  );
  
}
?>