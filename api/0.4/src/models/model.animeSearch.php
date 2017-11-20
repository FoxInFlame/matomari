<?php

require_once(dirname(__FILE__) . "/../absoluteGMT.php");
require_once(dirname(__FILE__) . "/../exceptions.php");

class AnimeSearch {

  private $info = array(
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
  
  public function asArray() {
    return $this->info;
  }

  public function set($data, $value) {
    switch($data) {
      case "id":
        $this->info["id"] = $value;
        break;
      case "title":
        $this->info["title"] = $value;
        break;
      case "mal_url":
        $this->info["mal_url"] = $value;
        break;
      case "image_url":
        $this->info["image_url"] = $value;
        break;
      case "score":
        $this->info["score"] = $value;
        break;
      case "type":
        $this->info["type"] = $value;
        break;
      case "episodes":
        $this->info["episodes"] = $value;
        break;
      case "air_date_from":
        $this->info["air_dates"]["from"] = $value;
        break;
      case "air_date_to":
        $this->info["air_dates"]["to"] = $value;
        break;
      case "rating":
        $this->info["rating"] = $value;
        break;
      case "members_inlist":
        $this->info["members_inlist"] = $value;
        break;
      default:
        throw new ModelKeyDoesNotExist("Nonexistent set key.");
    }
  }
  
  public function get($data) {
    switch($data) {
      case "id":
        return $this->info["id"];
      case "title":
        return $this->info["title"];
      case "mal_url":
        return $this->info["mal_url"];
      case "image_url":
        return $this->info["image_url"];
      case "score":
        return $this->info["score"];
      case "type":
        return $this->info["type"];
      case "episodes":
        return $this->info["episodes"];
      case "air_date_from":
        return $this->info["air_dates"]["from"];
      case "air_date_to":
        return $this->info["air_dates"]["to"];
      case "rating":
        return $this->info["rating"];
      case "members_inlist":
        return $this->info["members_inlist"];
      default:
        throw new ModelKeyDoesNotExist("Nonexistent get key.");
    }
  }
}
?>