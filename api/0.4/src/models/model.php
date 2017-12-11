<?php

require_once(dirname(__FILE__) . "/../absoluteGMT.php");
require_once(dirname(__FILE__) . "/../exceptions.php");

class Model {

  public function asArray() {
    return $this->info;
  }

  public function set($data, $value) {
    switch($data) {
      case "air_status":
        switch($value) {
          case $value === 1:
          case $value === "airing":
            $value = 1;
            break;
          case $value === 2:
          case $value === "finished airing":
            $value = 2;
            break;
          case $value === 3:
          case $value === "not yet aired":
            $value = 3;
            break;
          default:
            throw new ModelValueNotValid("The provided value is invalid.");
        }
        $this->info["air_status"] = $value;
        break;
      case "air_date_from":
        $this->info["air_dates"]["from"] = $value;
        break;
      case "air_date_to":
        $this->info["air_dates"]["to"] = $value;
        break;
      case "duration_total":
        $this->info["duration"]["total"] = $value;
        break;
      case "duration_per_episode":
        $this->info["duration"]["per_episode"] = $value;
        break;
      case "watch_status":
        switch($value) {
          case $value === 1:
          case $value === "watching":
            $value = 1;
            break;
          case $value === 2:
          case $value === "completed":
            $value = 2;
            break;
          case $value === 3:
          case $value === "on_hold":
            $value = 3;
            break;
          case $value === 4:
          case $value === "dropped":
            $value = 4;
            break;
          case $value === 6:
          case $value === "plan_to_watch":
            $value = 6;
            break;
          default:
            throw new ModelValueNotValid("The provided value is invalid.");
        }
        $this->info["watch_status"] = $value;
        break;
      case "watch_date_from":
        $this->info["watch_dates"]["from"] = $value;
        break;
      case "watch_date_to":
        $this->info["watch_dates"]["to"] = $value;
        break;
      case "priority":
        switch($value) {
          case $value === 0:
          case $value === "low":
            $value = "low";
            break;
          case $value === 1:
          case $value === "medium":
            $value = "medium";
            break;
          case $value === 2:
          case $value === "high":
            $value = "high";
            break;
          default:
            throw new ModelValueNotValid("The provided value is invalid.");
        }
        $this->info["priority"] = $value;
        break;
      case "storage":
        switch($value) {
          case $value === 0:
          case $value === null:
          case $value === "":
            $value = null;
            break;
          case $value === 1:
          case $value === "hard_drive":
          case $value === "HD":
            $value = 1;
            break;
          case $value === 2:
          case $value === "dvd_cd":
          case $value === "DVD":
            $value = 2;
            break;
          case $value === 3:
          case $value === "none":
            $value = 3;
            break;
          case $value === 4:
          case $value === "retail_dvd":
          case $value === "RDVD":
            $value = 4;
            break;
          case $value === 5:
          case $value === "vhs":
          case $value === "VHS":
            $value = 5;
            break;
          case $value === 6:
          case $value === "external_hd":
          case $value === "EHD":
            $value = 6;
            break;
          case $value === 7:
          case $value === "nas":
          case $value === "NAS":
            $value = 7;
            break;
          case $value === 8:
          case $value === "bluray":
          case $value === "Blue-ray":
            $value = 8;
            break;
          default:
            throw new ModelValueNotValid("The provided value is invalid.");
        }
        $this->info["storage"] = $value;
        break;
      case "rewatch_value":
        switch($value) {
          case $value === 0:
          case $value === null:
            $value = null;
            break;
          case $value === 1:
          case $value === "very_low":
            $value = 1;
            break;
          case $value === 2:
          case $value === "low":
            $value = 2;
            break;
          case $value === 3:
          case $value === "medium":
            $value = 3;
            break;
          case $value === 4:
          case $value === "high":
            $value = 4;
            break;
          case $value === 5:
          case $value === "very_high":
            $value = 5;
            break;
          default:
            throw new ModelValueNotValid("The provided value is invalid.");
        }
        $this->info["rewatch_value"] = $value;
        break;
      case "rec_from_id":
      case "rec_from_title":
      case "rec_from_mal_url":
      case "rec_from_image_url":
        $this->info["rec_from"][substr($data, 9)] = $value;
        break;
      case "rec_to_id":
      case "rec_to_title":
      case "rec_to_mal_url":
      case "rec_to_image_url":
        $this->info["rec_to"][substr($data, 7)] = $value;
        break;
      default:
        if(array_key_exists($data, $this->info)) {
          $this->info[$data] = $value;
        } else {
          throw new ModelKeyDoesNotExist("Nonexistent set key.");
        }
        break;
    }
  }

  public function get($data) {
    switch($data) {
      case "air_date_from":
        return $this->info["air_dates"]["from"];
        break;
      case "air_date_to":
        return $this->info["air_dates"]["to"];
        break;
      case "duration_total":
        return $this->info["duration"]["total"];
        break;
      case "duration_per_episode":
        return $this->info["duration"]["per_episode"];
        break;
      case "watch_date_from":
        return $this->info["watch_dates"]["from"];
        break;
      case "watch_date_to":
        return $this->info["watch_dates"]["to"];
        break;
      case "rec_from_id":
      case "rec_from_title":
      case "rec_from_mal_url":
      case "rec_from_image_url":
        return $this->info["rec_from"][substr($data, 9)];
        break;
      case "rec_to_id":
      case "rec_to_title":
      case "rec_to_mal_url":
      case "rec_to_image_url":
        return $this->info["rec_to"][substr($data, 7)];
        break;
      default:
        if(array_key_exists($data, $this->info)) {
          return $this->info[$data];
        } else {
          throw new ModelKeyDoesNotExist("Nonexistent get key.");
        }
        break;
    }
  }
}
?>
