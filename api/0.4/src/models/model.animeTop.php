<?php

require_once(dirname(__FILE__) . "/model.php");

class AnimeTop extends Model {

  public $info = array(
    "id" => null,
    "title" => null,
    "mal_url" => null,
    "image_url" => null,
    "score" => null,
    "rank" => null,
    "type" => null,
    "episodes" => null,
    "members_inlist" => null,
    "members_favorited" => null
  );
  
}
?>