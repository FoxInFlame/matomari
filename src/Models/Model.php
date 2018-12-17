<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Models;

use Matomari\Exceptions\ModelKeyDoesNotExist;
use Matomari\Exceptions\ModelValueNotValid;
use ReflectionClass;

/** 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class Model
{

  /**
   * Retrieve the model as an array.
   * 
   * @return Array
   * @since 0.5
   */
  public function asArray() {

    $properties = get_object_vars($this);
    return $properties;

  }

  /**
   * Set a value in the model using the provided key.
   * Double slashes inside the key indicate a nested key.
   * 
   * @param String $data The key to the data in the model
   * @param Mixed $value The value to set for the provided key
   * @since 0.5
   */
  public function set($data, $value) {

    switch($data) {
      case 'air_status':
        switch($value) {
          case $value === 1:
          case $value === 'currently_airing':
            $value = 'currently_airing';
            break;
          case $value === 2:
          case $value === 'finished_airing':
            $value = 'finished_airing';
            break;
          case $value === 3:
          case $value === 'not_yet_aired':
            $value = 'not_yet_aired';
            break;
          default:
            throw new ModelValueNotValid('The provided value is invalid.');
        }
        $this->{'air_status'} = $value;
        break;

      case 'watch_status':
        switch($value) {
          case $value === 1:
          case $value === 'watching':
            $value = 'watching';
            break;
          case $value === 2:
          case $value === 'completed':
            $value = 'completed';
            break;
          case $value === 3:
          case $value === 'on_hold':
            $value = 'on_hold';
            break;
          case $value === 4:
          case $value === 'dropped':
            $value = 'dropped';
            break;
          case $value === 6:
          case $value === 'plan_to_watch':
            $value = 'plan_to_watch';
            break;
          default:
            throw new ModelValueNotValid('The provided value is invalid.');
        }
        $this->{'watch_status'} = $value;
        break;

      case 'priority':
        switch($value) {
          case $value === 0:
          case $value === 'low':
            $value = 'low';
            break;
          case $value === 1:
          case $value === 'medium':
            $value = 'medium';
            break;
          case $value === 2:
          case $value === 'high':
            $value = 'high';
            break;
          default:
            throw new ModelValueNotValid('The provided value is invalid.');
        }
        $this->{'priority'} = $value;
        break;

      case 'storage':
        switch($value) {
          case $value === 0:
          case $value === null:
          case $value === '':
            $value = null;
            break;
          case $value === 1:
          case $value === 'hard_drive':
          case $value === 'HD':
            $value = 1;
            break;
          case $value === 2:
          case $value === 'dvd_cd':
          case $value === 'DVD':
            $value = 2;
            break;
          case $value === 3:
          case $value === 'none':
            $value = 3;
            break;
          case $value === 4:
          case $value === 'retail_dvd':
          case $value === 'RDVD':
            $value = 4;
            break;
          case $value === 5:
          case $value === 'vhs':
          case $value === 'VHS':
            $value = 5;
            break;
          case $value === 6:
          case $value === 'external_hd':
          case $value === 'EHD':
            $value = 6;
            break;
          case $value === 7:
          case $value === 'nas':
          case $value === 'NAS':
            $value = 7;
            break;
          case $value === 8:
          case $value === 'bluray':
          case $value === 'Blue-ray':
            $value = 8;
            break;
          default:
            throw new ModelValueNotValid('The provided value is invalid.');
        }
        $this->{'storage'} = $value;
        break;

      case 'rewatch_value':
        switch($value) {
          case $value === 0:
          case $value === null:
            $value = null;
            break;
          case $value === 1:
          case $value === 'very_low':
            $value = 1;
            break;
          case $value === 2:
          case $value === 'low':
            $value = 2;
            break;
          case $value === 3:
          case $value === 'medium':
            $value = 3;
            break;
          case $value === 4:
          case $value === 'high':
            $value = 4;
            break;
          case $value === 5:
          case $value === 'very_high':
            $value = 5;
            break;
          default:
            throw new ModelValueNotValid('The provided value is invalid.');
        }
        $this->{'rewatch_value'} = $value;
        break;

      default:
        $data_levels = explode('//', $data);
        $base_property = array_shift($data_levels);
        $temp_arr = &$this->{$base_property}; // Reference operator
        foreach($data_levels as $level) {
          if(!array_key_exists($level, $temp_arr)) {
            throw new ModelKeyDoesNotExist('Nonexistent set key.');
          }
          $temp_arr = &$temp_arr[$level];
        }
        $temp_arr = $value;
        break;
    }

  }

  /**
   * Get a value in the model using the provided key.
   * Double slashes inside the key indicate a nested key.
   * 
   * @param String $data The key to the data in the model
   * @return Mixed
   * @since 0.5
   */
  public function get($data) {

    $data_levels = explode('//', $data);
    $base_property = array_shift($data_levels);
    $temp_arr = &$this->{$base_property}; // Reference operator
    foreach($data_levels as $level) {
      if(array_key_exists($level, $temp_arr)) {
        $temp_arr = &$temp_arr[$level];
      } else {  
        throw new ModelKeyDoesNotExist('Nonexistent get key.');
      }
    }
    return $temp_arr;
    
  }
}