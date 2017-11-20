<?php

require_once(dirname(__FILE__) . "/../absoluteGMT.php");
require_once(dirname(__FILE__) . "/../exceptions.php");

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
      case "type":
        return $this->info["type"];
      case "episodes":
        return $this->info["episodes"];
      case "members_inlist":
        return $this->info["members_inlist"];
      case "members_favorited":
        return $this->info["members_favorited"];
      default:
        throw new ModelKeyDoesNotExist("Nonexistent get key.");
    }
  }
}
?>