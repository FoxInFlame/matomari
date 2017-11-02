<?php

require_once(dirname(__FILE__) . "/../absoluteGMT.php");

class Anime {

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
    "premier_date" => null,
    "season" => null,
    "air_time" => null,
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
      case "rank":
        return $this->info["rank"];
        break;
      case "popularity":
        return $this->info["popularity"];
        break;
      case "synopsis":
        return $this->info["synopsis"];
        break;
      case "other_titles":
        return $this->info["other_titles"];
        break;
      case "type":
        return $this->info["type"];
        break;
      case "episodes":
        return $this->info["episodes"];
        break;
      case "air_status":
        return $this->info["air_status"];
        break;
      case "air_date_from":
        return $this->info["air_dates"]["from"];
        break;
      case "air_date_to":
        return $this->info["air_dates"]["to"];
        break;
      case "premier_date":
        return $this->info["premier_date"];
        break;
      case "season":
        return $this->info["season"];
        break;
      case "air_time":
        return $this->info["air_time"];
        break;
      case "producers":
        return $this->info["producers"];
        break;
      case "licensors":
        return $this->info["licensors"];
        break;
      case "studios":
        return $this->info["studios"];
        break;
      case "source":
        return $this->info["source"];
        break;
      case "duration_total":
        return $this->info["duration"]["total"];
        break;
      case "duration_per_episode":
        return $this->info["duration"]["per_episode"];
        break;
      case "genres":
        return $this->info["genres"];
        break;
      case "rating":
        return $this->info["rating"];
        break;
      case "members_scored":
        return $this->info["members_scored"];
        break;
      case "members_inlist":
        return $this->info["members_inlist"];
        break;
      case "members_favorited":
        return $this->info["members_favorited"];
        break;
      case "background":
        return $this->info["background"];
        break;
      case "related":
        return $this->info["related"];
        break;
      case "theme_songs":
        return $this->info["theme_songs"];
        break;
      case "user_status":
        switch($this->user_status) {
          case "watching":
            return 1;
            break;
          case "completed":
            return 2;
            break;
          case "onhold":
            return 3;
            break;
          case "dropped":
            return 4;
            break;
          case "plan_to_watch":
            return 6;
            break;
        }
        return $this->user_status;
        break;
      case "user_status_str":
        return $this->user_status;
        break;
      case "user_rewatching":
        return $this->user_rewatching;
        break;
      case "user_episodes":
        return $this->user_episodes;
        break;
      case "user_score":
        return $this->user_score;
        break;
      case "user_start_date":
        return $this->user_start_date;
        break;
      case "user_end_date":
        return $this->user_end_date;
        break;
      case "user_tags":
        return $this->user_tags;
        break;
      case "user_priority":
        return $this->user_priority;
        break;
      case "user_storage":
        return $this->user_storage;
        break;
      case "user_rewatch_times":
        return $this->user_rewatch_times;
        break;
      case "user_rewatch_value":
        return $this->user_rewatch_value;
        break;
      case "user_comments":
        return $this->user_comments;
        break;
    }
  }
}
?>