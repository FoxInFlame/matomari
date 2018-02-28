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
class Time
{

  /**
   * Contains the MAL timezone when not logged in (GMT-8)
   * @var String
   */
  public static $tz_mal = 'America/Los_Angeles'; 

  /**
   * Contains the resulting timezone in String format
   * @var String
   */
  public static $tz_final = 'Etc/GMT';

  /**
   * Contains MAL
   */

  /**
   * Convert any kind of MAL date into absolute GMT DateTimes.
   * 
   * @param String $string The date string on MAL
   * @param String $defaultFormat The default date DateTime format to look for.
   * @return DateTime
   * @since 0.5
   */
  public static function convert($string, $defaultFormat = '!M j, g:i A') {
    $string = trim($string); // Super important! :)

    if(strpos($string, 'Now') !== false) {
      $date = new DateTime(null);
      $date->setTimeZone(new DateTimeZone(self::$tz_final));
      return $date;
    } else if(strpos($string, 'ago') !== false) {
      /*Note: These are returning approximate values */
      $date = new DateTime(null);
      $date->setTimeZone(new DateTimeZone(self::$tz_final));
      if(strpos($string, 'hours') !== false) {
        if(strpos($string, 'hour') !== false) {
          $hours = substr($string, 0, -10);
          $date->modify('-' . $hours . ' hours');
        } else {
          $hour = substr($string, 0, -9);
          $date->modify('-' . $hour . ' hour');
        }
      }
      if(strpos($string, 'minute') !== false) {
        if(strpos($string, 'minutes') !== false) {
          $minutes = substr($string, 0, -12);
          $date->modify('-' . $minutes . ' minutes');
        } else {
          $minute = substr($string, 0, -11);
          $date->modify('-' . $minute . ' minute');
        }
      }
      if(strpos($string, 'second') !== false) {
        if(strpos($string, 'seconds') !== false) {
          $seconds = substr($string, 0, -12);
          $date->modify('-' . $seconds . ' seconds');
        } else {
          $second = substr($string, 0, -11);
          $date->modify('-' . $second . ' second');
        }
      }
      return $date;
    } else if(strpos($string, 'Today') !== false) {
      // Today, 2:47 AM
      $date = DateTime::createFromFormat('g:i A', substr($string, 7), new DateTimeZone(self::$tz_mal));
      $date->setTimeZone(new DateTimeZone(self::$tz_final));
      return $date;
    } else if(strpos($string, 'Yesterday') !== false) {
      // Yesterday, 8:47 PM
      $date = DateTime::createFromFormat('g:i A', substr($string, 11), new DateTimeZone(self::$tz_mal));
      $date->modify('-1 day');
      $date->setTimeZone(new DateTimeZone(self::$tz_final));
      return $date;
    } else if(strpos($string, 'AM') !== false || strpos($string, 'PM') !== false) {
      // Feb 24, 9:29 AM
      // Dec 15, 2006 4:32 PM
      if(strlen($string) > 15) {
        $date = DateTime::createFromFormat('!M j, g:i A', $string, new DateTimeZone(self::$tz_mal));
      } else {
        $date = DateTime::createFromFormat('!M j, Y g:i A', $string, new DateTimeZone(self::$tz_mal));
      }
      $date->setTimeZone(new DateTimeZone(self::$tz_final));
      return $date;
    } else if(strpos($string, ', ') !== false) {
      // Jul 4, 2010
      // Apr, 2018
      // 10, 1951
      if(strlen(explode(', ', $string)[0]) > 3) {
        $date = DateTime::createFromFormat('!M j, Y', $string, new DateTimeZone(self::$tz_mal));
      } else if(strlen(explode(', ', $string)[0]) > 2) {
        $date = DateTime::createFromFormat('!M, Y', $string, new DateTimeZone(self::$tz_mal));
      } else {
        $date = DateTime::createFromFormat('!j, Y', $string, new DateTimeZone(self::$tz_mal));
      }
      $date->setTimeZone(new DateTimeZone(self::$tz_final));
      return $date;
    } else if(strpos($string, '/') !== false) {
      // 09/09/2016 at 04:35
      $date = DateTime::createFromFormat('!m/d/Y at H:i', $string, new DateTimeZone(self::$tz_mal));
      $date->setTimeZone(new DateTimeZone(self::$tz_final));
      return $date;
    } else {
      // Birthday: Jan 23
      $date = DateTime::createFromFormat('!M j', $string, new DateTimeZone(self::$tz_mal));
      if($date) {
        $date->setTimeZone(new DateTimeZone(self::$tz_final));
        return $date;
      }
      // Birthday: Jan
      $date = DateTime::createFromFormat('!M', $string, new DateTimeZone(self::$tz_mal));
      if($date) {
        $date->setTimeZone(new DateTimeZone(self::$tz_final));
        return $date;
      }
      // 1951
      $date = DateTime::createFromFormat('!Y', $string, new DateTimeZone(self::$tz_mal));
      if($date) {
        $date->setTimeZone(new DateTimeZone(self::$tz_final));
        return $date;
      }
    }

    return false;
  }
}