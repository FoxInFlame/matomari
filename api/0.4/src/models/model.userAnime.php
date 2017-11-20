<?php

require_once(dirname(__FILE__) . "/../absoluteGMT.php");
require_once(dirname(__FILE__) . "/../exceptions.php");

class UserAnime {

  private $info_liststatus = array(
    "id" => null,
    "status" => null,
    "episodes" => null,
    "score" => null,
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

  public function asArray() {
    return $this->info_liststatus;
  }

  public function set($data, $value) {
    switch($data) {
      case "id":
        $this->info_liststatus["id"] = $value;
        break;
      case "status":
        /*$value = trim($value);
        if(is_numeric($value)) {
          switch($value) {
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
        } else if($value !== "watching" && $value !== "completed" && $value !== "on_hold" && $value !== "dropped" && $value !== "plan_to_watch" && $value !== null) {
          $value = "watching";
        }*/
        $this->info_liststatus["status"] = $value;
        break;
      case "rewatching":
        $this->info_liststatus["rewatching"] = $value;
        break;
      case "episodes":
        $this->info_liststatus["episodes"] = $value;
        break;
      case "score":
        $this->info_liststatus["score"] = $value;
        break;
      case "watch_date_from":
        $this->info_liststatus["watch_dates"]["from"] = $value;
        break;
      case "watch_date_to":
        $this->info_liststatus["watch_dates"]["to"] = $value;
        break;
      case "tags":
        $this->info_liststatus["tags"] = $value;
        break;
      case "priority":
        /*switch($value) {
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
        }*/
        $this->info_liststatus["priority"] = $value;
        break;
      case "storage":
        $this->info_liststatus["storage"] = $value;
/*        switch(trim($value)) {
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
        }*/
        break;
      case "storage_amount":
        $this->info_liststatus["storage_amount"] = $value;
        break;
      case "rewatch_times":
        $this->info_liststatus["rewatch_times"] = $value;
        break;
      case "rewatch_value":
        $this->info_liststatus["rewatch_value"] = $value;
        break;
      case "comments":
        $this->info_liststatus["comments"] = $value;
        break;
      default:
        throw new ModelKeyDoesNotExist("Nonexistent set key.");
    }
  }

  public function get($data) {
    switch($data) {
      case "id":
        return $this->info_liststatus["id"];
      case "status":
        return $this->info_liststatus["status"];
      case "rewatching":
        return $this->info_liststatus["rewatching"];
      case "episodes":
        return $this->info_liststatus["episodes"];
      case "score":
        return $this->info_liststatus["score"];
      case "watch_date_from":
        return $this->info_liststatus["watch_dates"]["from"];
      case "watch_date_to":
        return $this->info_liststatus["watch_dates"]["to"];
      case "tags":
        return $this->info_liststatus["tags"];
      case "priority":
        return $this->info_liststatus["priority"];
      case "storage":
        return $this->info_liststatus["storage"];
      case "storage_amount":
        return $this->info_liststatus["storage_amount"];
      case "rewatch_times":
        return $this->info_liststatus["rewatch_times"];
      case "rewatch_value":
        return $this->info_liststatus["rewatch_value"];
      case "comments":
        return $this->info_liststatus["comments"];
      default:
        throw new ModelKeyDoesNotExist("Nonexistent get key.");
    }
  }
}