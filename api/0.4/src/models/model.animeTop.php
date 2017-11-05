<?php

require_once(dirname(__FILE__) . "/../absoluteGMT.php");

class AnimeTop {

  private $info = array(
    "id" => null,
    "title" => null,
    "mal_url" => null,
    "image_url" => null,
    "score" => null,
    "rank" => null,
    "type" => null,
    "episodes" => null,
    "members_inlist" => null,
    "members_favorited" => null
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
      case "type":
        $this->info["type"] = $value;
        break;
      case "episodes":
        $this->info["episodes"] = $value;
        break;
      case "members_inlist":
        $this->info["members_inlist"] = $value;
        break;
      case "members_favorited":
        $this->info["members_favorited"] = $value;
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
      case "type":
        return $this->info["type"];
        break;
      case "episodes":
        return $this->info["episodes"];
        break;
      case "members_inlist":
        return $this->info["members_inlist"];
        break;
      case "members_favorited":
        return $this->info["members_favorited"];
        break;
    }
  }
}
?>