<?php

require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");
require_once(dirname(__FILE__) . "/../models/model.userListAnime.php");

class UserListAnimeUSERNAMEParser {

  public static function parse($xmlresponse, $jsonresponse) {
    libxml_use_internal_errors(true);
    $xmlresponse = new SimpleXMLElement($xmlresponse);
    if(!$xmlresponse) {
      echo json_encode(array(
        "message" => "The code for MAL is not valid XML markup.",
      ));
      http_response_code(500);
      return;
    }

    $jsonresponse_arr = @json_decode($jsonresponse);
    if($jsonresponse_arr === null && json_last_error() !== JSON_ERROR_NONE) {
      echo json_encode(array(
        "message" => "The code for MAL is not valid JSON markup.",
      ));
      http_response_code(500);
      return;
    }

    $malresponse_json_reformatted = [];
    foreach($jsonresponse_arr as $jsonresponse_arr_item) {
      $jsonresponse_arr_new[$jsonresponse_arr_item->anime_id] = $jsonresponse_arr_item;
    }

    $anime_arr = array();

    foreach($xmlresponse->anime as $xmlanime) {
      $key = (string)$xmlanime->series_animedb_id;
      if(!array_key_exists($key, $jsonresponse_arr_new)) continue;

      $anime = new UserListAnime();

      $anime->set("id", (int)$xmlanime->series_animedb_id);
      $anime->set("title", (string)$jsonresponse_arr_new[$key]->anime_title);
      $anime->set("mal_url", (string)"https://myanimelist.net" . $jsonresponse_arr_new[$key]->anime_url);
      $anime->set("image_url", (string)$xmlanime->series_image);
      $anime->set("other_titles", array(
        "synonyms" => (array)array_values(array_filter(explode("; ", (string)$xmlanime->series_synonyms)))
        // explode, then remove empty elements ("", which appear in many anime) (which will result in key-preserved array), and get the values as a new array
      ));
      $anime->set("type", (string)$jsonresponse_arr_new[$key]->anime_media_type_string);
      $anime->set("episodes", (int)$jsonresponse_arr_new[$key]->anime_num_episodes);
      $anime->set("air_status", (int)$jsonresponse_arr_new[$key]->anime_airing_status);

      $mal_air_start_date = $jsonresponse_arr_new[$key]->anime_start_date_string;
      if($mal_air_start_date !== null) {
        foreach(explode("-", $mal_air_start_date) as $index => $number) {
          // load.json returns the exact same date format as /anime search, except for the unknown parts.
          // MM-DD-YY (and 00 if unknown, null if everything is unknown)
          if($index == 0) {
            $month = $number;
            if($month == "00") {
              $month = "-";
            }
          }
          if($index == 1) {
            $day = $number;
            if($day == "00") {
              $day = "-";
            }
          }
          if($index == 2) {
            $year = $number;
            if($year == "00") {
              $year = "-";
            } else {
              if($year > 40) { // Some anime are made in 1968, so I can't use date_format from y to Y.
                // Over 1940
                $year = "19" . $year;
              } else {
                // Under 2040
                $year = "20" . $year;
              }
            }
          }
        }
        $anime->set("air_dates//from", (string)$year . "-" . $month . "-" . $day);
      }

      $mal_air_end_date = $jsonresponse_arr_new[$key]->anime_end_date_string;
      if($mal_air_end_date !== null) {
        foreach(explode("-", $mal_air_end_date) as $index => $number) {
          // load.json returns the exact same date format as /anime search, except for the unknown parts.
          // MM-DD-YY (and 00 if unknown, null if everything is unknown)
          if($index == 0) {
            $month = $number;
            if($month == "00") {
              $month = "-";
            }
          }
          if($index == 1) {
            $day = $number;
            if($day == "00") {
              $day = "-";
            }
          }
          if($index == 2) {
            $year = $number;
            if($year == "00") {
              $year = "-";
            } else {
              if($year > 40) { // Some anime are made in 1968, so I can't use date_format from y to Y.
                // Over 1940
                $year = "19" . $year;
              } else {
                // Under 2040
                $year = "20" . $year;
              }
            }
          }
        }
        $anime->set("air_dates//to", (string)$year . "-" . $month . "-" . $day);
      }
      $anime->set("rating", (string)$jsonresponse_arr_new[$key]->anime_mpaa_rating_string);
      $anime->set("watch_status", (int)$jsonresponse_arr_new[$key]->status);
      $anime->set("watched_episodes", (int)$jsonresponse_arr_new[$key]->num_watched_episodes);
      $anime->set("watch_score", (int)$jsonresponse_arr_new[$key]->score);

      $mal_watch_start_date = $jsonresponse_arr_new[$key]->start_date_string;
      if($mal_watch_start_date !== null) {
        foreach(explode("-", $mal_watch_start_date) as $index => $number) {
          // load.json returns the exact same date format as /anime search, except for the unknown parts.
          // MM-DD-YY (and 00 if unknown, null if everything is unknown)
          if($index == 0) {
            $month = $number;
            if($month == "00") {
              $month = "-";
            }
          }
          if($index == 1) {
            $day = $number;
            if($day == "00") {
              $day = "-";
            }
          }
          if($index == 2) {
            $year = $number;
            if($year == "00") {
              $year = "-";
            } else {
              if($year > 40) { // Some anime are made in 1968, so I can't use date_format from y to Y.
                // Over 1940
                $year = "19" . $year;
              } else {
                // Under 2040
                $year = "20" . $year;
              }
            }
          }
        }
        $anime->set("watch_dates//from", (string)$year . "-" . $month . "-" . $day);
      }

      $mal_watch_finish_date = $jsonresponse_arr_new[$key]->finish_date_string;
      if($mal_watch_finish_date !== null) {
        foreach(explode("-", $mal_watch_finish_date) as $index => $number) {
          // load.json returns the exact same date format as /anime search, except for the unknown parts.
          // MM-DD-YY (and 00 if unknown, null if everything is unknown)
          if($index == 0) {
            $month = $number;
            if($month == "00") {
              $month = "-";
            }
          }
          if($index == 1) {
            $day = $number;
            if($day == "00") {
              $day = "-";
            }
          }
          if($index == 2) {
            $year = $number;
            if($year == "00") {
              $year = "-";
            } else {
              if($year > 40) { // Some anime are made in 1968, so I can't use date_format from y to Y.
                // Over 1940
                $year = "19" . $year;
              } else {
                // Under 2040
                $year = "20" . $year;
              }
            }
          }
        }
        $anime->set("watch_dates//to", (string)$year . "-" . $month . "-" . $day);
      }

      $anime->set("tags", (string)$jsonresponse_arr_new[$key]->tags);
      $anime->set("priority", (string)strtolower($jsonresponse_arr_new[$key]->priority_string));

      if($jsonresponse_arr_new[$key]->storage_string !== "") {
        $storage_stuff = explode(" ", $jsonresponse_arr_new[$key]->storage_string);
        if($storage_stuff === null) {
          $anime->set("storage", (string)"none");
        } else {
          $anime->set("storage", (string)$storage_stuff[0]);
          $anime->set("storage_amount", (float)$storage_stuff[1]);
        }
      }
      $anime->set("rewatching", (bool)($jsonresponse_arr_new[$key]->is_rewatching === 1));
      $anime->set("last_updated", (string)getAbsoluteTimeGMT($xmlanime->my_last_updated, "U")->format("c"));
      $anime->set("days_spent_watching", (int)$jsonresponse_arr_new[$key]->days_string);

      array_push($anime_arr, $anime->asArray());
    }

    $anime_stats = array(
      "watching" => (int)$xmlresponse->myinfo->user_watching,
      "completed" => (int)$xmlresponse->myinfo->user_completed,
      "on_hold" => (int)$xmlresponse->myinfo->user_onhold,
      "dropped" => (int)$xmlresponse->myinfo->user_dropped,
      "plan_to_watch" => (int)$xmlresponse->myinfo->plantowatch,
      "total" => (int)$xmlresponse->myinfo->user_watching + (int)$xmlresponse->myinfo->user_completed + (int)$xmlresponse->myinfo->user_onhold + (int)$xmlresponse->myinfo->user_dropped + (int)$xmlresponse->myinfo->plantowatch
    );

    return [$anime_stats, $anime_arr];
  }
}