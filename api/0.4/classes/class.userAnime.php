<?php

require_once(dirname(__FILE__) . "/../absoluteGMT.php");

class UserAnime {

  private $info_liststatus = array(
    "status" => null,
    "rewatching" => null,
    "episodes" => null,
    "score" => null,
    "watch_dates" => array(
      "from" => null,
      "to" => null,
    ),
    "tags" => array(),
    "priority" => null,
    "storage" => null,
    "storage_value" => null,
    "rewatch_times" => null,
    "rewatch_value" => null,
    "comments" => null
  );

  public function set($data, $value) {
    switch($data) {
      case "status":
        if(is_numeric($value)) {
          switch(trim($value)) {
            case "1":
              $value = "watching";
              break;
            case "2":
              $value = "completed";
              break;
            case "3":
              $value = "on_hold";
              break;
            case "4":
              $value = "dropped";
              break;
            case "6":
              $value = "plan_to_watch";
              break;
            default:
              $value = "watching";
              break;
          }
        } else {
          if($value !== "watching" && $value !== "completed" && $value !== "on_hold" && $value !== "dropped" && $value !== "plan_to_watch" && $value !== null) $value = "watching";
        }
        $this->user_status = $value ? trim($value) : $value;
        break;
      case "rewatching":
        $this->user_rewatching = $value;
        break;
      case "episodes":
        $this->user_episodes = $value ? trim($value) : $value;
        break;
      case "score":
        $this->user_score = $value ? trim($value) : $value;
        break;
      case "watch_date_from":
        $this->user_start_date = $value ? trim($value) : $value;
        break;
      case "watch_date_to":
        $this->user_end_date = $value ? trim($value) : $value;
        break;
      case "tags":
        $this->user_tags = $value ? trim($value) : $value;
        break;
      case "priority":
        switch($value) {
          case "0":
            $value = "low";
            break;
          case "1":
            $value = "medium";
            break;
          case "2":
            $value = "high";
            break;
          default:
            $value = "low";
            break;
        }
        $this->user_priority = $value ? trim($value) : $value;
        break;
      case "storage":
        if(is_numeric($value)) {
          switch(trim($value)) {
            case "1":
              $value = "hard_drive";
              break;
            case "2":
              $value = "dvd_cd";
              break;
            case "3":
              $value = "none";
              break;
            case "4":
              $value = "retail_dvd";
              break;
            case "6":
              $value = "vhs";
              break;
            case "7":
              $value = "external_hd";
              break;
            case "8":
              $value = "nas";
              break;
            case "9":
              $value = "bluray";
              break;
            default:
              $value = null;
              break;
          }
        } else {
          if($value !== "hard_drive" && $value !== "dvd_cd" && $value !== "none" && $value !== "retail_dvd" && $value !== "vhs" && $value !== "external_hd" && $value !== "nas" && $value !== "bluray" && $value !== null) $value = null;
        }
        $this->user_storage = $value ? trim($value) : $value;
        break;
      case "rewatch_times":
        $this->user_rewatch_times = $value ? trim($value) : $value;
        break;
      case "rewatch_value":
        $this->user_rewatch_value = $value ? trim($value) : $value;
        break;
      case "comments":
        $this->user_comments = $value ? trim($value) : $value;
        break;
    }
  }
}