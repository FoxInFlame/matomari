<?php

require_once(dirname(__FILE__) . "/../absoluteGMT.php");
require_once(dirname(__FILE__) . "/../exceptions.php");

class MeListAnime {

  private $info_liststatus = array(
    "id" => null,
    "watch_status" => null,
    "watched_episodes" => null,
    "score" => null,
    "watch_dates" => array(
      "from" => null,
      "to" => null,
    ),
    "tags" => array(),
    "priority" => null,
    "storage" => null,
    "storage_amount" => null,
    "rewatching" => null,
    "rewatch_times" => null,
    "rewatch_value" => null,
    "comments" => null
  );

  public function asArray() {
    return $this->info_liststatus;
  }

  public function set($data, $value) {
    switch($data) {
      case "id":
        $this->info_liststatus["id"] = $value;
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
        $this->info_liststatus["watch_status"] = $value;
        break;
      case "watched_episodes":
        $this->info_liststatus["watched_episodes"] = $value;
        break;
      case "score":
        $this->info_liststatus["score"] = $value;
        break;
      case "watch_date_from":
        $this->info_liststatus["watch_dates"]["from"] = $value;
        break;
      case "watch_date_to":
        $this->info_liststatus["watch_dates"]["to"] = $value;
        break;
      case "tags":
        $this->info_liststatus["tags"] = $value;
        break;
      case "priority":
        switch($value) {
          case $value === 0:
          case $value === "low":
            $value = 0;
            break;
          case $value === 1:
          case $value === "medium":
            $value = 1;
            break;
          case $value === 2:
          case $value === "high":
            $value = 2;
            break;
          default:
            throw new ModelValueNotValid("The provided value is invalid.");
        }
        $this->info_liststatus["priority"] = $value;
        break;
      case "storage":
        switch($value) {
          case $value === 0:
          case $value === null:
            $value = null;
            break;
          case $value === 1:
          case $value === "hard_drive":
            $value = 1;
            break;
          case $value === 2:
          case $value === "dvd_cd":
            $value = 2;
            break;
          case $value === 3:
          case $value === "none":
            $value = 3;
            break;
          case $value === 4:
          case $value === "retail_dvd":
            $value = 4;
            break;
          case $value === 5:
          case $value === "vhs":
            $value = 5;
            break;
          case $value === 6:
          case $value === "external_hd":
            $value = 6;
            break;
          case $value === 7:
          case $value === "nas":
            $value = 7;
            break;
          case $value === 8:
          case $value === "bluray":
            $value = 8;
            break;
          default:
            throw new ModelValueNotValid("The provided value is invalid.");
        }
        $this->info_liststatus["storage"] = $value;
        break;
      case "storage_amount":
        $this->info_liststatus["storage_amount"] = $value;
        break;
      case "rewatching":
        $this->info_liststatus["rewatching"] = $value;
        break;
      case "rewatch_times":
        $this->info_liststatus["rewatch_times"] = $value;
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
        $this->info_liststatus["rewatch_value"] = $value;
        break;
      case "comments":
        $this->info_liststatus["comments"] = $value;
        break;
      default:
        throw new ModelKeyDoesNotExist("Nonexistent set key.");
    }
  }

  public function get($data) {
    switch($data) {
      case "id":
        return $this->info_liststatus["id"];
      case "watch_status":
        return $this->info_liststatus["watch_status"];
      case "rewatching":
        return $this->info_liststatus["rewatching"];
      case "watched_episodes":
        return $this->info_liststatus["watched_episodes"];
      case "score":
        return $this->info_liststatus["score"];
      case "watch_date_from":
        return $this->info_liststatus["watch_dates"]["from"];
      case "watch_date_to":
        return $this->info_liststatus["watch_dates"]["to"];
      case "tags":
        return $this->info_liststatus["tags"];
      case "priority":
        return $this->info_liststatus["priority"];
      case "storage":
        return $this->info_liststatus["storage"];
      case "storage_amount":
        return $this->info_liststatus["storage_amount"];
      case "rewatch_times":
        return $this->info_liststatus["rewatch_times"];
      case "rewatch_value":
        return $this->info_liststatus["rewatch_value"];
      case "comments":
        return $this->info_liststatus["comments"];
      default:
        throw new ModelKeyDoesNotExist("Nonexistent get key.");
    }
  }
}