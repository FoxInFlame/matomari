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
   * Convert any kind of MAL date into ISO 8601 date Strings.
   * 
   * @param String $string The date string on MAL
   * @return String
   * @since 0.5
   */
  public static function convert($string) {
    $string = trim($string); // Super important! :)

    if(strpos($string, 'Now') !== false) {
      $date = new DateTime(null);
      $date->setTimeZone(new DateTimeZone(self::$tz_final));
      return $date->format('Y-m-d\TH:i:sO');
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
        return $date->format('Y-m-d\THO');
      }
      if(strpos($string, 'minute') !== false) {
        if(strpos($string, 'minutes') !== false) {
          $minutes = substr($string, 0, -12);
          $date->modify('-' . $minutes . ' minutes');
        } else {
          $minute = substr($string, 0, -11);
          $date->modify('-' . $minute . ' minute');
        }
        return $date->format('Y-m-d\TH:iO');
      }
      if(strpos($string, 'second') !== false) {
        if(strpos($string, 'seconds') !== false) {
          $seconds = substr($string, 0, -12);
          $date->modify('-' . $seconds . ' seconds');
        } else {
          $second = substr($string, 0, -11);
          $date->modify('-' . $second . ' second');
        }
        return $date->format('Y-m-d\TH:i:sO');
      }
    } else if(strpos($string, 'Today') !== false) {
      // Today, 2:47 AM
      $date = DateTime::createFromFormat('g:i A', substr($string, 7), new DateTimeZone(self::$tz_mal));
      $date->setTimeZone(new DateTimeZone(self::$tz_final));
      return $date->format('Y-m-d\TH:iO');
    } else if(strpos($string, 'Yesterday') !== false) {
      // Yesterday, 8:47 PM
      $date = DateTime::createFromFormat('g:i A', substr($string, 11), new DateTimeZone(self::$tz_mal));
      $date->modify('-1 day');
      $date->setTimeZone(new DateTimeZone(self::$tz_final));
      return $date->format('Y-m-d\TH:iO');
    } else if(strpos($string, 'AM') !== false || strpos($string, 'PM') !== false) {
      // Feb 24, 9:29 AM
      // Dec 15, 2006 4:32 PM
      if(strlen($string) > 15) {
        $date = DateTime::createFromFormat('!M j, g:i A', $string, new DateTimeZone(self::$tz_mal));
      } else {
        $date = DateTime::createFromFormat('!M j, Y g:i A', $string, new DateTimeZone(self::$tz_mal));
      }
      $date->setTimeZone(new DateTimeZone(self::$tz_final));
      return $date->format('Y-m-d\TH:iO');
    } else if(strpos($string, ', ') !== false) {
      // Jul 4, 2010
      // Apr, 2018
      if(strlen(explode(', ', $string)[0]) > 3) {
        $date = DateTime::createFromFormat('!M j, Y', $string);
        return $date->format('Y-m-d');
      } else if(strlen(explode(', ', $string)[0]) > 2) {
        $date = DateTime::createFromFormat('!M, Y', $string);
        return $date->format('Y-m');
      }
    } else if(strpos($string, '/') !== false) {
      // 09/09/2016 at 04:35
      $date = DateTime::createFromFormat('!m/d/Y at H:i', $string, new DateTimeZone(self::$tz_mal));
      $date->setTimeZone(new DateTimeZone(self::$tz_final));
      return $date->format('Y-m-d\TH:iO');
    } else if(strpos($string, '-') !== false && strlen($string) === 8) {
      // Part of the response in anime/search.
      // Unlike previous ones, these are full dates and thus do not require timezones to be specified
      // or converted to.

      if(preg_match('/(?:^|\s|$)\d{2}-\d{2}-\d{2}(?:^|\s|$)/', $string, $matches)) {
        // MM-DD-YY
        if(substr($matches[0], 6, 2) > 30) {
          $date = DateTime::createFromFormat('m-d-Y', substr($matches[0], 0, 6) . '19' . substr($matches[0], 6, 2));
        } else {
          $date = DateTime::createFromFormat('m-d-Y', substr($matches[0], 0, 6) . '20' . substr($matches[0], 6, 2));
        }
        return $date->format('Y-m-d');
      } else if(preg_match('/(?:^|\s|$)\d{2}-\d{2}-\?\?(?:^|\s|$)/', $string, $matches)) {
        // MM-DD-??
        return null;
      } else if(preg_match('/(?:^|\s|$)\d{2}-\?\?-\d{2}(?:^|\s|$)/', $string, $matches)) {
        // MM-??-YY
        if(substr($matches[0], 6, 2) > 30) {
          $date = DateTime::createFromFormat('m-??-Y', substr($matches[0], 0, 6) . '19' . substr($matches[0], 6, 2));
        } else {
          $date = DateTime::createFromFormat('m-??-Y', substr($matches[0], 0, 6) . '20' . substr($matches[0], 6, 2));
        }
        return $date->format('Y-m');
      } else if(preg_match('/(?:^|\s|$)\d{2}-\?\?-\?\?(?:^|\s|$)/', $string, $matches)) {
        // MM-??-??
        return null;
      } else if(preg_match('/(?:^|\s|$)\?\?-\d{2}-\d{2}(?:^|\s|$)/', $string, $matches)) {
        // ??-DD-YY
        return null;
      } else if(preg_match('/(?:^|\s|$)\?\?-\d{2}-\?\?(?:^|\s|$)/', $string, $matches)) {
        // ??-DD-??
        return null;
      } else if(preg_match('/(?:^|\s|$)\?\?-\?\?-\d{2}(?:^|\s|$)/', $string, $matches)) {
        // ??-??-YY
        if(substr($matches[0], 6, 2) > 30) {
          $date = DateTime::createFromFormat('Y', '19' . substr($matches[0], 6, 2));
        } else {
          $date = DateTime::createFromFormat('Y', '20' . substr($matches[0], 6, 2));
        }
        return $date->format('Y');
      } else if(preg_match('/(?:^|\s|$)\?\?-\?\?-\?\?(?:^|\s|$)/', $string, $matches)) {
        // ??-??-??
        return null;
      } else {
        return null;        
      }

    } else {
      // 1951
      $date = DateTime::createFromFormat('!Y', $string);
      if($date) {
        return $date->format('Y');
      }
    }

    return null;
  }

  /**
   * Convert birthdays on MAL into ISO 8601 date Strings.
   * Unlike normal dates, birthdays can be mm-dd only without the year section.
   * 
   * @param String $string The birthday string on MAL
   * @return String
   * @since 0.5
   */
  public static function convertBirthday($string) {
    $string = trim($string);

    // Birthday: Jan 23, 2017
    $date = DateTime::createFromFormat('!M j, Y', $string);
    if($date) {
      return $date->format('Y-m-d');
    }

    // Birthday: Feb, 2001
    $date = DateTime::createFromFormat('!M, Y', $string);
    if($date) {
      return $date->format('Y-m');
    }

    // Birthday: 6, 2006
    $date = DateTime::createFromFormat('!j, Y', $string);
    if($date) {
      return $date->format('Y-d');
    }

    // Birthday: Jan 5
    $date = DateTime::createFromFormat('!M j', $string);
    if($date) {
      return $date->format('m-d');
    }

    // Birthday: 2001
    $date = DateTime::createFromFormat('!Y', $string);
    if($date) {
      return $date->format('Y');
    }

    return false;
  }
}