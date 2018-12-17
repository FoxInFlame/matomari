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
   * Convert 'Now' to the current time.
   * 
   * @return Array (DateTime Object, Format String, Array of Exceptions)
   * @since 0.5 
   */
  private static function convertNowString() {

    $date = new DateTime(null);
    return [$date, 'Y-m-d\TH:i:sO', []];
    // return $date->format('Y-m-d\TH:i:sO');

  }

  /**
   * Convert 'Today, 2:48 AM' into the correct DateTime.
   * 
   * @param String $string
   * @return Array (DateTime Object, Format String, Array of Exceptions)
   * @since 0.5
   */
  private static function convertTodayString($string) {
    
    $date = DateTime::createFromFormat('g:i A', substr($string, 7), new DateTimeZone(self::$tz_mal));

    return [$date, 'Y-m-d\TH:iO', []];
    // return $date->format('Y-m-d\TH:iO');

  }

  /**
   * Convert 'Yesterday, 4:47 PM' into correct DateTime.
   * 
   * @param String $string
   * @return Array (DateTime Object, Format String, Array of Exceptions)
   * @since 0.5 
   */
  private static function convertYesterdayString($string) {

    $date = DateTime::createFromFormat('g:i A', substr($string, 11), new DateTimeZone(self::$tz_mal));
    $date->modify('-1 day');

    return [$date, 'Y-m-d\TH:iO', []];

    // return $date->format('Y-m-d\TH:iO');

  }

  /**
   * Convert 'something units ago' to its DateTime.
   * 
   * @param String $string
   * @return Array (DateTime Object, Format String, Array of Exceptions)
   * @since 0.5
   */
  private static function convertAgoString($string) {

    // Current time
    $date = new DateTime(null);

    if(strpos($string, 'hour') !== false) {

      if(strpos($string, 'hours') !== false) {

        $hours = substr($string, 0, -10);
        $date->modify('-' . $hours . ' hours');

      } else {

        $hour = substr($string, 0, -9);
        $date->modify('-' . $hour . ' hour');
      
      }

      return [$date, 'Y-m-d\THO', []];
      // return $date->format('Y-m-d\THO');

    }

    if(strpos($string, 'minute') !== false) {

      if(strpos($string, 'minutes') !== false) {

        $minutes = substr($string, 0, -12);
        $date->modify('-' . $minutes . ' minutes');

      } else {

        $minute = substr($string, 0, -11);
        $date->modify('-' . $minute . ' minute');

      }

      return [$date, 'Y-m-d\TH:iO', []];

      // return $date->format('Y-m-d\TH:iO');

    }

    if(strpos($string, 'second') !== false) {

      if(strpos($string, 'seconds') !== false) {

        $seconds = substr($string, 0, -12);
        $date->modify('-' . $seconds . ' seconds');

      } else {
        
        $second = substr($string, 0, -11);
        $date->modify('-' . $second . ' second');
      
      }

      return [$date, 'Y-m-d\TH:i:sO', []];

      // return $date->format('Y-m-d\TH:i:sO');

    }

  }

  /**
   * Convert exact past datetimes to its DateTime.
   * e.g. Dec 15, 2006 4:32 PM
   *      Feb 24, 9:29 AM
   * 
   * @param String $string
   * @return Array (DateTime Object, Format String, Array of Exceptions)
   * @since 0.5
   */
  private static function convertExactDateString($string) {

    if(strlen($string) > 15) {

      // Only month and date -> This year
      $date = DateTime::createFromFormat('!M j, g:i A', $string, new DateTimeZone(self::$tz_mal));
      
    } else {

      // Contains Year
      $date = DateTime::createFromFormat('!M j, Y g:i A', $string, new DateTimeZone(self::$tz_mal));

    }

    return [$date, 'Y-m-d\TH:iO', []];

    // return $date->format('Y-m-d\TH:iO');
    

  }

  /**
   * Convert exact past dates to its DateTime.
   * e.g. Jul 4, 2010
   *      April, 2018
   * 
   * @param String $string
   * @return Array (DateTime Object, Format String, Array of Exceptions)
   * @since 0.5
   */
  private static function convertDateNoTimeString($string) {

    if(strlen(explode(', ', $string)[0]) > 3) {

      $date = DateTime::createFromFormat('!M j, Y', $string);
      
      return [$date, 'Y-m-d', []];

    } else if(strlen(explode(', ', $string)[0]) > 2) {

      $date = DateTime::createFromFormat('!M, Y', $string);
      
      return [$date, 'Y-m', []];

    }

  }

  /**
   * Convert slash separated dates to its DateTime.
   * e.g. 09/09/2016 at 04:35
   * 
   * @param String $string
   * @return Array (DateTime Object, Format String, Array of Exceptions)
   * @since 0.5
   */
  private static function convertSlashDateString($string) {

    $date = DateTime::createFromFormat('!m/d/Y at H:i', $string, new DateTimeZone(self::$tz_mal));
    
    return [$date, 'Y-m-d\TH:iO', []];
    // return $date->format('Y-m-d\TH:iO');

  }

  /**
   * Convert the date format returned by searches to its DateTime.
   * e.g. 09-??-15
   * 
   * @param String $string
   * @return Array (DateTime Object, Format String, Array of Exceptions)
   * @since 0.5
   */
  private static function convertSearchDateString($string) {

    if(preg_match('/(?:^|\s|$)\d{2}-\d{2}-\d{2}(?:^|\s|$)/', $string, $matches)) {

      // MM-DD-YY
      if(substr($matches[0], 6, 2) > 30) {
        $date = DateTime::createFromFormat('m-d-Y', substr($matches[0], 0, 6) . '19' . substr($matches[0], 6, 2));
      } else {
        $date = DateTime::createFromFormat('m-d-Y', substr($matches[0], 0, 6) . '20' . substr($matches[0], 6, 2));
      }

      return [$date, 'Y-m-d', []];
      // return $date->format('Y-m-d');

    } else if(preg_match('/(?:^|\s|$)\d{2}-\d{2}-\?\?(?:^|\s|$)/', $string, $matches)) {

      // MM-DD-??
      $date = DateTime::createFromFormat('m-d', substr($matches[0], 0, 5));

      return [$date, '', [
        'Y' => null
      ]];

    } else if(preg_match('/(?:^|\s|$)\d{2}-\?\?-\d{2}(?:^|\s|$)/', $string, $matches)) {

      // MM-??-YY
      if(substr($matches[0], 6, 2) > 30) {
        $date = DateTime::createFromFormat('mY', substr($matches[0], 0, 2) . '19' . substr($matches[0], 6, 2));
      } else {
        $date = DateTime::createFromFormat('mY', substr($matches[0], 0, 2) . '20' . substr($matches[0], 6, 2));
      }

      return [$date, 'Y-m', [
        'd' => null
      ]];

    } else if(preg_match('/(?:^|\s|$)\d{2}-\?\?-\?\?(?:^|\s|$)/', $string, $matches)) {

      // MM-??-??
      $date = DateTime::createFromFormat('m', substr($matches[0], 0, 2));

      return [$date, '', [
        'Y' => null,
        'd' => null
      ]];

    } else if(preg_match('/(?:^|\s|$)\?\?-\d{2}-\d{2}(?:^|\s|$)/', $string, $matches)) {

      // ??-DD-YY
      if(substr($matches[0], 6, 2) > 30) { 
        $date = DateTime::createFromFormat('dY', substr($matches[0], 3, 2) . '19' . substr($matches[0], 6, 2));
      } else {
        $date = DateTime::createFromFormat('dY', substr($matches[0], 3, 2) . '20' . substr($matches[0], 6, 2));
      }
      
      return [$date, 'Y', [
        'm' => null
      ]];

    } else if(preg_match('/(?:^|\s|$)\?\?-\d{2}-\?\?(?:^|\s|$)/', $string, $matches)) {

      // ??-DD-??
      $date = DateTime::createFromFormat('d', substr($matches[0], 3, 2));

      return [$date, '', [
        'Y' => null,
        'm' => null
      ]];

    } else if(preg_match('/(?:^|\s|$)\?\?-\?\?-\d{2}(?:^|\s|$)/', $string, $matches)) {

      // ??-??-YY
      if(substr($matches[0], 6, 2) > 30) {
        $date = DateTime::createFromFormat('Y', '19' . substr($matches[0], 6, 2));
      } else {
        $date = DateTime::createFromFormat('Y', '20' . substr($matches[0], 6, 2));
      }

      return [$date, 'Y', [
        'm' => null,
        'd' => null
      ]];

    } else if(preg_match('/(?:^|\s|$)\?\?-\?\?-\?\?(?:^|\s|$)/', $string, $matches)) {

      // ??-??-??
      return [null, null, []];

    }

    return [null, null, []];
  
  }

  /**
   * Convert plain years to its DateTime.
   * e.g. 2018
   * 
   * @param String $string
   * @return Array (DateTime Object, Format String, Array of Exceptions)
   * @since 0.5
   */
  private static function convertYearString($string) {

    $date = DateTime::createFromFormat('!Y', $string);

    return [$date, 'Y', []];
    // return $date

  }

  /**
   * Convert any generic kind of MAL date into ISO 8601 date Strings.
   * 
   * @param String $string The date string on MAL
   * @return String
   * @since 0.5
   */
  public static function convert($string) {
    
    if(strpos($string, 'Now') !== false) {

      list($date, $format, $exceptions) = self::convertNowString($string);

    } else if(strpos($string, 'Today') !== false) {

      list($date, $format, $exceptions) = self::convertTodayString($string);

    } else if(strpos($string, 'Yesterday') !== false) {

      list($date, $format, $exceptions) = self::convertYesterdayString($string);

    } else if(strpos($string, 'ago') !== false) {

      // Note that these are returning approximate values.
      list($date, $format, $exceptions) = self::convertAgoString($string);

    } else if(strpos($string, 'AM') !== false ||strpos($string, 'PM') !== false) {

      list($date, $format, $exceptions) = self::convertExactDateString($string);

    } else if(strpos($string, ', ') !== false) {

      list($date, $format, $exceptions) = self::convertDateNoTimeString($string);

    } else if(strpos($string, '/') !== false) {

      list($date, $format, $exceptions) = self::convertSlashDateString($string);

    } else if(strpos($string, '-') !== false && strlen($string) === 8) {

      list($date, $format, $exceptions) = self::convertSearchDateString($string);

    } else {
      
      list($date, $format, $exceptions) = self::convertYearString($string);

    }

    if($date === false || $date === null || $format === null) {
      return null;
    }

    $date->setTimeZone(new DateTimeZone(self::$tz_final));
    
    return self::convertDateTimeArray($date, $format, $exceptions);
    
  }

  /**
   * Convert DateTimes to API-friendly associative arrays using the format provided.
   * Only the provided fields in the format are going to be present in the assoc. array,
   * and there can be nulls or preset exceptions in value through $exceptions.
   * 
   * @param DateTime $date
   * @param String $format
   * @return Array
   * @since 0.5
   */
  private static function convertDateTimeArray($date, $format, $exceptions) {

    $date_array = [];

    $map = [
      'Y' => 'year',
      'm' => 'month',
      'd' => 'day',
      'H' => 'hour',
      'i' => 'minute',
      's' => 'second',
      'O' => 'offset'
    ];

    foreach($map as $date_part_key => $date_part_name) {
      if(array_key_exists($date_part_key, $exceptions)) {
        // Use the exception value if it exists, and continue to stop further execution
        $date_array[$date_part_name] = $exceptions[$date_part_key];
        continue;
      }
      // No exception was provided for this date_part_key, check if it is required for the format
      if(strpos($format, $date_part_key) !== false) {
        $date_array[$date_part_name] = $date->format($date_part_key);
        // Offset can be string, but others are all integers
        if($date_part_key !== 'O') $date_array[$date_part_name] = (int)$date_array[$date_part_name]; 
      }
    }

    $date_array['iso8601'] = $date->format($format);

    return $date_array;

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