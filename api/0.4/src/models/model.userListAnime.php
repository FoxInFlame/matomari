<?php


require_once(dirname(__FILE__) . "/model.php");

class UserListAnime extends Model {

  public $info = array(
    "id" => null,
    "title" => null,
    "mal_url" => null,
    "image_url" => null,
    "other_titles" => array(),
    "type" => null,
    "episodes" => null,
    "air_status" => null,
    "air_dates" => array(
      "from" => null,
      "to" => null
    ),
    "rating" => null,
    "watch_status" => null,
    "watched_episodes" => null,
    "watch_score" => null,
    "watch_dates" => array(
      "from" => null,
      "to" => null,
    ),
    "tags" => array(),
    "priority" => null,
    "storage" => null,
    "storage_amount" => null,
    "rewatching" => null,
    "last_updated" => null,
    "days_spent_watching" => null
  );
  
}
?>
