<?php
/*

Inspired by Atarashii API

*/

class Anime {
  
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
        $this->id = trim($value);
        break;
      case "title":
        $this->title = trim($value);
        break;
      case "otherTitles":
        $this->other_titles = $value;  // Don't trim arrays!
        break;
      case "rank":
        $this->rank = trim($value);
        break;
      case "popularity":
        $this->popularity = trim($value);
        break;
      case "imageURL":
        $this->image_url = trim($value);
        break;
      case "source":
        $this->source = trim($value);
        break;
      case "MALURL":
        $this->mal_url = trim($value);
        break;
      case "type":
        $this->type = trim($value);
        break;
      case "episodes":
        $this->episodes = trim($value);
        break;
      case "duration":
        $this->duration = trim($value);
        break;
      case "totalDuration":
        $this->total_duration = trim($value);
        break;
      case "score":
        $this->score = trim($value);
        break;
      case "scoreCount":
        $this->score_count = trim($value);
        break;
      case "membersCount":
        $this->members_count = trim($value);
        break;
      case "favoritesCount":
        $this->favorites_count = trim($value);
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
        $this->synopsis = trim($value);
        break;
      case "user_status":
        $this->userStatus = trim($value);
        break;
      case "user_rewatching":
        $this->userRewatching = trim($value);
        break;
      case "user_episodes":
        $this->userEpisodes = trim($value);
        break;
      case "startDate":
        $this->userStartDate = trim($value);
        break;
      case "endDate":
        $this->userEndDate = trim($value);
        break;
      case "tags":
        $this->userTags = trim($value);
        break;
      case "userPriority":
        $this->userPriority = trim($value);
        break;
      case "userStorage":
        $this->userStorage = trim($value);
        break;
      case "userRewatchCount":
        $this->userRewatchCount = trim($value);
        break;
      case "userRewatchValue":
        $this->userRewatchValue = trim($value);
        break;
      case "userComments":
        $this->userComments = trim($value);
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