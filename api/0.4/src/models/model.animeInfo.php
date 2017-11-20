<?php

require_once(dirname(__FILE__) . "/../absoluteGMT.php");
require_once(dirname(__FILE__) . "/../exceptions.php");

class AnimeInfo {

  private $info = array(
    "id" => null,
    "title" => null,
    "mal_url" => null,
    "image_url" => null,
    "score" => null,
    "rank" => null,
    "popularity" => null,
    "synopsis" => null,
    "other_titles" => array(),
    "type" => null,
    "episodes" => null,
    "air_status" => null,
    "air_dates" => array(
      "from" => null,
      "to" => null
    ),
    "season" => null,
    "air_time" => null,
    "premier_date" => null,
    "producers" => array(),
    "licensors" => array(),
    "studios" => array(),
    "source" => null,
    "genres" => array(),
    "duration" => array(
      "total" => null,
      "per_episode" => null
    ),
    "rating" => null,
    "members_scored" => null,
    "members_inlist" => null,
    "members_favorited" => null,
    "background" => null,
    "related" => array(),
    "theme_songs" => array()
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
      case "rank":
        $this->info["rank"] = $value;
        break;
      case "popularity":
        $this->info["popularity"] = $value;
        break;
      case "synopsis":
        $this->info["synopsis"] = $value;
        break;
      case "other_titles":
        $this->info["other_titles"] = $value;
        break;
      case "type":
        $this->info["type"] = $value;
        break;
      case "episodes":
        $this->info["episodes"] = $value;
        break;
      case "air_status":
        $this->info["air_status"] = $value;
        break;
      case "air_date_from":
        $this->info["air_dates"]["from"] = $value;
        break;
      case "air_date_to":
        $this->info["air_dates"]["to"] = $value;
        break;
      case "premier_date":
        $this->info["premier_date"] = $value;
        break;
      case "season":
        $this->info["season"] = $value;
        break;
      case "air_time":
        $this->info["air_time"] = $value;
        break;
      case "producers":
        $this->info["producers"] = $value;
        break;
      case "licensors":
        $this->info["licensors"] = $value;
        break;
      case "studios":
        $this->info["studios"] = $value;
        break;
      case "source":
        $this->info["source"] = $value;
        break;
      case "duration_total":
        $this->info["duration"]["total"] = $value;
        break;
      case "duration_per_episode":
        $this->info["duration"]["per_episode"] = $value;
        break;
      case "genres":
        $this->info["genres"] = $value;
        break;
      case "rating":
        $this->info["rating"] = $value;
        break;
      case "members_scored":
        $this->info["members_scored"] = $value;
        break;
      case "members_inlist":
        $this->info["members_inlist"] = $value;
        break;
      case "members_favorited":
        $this->info["members_favorited"] = $value;
        break;
      case "background":
        $this->info["background"] = $value;
        break;
      case "related":
        $this->info["related"] = $value;
        break;
      case "theme_songs":
        $this->info["theme_songs"] = $value;
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
      case "rank":
        return $this->info["rank"];
      case "popularity":
        return $this->info["popularity"];
      case "synopsis":
        return $this->info["synopsis"];
      case "other_titles":
        return $this->info["other_titles"];
      case "type":
        return $this->info["type"];
      case "episodes":
        return $this->info["episodes"];
      case "air_status":
        return $this->info["air_status"];
      case "air_date_from":
        return $this->info["air_dates"]["from"];
      case "air_date_to":
        return $this->info["air_dates"]["to"];
      case "premier_date":
        return $this->info["premier_date"];
      case "season":
        return $this->info["season"];
      case "air_time":
        return $this->info["air_time"];
      case "producers":
        return $this->info["producers"];
      case "licensors":
        return $this->info["licensors"];
      case "studios":
        return $this->info["studios"];
      case "source":
        return $this->info["source"];
      case "duration_total":
        return $this->info["duration"]["total"];
      case "duration_per_episode":
        return $this->info["duration"]["per_episode"];
      case "genres":
        return $this->info["genres"];
      case "rating":
        return $this->info["rating"];
      case "members_scored":
        return $this->info["members_scored"];
      case "members_inlist":
        return $this->info["members_inlist"];
      case "members_favorited":
        return $this->info["members_favorited"];
      case "background":
        return $this->info["background"];
      case "related":
        return $this->info["related"];
      case "theme_songs":
        return $this->info["theme_songs"];
      default:
        throw new ModelKeyDoesNotExist("Nonexistent get key.");
    }
  }
}
?>