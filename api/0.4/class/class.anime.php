<?php
/*

Inspired by Atarashii API

*/

class Anime {
  
  // Normal anime info
  private $id;
  private $title;
  private $other_titles = array();
  private $rank;
  private $popularity;
  private $image_url;
  private $source;
  private $mal_url;
  private $type;
  private $episodes;
  private $status;
  private $duration;
  private $total_duration;
  private $score;
  private $score_count;
  private $members_count;
  private $favorites_count;
  private $genres = array();
  private $producers = array();
  private $studios = array();
  private $licensors = array();
  private $synopsis;
  
  // t=64
  private $release_year;
  private $synopsis_snippet;
  
  // User info
  private $userStatus;
  private $userRewatching;
  private $userEpisodes;
  private $userStartDate;
  private $userEndDate;
  private $userTags = array();
  private $userPriority;
  private $userStorage;
  private $userRewatchCount;
  private $userRewatchValue;
  private $userComments;
  
  public function set($data, $value) {
    switch($data) {
      case "id":
        $this->id = $value ? trim($value) : $value;
        break;
      case "title":
        $this->title = $value ? trim($value) : $value;
        break;
      case "otherTitles":
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
      case "MALURL":
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
      case "totalDuration":
        $this->total_duration = $value ? trim($value) : $value;
        break;
      case "score":
        $this->score = $value ? trim($value) : $value;
        break;
      case "scoreCount":
        $this->score_count = $value ? trim($value) : $value;
        break;
      case "membersCount":
        $this->members_count = $value ? trim($value) : $value;
        break;
      case "favoritesCount":
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
        $this->userStatus = $value ? trim($value) : $value;
        break;
      case "user_rewatching":
        $this->userRewatching = $value ? trim($value) : $value;
        break;
      case "user_episodes":
        $this->userEpisodes = $value ? trim($value) : $value;
        break;
      case "startDate":
        $this->userStartDate = $value ? trim($value) : $value;
        break;
      case "endDate":
        $this->userEndDate = $value ? trim($value) : $value;
        break;
      case "tags":
        $this->userTags = $value ? trim($value) : $value;
        break;
      case "userPriority":
        $this->userPriority = $value ? trim($value) : $value;
        break;
      case "userStorage":
        $this->userStorage = $value ? trim($value) : $value;
        break;
      case "userRewatchCount":
        $this->userRewatchCount = $value ? trim($value) : $value;
        break;
      case "userRewatchValue":
        $this->userRewatchValue = $value ? trim($value) : $value;
        break;
      case "userComments":
        $this->userComments = $value ? trim($value) : $value;
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
      case "otherTitles":
        return $this->other_titles;
        break;
      case "rank":
        return $this->rank;
        break;
      case "popularity":
        return $this->popularity;
        break;
      case "image":
        return array(
          $this->image_url,
          substr($this->image_url, 0, -4) . "t.jpg"
        );
        break;
      case "source":
        return $this->source;
        break;
      case "MALURL":
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
      case "totalDuration":
        if(!$this->total_duration) {
          if(!$this->episodes || !$this->duration) return;
          return $this->episodes * $this->duration;
        }
        return $this->total_duration;
        break;
      case "score":
        return $this->score;
        break;
      case "scoreCount":
        return $this->score_count;
        break;
      case "membersCount":
        return $this->members_count;
        break;
      case "favoritesCount":
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
      case "userStatus":
        return $this->userStatus;
        break;
      case "userRewatching":
        return $this->userRewatching;
        break;
      case "userEpisodes":
        return $this->userEpisodes;
        break;
      case "userStartDate":
        return $this->userStartDate;
        break;
      case "userEndDate":
        return $this->userEndDate;
        break;
      case "userTags":
        return $this->userTags;
        break;
      case "userPriority":
        return $this->userPriority;
        break;
      case "userStorage":
        return $this->userStorage;
        break;
      case "userRewatchCount":
        return $this->userRewatchCount;
        break;
      case "userRewatchValue":
        return $this->userRewatchValue;
        break;
      case "userComments":
        return $this->userComments;
        break;
    }
  }
}
?>