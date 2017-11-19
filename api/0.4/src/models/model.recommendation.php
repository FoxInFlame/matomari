<?php

require_once(dirname(__FILE__) . "/../absoluteGMT.php");

class Recommendation {

  private $info = array(
    "from" => array(
      "id" => null,
      "title" => null,
      "mal_url" => null,
      "image_url" => null
    ),
    "to" => array(
      "id" => null,
      "title" => null,
      "mal_url" => null,
      "image_url" => null
    ),
    "reason" => null,
    "author" => null,
    "timestamp" => null
  );
  
  public function asArray() {
    return $this->info;
  }

  public function set($data, $value) {
    switch($data) {
      case "from_id":
        $this->info["from"]["id"] = $value;
        break;
      case "from_title":
        $this->info["from"]["title"] = $value;
        break;
      case "from_mal_url":
        $this->info["from"]["mal_url"] = $value;
        break;
      case "from_image_url":
        $this->info["from"]["image_url"] = $value;
        break;
      case "to_id":
        $this->info["to"]["id"] = $value;
        break;
      case "to_title":
        $this->info["to"]["title"] = $value;
        break;
      case "to_mal_url":
        $this->info["to"]["mal_url"] = $value;
        break;
      case "to_image_url":
        $this->info["to"]["image_url"] = $value;
        break;
      case "reason":
        $this->info["reason"] = $value;
        break;
      case "author":
        $this->info["author"] = $value;
        break;
      case "timestamp":
        $this->info["timestamp"] = $value;
        break;
    }
  }
  
  public function get($data) {
    switch($data) {
      case "from_id":
        return $this->info["from"]["id"];
        break;
      case "from_title":
        return $this->info["from"]["title"];
        break;
      case "from_mal_url":
        return $this->info["from"]["mal_url"];
        break;
      case "from_image_url":
        return $this->info["from"]["image_url"];
        break;
      case "to_id":
        return $this->info["to"]["id"];
        break;
      case "to_title":
        return $this->info["to"]["title"];
        break;
      case "to_mal_url":
        return $this->info["to"]["mal_url"];
        break;
      case "to_image_url":
        return $this->info["to"]["image_url"];
        break;
      case "reason":
        return $this->info["reason"];
        break;
      case "author":
        return $this->info["author"];
        break;
      case "timestamp":
        return $this->info["timestamp"];
        break;
    }
  }
}
?>