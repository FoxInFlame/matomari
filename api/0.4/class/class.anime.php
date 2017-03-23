<?php
/*

Inspired by Atarashii API

*/

class Anime {
  
  // Normal anime info
  private $id;
  private $title;
  private $mal_url;
  private $image_url;
  private $other_titles = array();
  private $type;
  private $episodes;
  private $status;
  private $air_date;
  private $season;
  private $air_time;
  private $producers = array();
  private $licensors = array();
  private $studios = array();
  private $source;
  private $genres = array();
  private $duration;
  private $total_duration;
  private $rating;
  private $score;
  private $score_count;
  private $rank;
  private $popularity;
  private $members_count;
  private $favorites_count;
  private $external_links = array();
  private $synopsis;
  private $background;
  private $related = array();
  private $theme_songs = array();
  
  // t=64
  private $release_year;
  private $synopsis_snippet;
  
  // User info
  private $user_status;
  private $user_rewatching;
  private $user_episodes;
  private $user_start_date;
  private $user_end_date;
  private $user_tags = array();
  private $user_priority;
  private $user_storage;
  private $user_rewatch_count;
  private $user_rewatch_value;
  private $user_comments;
  
  public function set($data, $value) {
    switch($data) {
      case "id":
        $this->id = $value ? trim($value) : $value;
        break;
      case "title":
        $this->title = $value ? trim($value) : $value;
        break;
      case "other_titles":
        $this->other_titles = $value;  // Don't trim arrays!
        break;
      case "rank":
        $this->rank = $value ? trim($value) : $value;
        break;
      case "popularity":
        $this->popularity = $value ? trim($value) : $value;
        break;
      case "image":
        if(strpos($value, " 1x, ") !== false) { // Contains two with ?s=
          $value = explode(" 1x,", $value)[0];
        }
        if(strpos($value, "/r/") !== false) { // URL is /r/ and contains ?s=
          $value = str_replace("/r/50x70", "", explode("?s=", $value)[0]);
        }
        $this->image_url = $value ? trim($value) : $value;
        break;
      case "source":
        $this->source = $value ? trim($value) : $value;
        break;
      case "mal_url":
        if($value[0] == "/") {
          $value = "https://myanimelist.net" . $value;
        }
        $this->mal_url = $value ? trim($value) : $value;
        break;
      case "type":
        $this->type = $value ? trim($value) : $value;
        break;
      case "episodes":
        $this->episodes = $value ? trim($value) : $value;
        break;
      case "status":
        $this->status = $value ? trim($value) : $value;
        break;
      case "duration":
        $this->duration = $value ? trim($value) : $value;
        break;
      case "total_duration":
        $this->total_duration = $value ? trim($value) : $value;
        break;
      case "score":
        $this->score = $value ? trim($value) : $value;
        break;
      case "score_count":
        $this->score_count = $value ? trim($value) : $value;
        break;
      case "members_count":
        $this->members_count = $value ? trim($value) : $value;
        break;
      case "favorites_count":
        $this->favorites_count = $value ? trim($value) : $value;
        break;
      case "genres":
        $this->genres = $value;
        break;
      case "producers":
        $this->producers = $value;
        break;
      case "studios":
        $this->studios = $value;
        break;
      case "licensors":
        $this->licensors = $value;
        break;
      case "synopsis":
        $this->synopsis = $value ? trim($value) : $value;
        break;
      case "release_year":
        $this->release_year = $value ? trim($value) : $value;
        break;
      case "synopsis_snippet":
        $this->synopsis_snippet = $value ? trim($value) : $value;
        break;
      case "user_status":
        $this->user_status = $value ? trim($value) : $value;
        break;
      case "user_rewatching":
        $this->user_rewatching = $value ? trim($value) : $value;
        break;
      case "user_episodes":
        $this->user_episodes = $value ? trim($value) : $value;
        break;
      case "user_start_date":
        $this->user_start_date = $value ? trim($value) : $value;
        break;
      case "user_end_date":
        $this->user_end_date = $value ? trim($value) : $value;
        break;
      case "user_tags":
        $this->user_tags = $value ? trim($value) : $value;
        break;
      case "user_priority":
        $this->user_priority = $value ? trim($value) : $value;
        break;
      case "user_storage":
        $this->user_storage = $value ? trim($value) : $value;
        break;
      case "user_rewatch_count":
        $this->user_rewatch_count = $value ? trim($value) : $value;
        break;
      case "user_rewatch_value":
        $this->user_rewatch_value = $value ? trim($value) : $value;
        break;
      case "user_comments":
        $this->user_comments = $value ? trim($value) : $value;
        break;
    }
  }
  
  public function get($data) {
    switch($data) {
      case "id":
        return $this->id;
        break;
      case "title":
        return $this->title;
        break;
      case "other_titles":
        return $this->other_titles;
        break;
      case "rank":
        return $this->rank;
        break;
      case "popularity":
        return $this->popularity;
        break;
      case "image":
        $array = array();
        if(@get_headers(substr($this->image_url, 0, -4) . "t.jpg")[0] == "HTTP/1.1 404 Not Found") {
          array_push($array, substr($this->image_url, 0, -4) . "t.jpg");
        }
        array_push($this->image_url);
        if(@get_headers(substr($this->image_url, 0, -4) . "l.jpg")[0] == "HTTP/1.1 404 Not Found") {
          array_push($array, substr($this->image_url, 0, -4) . "l.jpg");
        }
        return $array;
        break;
      case "source":
        return $this->source;
        break;
      case "mal_url":
        return $this->mal_url;
        break;
      case "type":
        return $this->type;
        break;
      case "episodes":
        return $this->episodes;
        break;
      case "status":
        return $this->status;
        break;
      case "duration":
        return $this->duration;
        break;
      case "total_duration":
        if(!$this->total_duration) {
          if(!$this->episodes || !$this->duration) return;
          return $this->episodes * $this->duration;
        }
        return $this->total_duration;
        break;
      case "score":
        return $this->score;
        break;
      case "score_count":
        return $this->score_count;
        break;
      case "members_count":
        return $this->members_count;
        break;
      case "favorites_count":
        return $this->favorites_count;
        break;
      case "genres":
        return $this->genres;
        break;
      case "producers":
        return $this->producers;
        break;
      case "studios":
        return $this->studios;
        break;
      case "licensors":
        return $this->licensors;
        break;
      case "synopsis":
        return $this->synopsis;
        break;
      case "release_year":
        return $this->release_year;
        break;
      case "synopsis_snippet":
        return($this->synopsis_snippet);
        break;
      case "user_status":
        return $this->user_status;
        break;
      case "user_rewatching":
        return $this->user_rewatching;
        break;
      case "user_episodes":
        return $this->user_episodes;
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
      case "user_rewatch_count":
        return $this->user_rewatch_count;
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