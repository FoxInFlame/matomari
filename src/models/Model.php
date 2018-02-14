<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Models;

use Matomari\Components\Exceptions;

/** 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class Model
{

  public function asArray() {
    return $this->info;
  }

  public function set($data, $value) {
    switch($data) {
      case "air_status":
        switch($value) {
          case $value === 1:
          case $value === "currently airing":
            $value = "currently_airing";
            break;
          case $value === 2:
          case $value === "finished airing":
            $value = "finished_airing";
            break;
          case $value === 3:
          case $value === "not yet aired":
            $value = "not_yet_aired";
            break;
          default:
            throw new ModelValueNotValid("The provided value is invalid.");
        }
        $this->info["air_status"] = $value;
        break;
      case "watch_status":
        switch($value) {
          case $value === 1:
          case $value === "watching":
            $value = "watching";
            break;
          case $value === 2:
          case $value === "completed":
            $value = "completed";
            break;
          case $value === 3:
          case $value === "on_hold":
            $value = "on_hold";
            break;
          case $value === 4:
          case $value === "dropped":
            $value = "dropped";
            break;
          case $value === 6:
          case $value === "plan_to_watch":
            $value = "plan_to_watch";
            break;
          default:
            throw new ModelValueNotValid("The provided value is invalid.");
        }
        $this->info["watch_status"] = $value;
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
      default:
        $data_levels = explode("//", $data);
        $temp_arr = &$this->info; // Reference operator
        foreach($data_levels as $level) {
          if(array_key_exists($level, $temp_arr)) {
            $temp_arr = &$temp_arr[$level];
          } else {  
            throw new ModelKeyDoesNotExist("Nonexistent set key.");
          }
        }
        $temp_arr = $value;
        break;
    }
  }

  public function get($data) {
    $data_levels = explode("//", $data);
    $temp_arr = &$this->info; // Reference operator
    foreach($data_levels as $level) {
      if(array_key_exists($level, $temp_arr)) {
        $temp_arr = &$temp_arr[$level];
      } else {  
        throw new ModelKeyDoesNotExist("Nonexistent get key.");
      }
    }
    return $temp_arr;
  }
}