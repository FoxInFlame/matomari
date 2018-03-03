<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Components;

use DateTime;
use DateTimeZone;

/** 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class Birthday
{

  /**
   * Convert MAL birthdays into acceptable return formats (one of the following types):
   * 1. Full (2012-12-04)
   * 2. Year (2012)
   * 3. Date (12-04)
   * 
   * @param String $string The date string on MAL
   * @return DateTime
   * @since 0.5
   */
  public static function convert($string) {
    $string = trim($string); // Super important! :)

    // Birthday: Jan 23, 2017
    $date = DateTime::createFromFormat('!M j', $string, new DateTimeZone(self::$tz_mal));
    if($date) {
      $date->setTimeZone(new DateTimeZone(self::$tz_final));
      return $date;
    }

    // Birthday: 1951
    $date = DateTime::createFromFormat('!Y', $string, new DateTimeZone(self::$tz_mal));
    if($date) {
      $date->setTimeZone(new DateTimeZone(self::$tz_final));
      return $date;
    }

    // Birthday: Jan 23
    $date = DateTime::createFromFormat('!M j', $string, new DateTimeZone(self::$tz_mal));
    if($date) {
      $date->setTimeZone(new DateTimeZone(self::$tz_final));
      return $date;
    }

    return false;
  }
}