<?php

require_once(dirname(__FILE__) . "/model.php");

class Recommendation extends Model {

  public $info = array(
    "id" => null,
    "rec_from" => array(
      "id" => null,
      "title" => null,
      "mal_url" => null,
      "image_url" => null
    ),
    "rec_to" => array(
      "id" => null,
      "title" => null,
      "mal_url" => null,
      "image_url" => null
    ),
    "reason" => null,
    "author" => null,
    "timestamp" => null
  );
  
}
?>