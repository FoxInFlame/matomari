<?php

require_once(dirname(__FILE__) . "/model.php");

class MeListAnime extends Model {

  public $info = array(
    "id" => null,
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
    "rewatch_times" => null,
    "rewatch_value" => null,
    "comments" => null
  );

}