<?php

require_once(dirname(__FILE__) . "/model.php");

class AnimeSearch extends Model {

  public $info = array(
    "id" => null,
    "title" => null,
    "mal_url" => null,
    "image_url" => null,
    "score" => null,
    "type" => null,
    "episodes" => null,
    "air_dates" => array(
      "from" => null,
      "to" => null
    ),
    "rating" => null,
    "members_inlist" => null
  );

}
?>