<?php

require_once(dirname(__FILE__) . "/../absoluteGMT.php");

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
    }
  }
  
  public function get($data) {
    switch($data) {
      case "id":
        return $this->info["id"];
        break;
      case "title":
        return $this->info["title"];
        break;
      case "mal_url":
        return $this->info["mal_url"];
        break;
      case "image_url":
        return $this->info["image_url"];
        break;
      case "score":
        return $this->info["score"];
        break;
      case "type":
        return $this->info["type"];
        break;
      case "episodes":
        return $this->info["episodes"];
        break;
      case "air_date_from":
        return $this->info["air_dates"]["from"];
        break;
      case "air_date_to":
        return $this->info["air_dates"]["to"];
        break;
      case "rating":
        return $this->info["rating"];
        break;
      case "members_inlist":
        return $this->info["members_inlist"];
        break;
    }
  }
}
?>