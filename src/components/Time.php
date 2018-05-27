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
use Matomari\Exceptions\InvalidDateFormat;

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
   * @param String $string The string... which is literally just 'Now'...
   * @return Array(DateTime, String)
   * @since 0.5 
   */
  private static function convert_now_string($string) {

    $date = new DateTime(null);
    return [$date, 'Y-m-d\TH:i:sO'];
    // return $date->format('Y-m-d\TH:i:sO');

  }

  /**
   * Convert 'Today, 2:48 AM' into the correct DateTime.
   * 
   * @param String $string
   * @return DateTime
   * @since 0.5
   */
  private static function convert_today_string($string) {
    
    $date = DateTime::createFromFormat('g:i A', substr($string, 7), new DateTimeZone(self::$tz_mal));

    return [$date, 'Y-m-d\TH:iO'];
    // return $date->format('Y-m-d\TH:iO');

  }

  /**
   * Convert 'Yesterday, 4:47 PM' into correct DateTime.
   * 
   * @param String $string
   * @return DateTime
   * @since 0.5 
   */
  private static function convert_yesterday_string($string) {

    $date = DateTime::createFromFormat('g:i A', substr($string, 11), new DateTimeZone(self::$tz_mal));
    $date->modify('-1 day');

    return [$date, 'Y-m-d\TH:iO'];

    // return $date->format('Y-m-d\TH:iO');

  }

  /**
   * Convert 'something units ago' to its DateTime.
   * 
   * @param String $string
   * @return DateTime
   * @since 0.5
   */
  private static function convert_ago_string($string) {

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

      return [$date, 'Y-m-d\THO'];
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

      return [$date, 'Y-m-d\TH:iO'];

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

      return [$date, 'Y-m-d\TH:i:sO'];

      // return $date->format('Y-m-d\TH:i:sO');

    }

  }

  /**
   * Convert exact past datetimes to its DateTime.
   * e.g. Dec 15, 2006 4:32 PM
   *      Feb 24, 9:29 AM
   * 
   * @param String $string
   * @return DateTime
   * @since 0.5
   */
  private static function convert_exact_date_string($string) {

    if(strlen($string) > 15) {

      // Only month and date -> This year
      $date = DateTime::createFromFormat('!M j, g:i A', $string, new DateTimeZone(self::$tz_mal));
      
    } else {

      // Contains Year
      $date = DateTime::createFromFormat('!M j, Y g:i A', $string, new DateTimeZone(self::$tz_mal));

    }

    return [$date, 'Y-m-d\TH:iO'];

    // return $date->format('Y-m-d\TH:iO');
    

  }

  /**
   * Convert exact past dates to its DateTime.
   * e.g. Jul 4, 2010
   *      April, 2018
   * 
   * @param String $string
   * @return DateTime
   * @since 0.5
   */
  private static function convert_date_no_time_string($string) {

    if(strlen(explode(', ', $string)[0]) > 3) {

      $date = DateTime::createFromFormat('!M j, Y', $string);
      
      return [$date, 'Y-m-d'];

    } else if(strlen(explode(', ', $string)[0]) > 2) {

      $date = DateTime::createFromFormat('!M, Y', $string);
      
      return [$date, 'Y-m'];

    }

  }

  /**
   * Convert slash separated dates to its DateTime.
   * e.g. 09/09/2016 at 04:35
   * 
   * @param String $string
   * @return DateTime
   * @since 0.5
   */
  private static function convert_slash_date_string($string) {

    $date = DateTime::createFromFormat('!m/d/Y at H:i', $string, new DateTimeZone(self::$tz_mal));
    
    return [$date, 'Y-m-d\TH:iO'];
    // return $date->format('Y-m-d\TH:iO');

  }

  /**
   * Convert the date format returned by searches to its DateTime.
   * e.g. 09-??-15
   * 
   * @param String $string
   * @return DateTime
   * @since 0.5
   */
  private static function convert_search_date_string($string) {

    if(preg_match('/(?:^|\s|$)\d{2}-\d{2}-\d{2}(?:^|\s|$)/', $string, $matches)) {

      // MM-DD-YY
      if(substr($matches[0], 6, 2) > 30) {
        $date = DateTime::createFromFormat('m-d-Y', substr($matches[0], 0, 6) . '19' . substr($matches[0], 6, 2));
      } else {
        $date = DateTime::createFromFormat('m-d-Y', substr($matches[0], 0, 6) . '20' . substr($matches[0], 6, 2));
      }

      return [$date, 'Y-m-d'];
      // return $date->format('Y-m-d');

    } else if(preg_match('/(?:^|\s|$)\d{2}-\d{2}-\?\?(?:^|\s|$)/', $string, $matches)) {

      // MM-DD-??
      return [null, 'Y-m-d', [
        'Y' => null,
        'm' => null,
        'd' => null
      ]];

    } else if(preg_match('/(?:^|\s|$)\d{2}-\?\?-\d{2}(?:^|\s|$)/', $string, $matches)) {

      // MM-??-YY
      if(substr($matches[0], 6, 2) > 30) {
        $date = DateTime::createFromFormat('m-??-Y', substr($matches[0], 0, 6) . '19' . substr($matches[0], 6, 2));
      } else {
        $date = DateTime::createFromFormat('m-??-Y', substr($matches[0], 0, 6) . '20' . substr($matches[0], 6, 2));
      }

      return [null, 'Y-m-d', [
        'Y' => null,
        'm' => null,
        'd' => null
      ]];

    } else if(preg_match('/(?:^|\s|$)\d{2}-\?\?-\?\?(?:^|\s|$)/', $string, $matches)) {

      // MM-??-??
      return [null, 'Y-m-d', [
        'Y' => null,
        'm' => null,
        'd' => null
      ]];

    } else if(preg_match('/(?:^|\s|$)\?\?-\d{2}-\d{2}(?:^|\s|$)/', $string, $matches)) {

      // ??-DD-YY
      return [null, 'Y-m-d', [
        'Y' => null,
        'm' => null,
        'd' => null
      ]];

    } else if(preg_match('/(?:^|\s|$)\?\?-\d{2}-\?\?(?:^|\s|$)/', $string, $matches)) {

      // ??-DD-??
      return [null, 'Y-m-d', [
        'Y' => null,
        'm' => null,
        'd' => null
      ]];

    } else if(preg_match('/(?:^|\s|$)\?\?-\?\?-\d{2}(?:^|\s|$)/', $string, $matches)) {

      // ??-??-YY
      // if(substr($matches[0], 6, 2) > 30) {
      //   $date = DateTime::createFromFormat('Y', '19' . substr($matches[0], 6, 2));
      // } else {
      //   $date = DateTime::createFromFormat('Y', '20' . substr($matches[0], 6, 2));
      // }

      return [null, 'Y-m-d', [
        'Y' => null,
        'm' => null,
        'd' => null
      ]];

    } else if(preg_match('/(?:^|\s|$)\?\?-\?\?-\?\?(?:^|\s|$)/', $string, $matches)) {

      // ??-??-??
      return [null, 'Y-m-d', [
        'Y' => null,
        'm' => null,
        'd' => null
      ]];

    }

    return [null, null];
  
  }

  /**
   * Convert plain years to its DateTime.
   * e.g. 2018
   * 
   * @param String $string
   * @return DateTime
   * @since 0.5
   */
  private static function convert_year_string($string) {

    $date = DateTime::createFromFormat('!Y', $string);

    return [$date, 'Y'];
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

      list($date, $format) = self::convert_now_string($string);

    } else if(strpos($string, 'Today') !== false) {

      list($date, $format) = self::convert_today_string($string);

    } else if(strpos($string, 'Yesterday') !== false) {

      list($date, $format) = self::convert_yesterday_string($string);

    } else if(strpos($string, 'ago') !== false) {

      // Note that these are returning approximate values.
      list($date, $format) = self::convert_ago_string($string);

    } else if(strpos($string, 'AM') !== false ||strpos($string, 'PM') !== false) {

      list($date, $format) = self::convert_exact_date_string($string);

    } else if(strpos($string, ', ') !== false) {

      list($date, $format) = self::convert_date_no_time_string($string);

    } else if(strpos($string, '/') !== false) {

      list($date, $format) = self::convert_slash_date_string($string);

    } else if(strpos($string, '-') !== false && strlen($string) === 8) {

      list($date, $format) = self::convert_search_date_string($string);

    } else {
      
      list($date, $format) = self::convert_year_string($string);

    }

    if($date === false || $date === null || $format === null) {
      return null;
    }

    $date->setTimeZone(new DateTimeZone(self::$tz_final));
    
    return self::convert_datetime_array($date, $format);
    
  }

  /**
   * Convert DateTimes to API-friendly associative arrays using the format provided.
   * 
   * @param DateTime $date
   * @param String $format
   * @return Array
   * @since 0.5
   */
  private static function convert_datetime_array($date, $format) {

    $date_array = [];

    if(strpos($format, 'Y') !== false) {
      $date_array['year'] = (int)$date->format('Y');
    }

    if(strpos($format, 'm') !== false) {
      $date_array['month'] = (int)$date->format('m');
    }

    if(strpos($format, 'd') !== false) {
      $date_array['day'] = (int)$date->format('d');
    }

    if(strpos($format, 'H') !== false) {
      $date_array['hour'] = (int)$date->format('H');
    }

    if(strpos($format, 'i') !== false) {
      $date_array['minute'] = (int)$date->format('i');
    }

    if(strpos($format, 's') !== false) {
      $date_array['second'] = (int)$date->format('s');
    }

    if(strpos($format, 'O') !== false) {
      $date_array['offset'] = $date->format('O');
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