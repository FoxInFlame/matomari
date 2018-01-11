<?php

require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");
require_once(dirname(__FILE__) . "/../models/model.meListAnime.php");

class MeListAnimeIDParser {

  public static function parse($response) {
    $html = str_get_html($response);

    if(!is_object($html)) {
      echo json_encode(array(
        "message" => "The code for MAL is not valid HTML markup.",
      ));
      http_response_code(502);
      return;
    }

    if(!is_object($html->find("body.page-common"))) {
      echo json_encode(array(
        "message" => "MAL is currently under maintenance."
      ));
      http_response_code(503);
      return;
    }

    $anime = new MeListAnime();

    // The ID
    // <input type="hidden" name="anime_id" id="anime_id" value="32281">
    $anime->set("id", (int)$html->find("#anime_id", 0)->value);

    // The Status
    // <select id="add_anime_status" name="add_anime[status]" class="inputtext">
    //    <option value="1">Watching</option><option value="2" selected="selected">Completed</option><option value="3">On-Hold</option><option value="4">Dropped</option><option value="6">Plan to Watch</option></select>
    $anime->set("watch_status", (int)$html->find("#add_anime_status option[selected]", 0)->value);

    // The Episodes
    // <input type="text" id="add_anime_num_watched_episodes" name="add_anime[num_watched_episodes]" class="inputtext" size="3" onchange="StatusBooleanCheck();" value="1">
    $anime->set("watched_episodes", (int)$html->find("#add_anime_num_watched_episodes", 0)->value);

    // The Score
    // <select id="add_anime_score" name="add_anime[score]" class="inputtext">
    //    <option value="">Select score</option><option value="10" selected="selected">(10) Masterpiece</option><option value="9">(9) Great</option><option value="8">(8) Very Good</option><option value="7">(7) Good</option><option value="6">(6) Fine</option><option value="5">(5) Average</option><option value="4">(4) Bad</option><option value="3">(3) Very Bad</option><option value="2">(2) Horrible</option><option value="1">(1) Appalling</option></select>
    if($html->find("#add_anime_score option[selected]", 0) && $html->find("#add_anime_score option[selected]", 0)->value !== "") {
      $anime->set("watch_score", (int)$html->find("#add_anime_score option[selected]", 0)->value);
    }

    // The "from" watch dates
    // Year:
    // <select id="add_anime_start_date_year" name="add_anime[start_date][year]" required="required" class="inputtext">
    //  <option value=""></option><option value="2017">2017</option>...<option value="1987">1987</option></select>
    if($html->find("#add_anime_start_date_year option[selected]", 0) && $html->find("#add_anime_start_date_year option[selected]", 0)->value !== "") {
      $watch_date_from_year = $html->find("#add_anime_start_date_year option[selected]", 0)->value;
    } else {
      $watch_date_from_year = "xxxx";
    }
    // Month:
    // <select id="add_anime_start_date_month" name="add_anime[start_date][month]" required="required" class="inputtext">
    //   <option value=""></option><option value="1">Jan</option>...<option value="12">Dec</option></select>
    if($html->find("#add_anime_start_date_month option[selected]", 0) && $html->find("#add_anime_start_date_month option[selected]", 0)->value !== "") {
      $watch_date_from_month = substr("0" . $html->find("#add_anime_start_date_month option[selected]", 0)->value, -2);
    } else {
      $watch_date_from_month = "xx";
    }
    // Day:
    // <select id="add_anime_start_date_day" name="add_anime[start_date][day]" required="required" class="inputtext">
    //   <option value=""></option><option value="1">1</option>...<option value="31">31</option></select>
    if($html->find("#add_anime_start_date_day option[selected]", 0) && $html->find("#add_anime_start_date_day option[selected]", 0)->value !== "") {
      $watch_date_from_day = substr("0" . $html->find("#add_anime_start_date_day option[selected]", 0)->value, -2);
    } else {
      $watch_date_from_day = "xx";
    }
    $anime->set("watch_dates//from", (string)($watch_date_from_year . "-" . $watch_date_from_month . "-" . $watch_date_from_day));

    // The "to" watch dates
    // Year:
    // <select id="add_anime_finish_date_year" name="add_anime[finish_date][year]" required="required" class="inputtext">
    //  <option value=""></option><option value="2017">2017</option>...<option value="1987">1987</option></select>
    if($html->find("#add_anime_finish_date_year option[selected]", 0) && $html->find("#add_anime_finish_date_year option[selected]", 0)->value !== "") {
      $watch_date_to_year = $html->find("#add_anime_finish_date_year option[selected]", 0)->value;
    } else {
      $watch_date_to_year = "xxxx";
    }
    // Month:
    // <select id="add_anime_start_date_month" name="add_anime[start_date][month]" required="required" class="inputtext">
    //   <option value=""></option><option value="1">Jan</option>...<option value="12">Dec</option></select>
    if($html->find("#add_anime_finish_date_month option[selected]", 0) && $html->find("#add_anime_finish_date_month option[selected]", 0)->value !== "") {
      $watch_date_to_month = substr("0" . $html->find("#add_anime_finish_date_month option[selected]", 0)->value, -2);
    } else {
      $watch_date_to_month = "xx";
    }
    // Day:
    // <select id="add_anime_finish_date_day" name="add_anime[finish_date][day]" required="required" class="inputtext">
    //   <option value=""></option><option value="1">1</option>...<option value="31">31</option></select>
    if($html->find("#add_anime_finish_date_day option[selected]", 0) && $html->find("#add_anime_finish_date_day option[selected]", 0)->value !== "") {
      $watch_date_to_day = substr("0" . $html->find("#add_anime_finish_date_day option[selected]", 0)->value, -2);
    } else {
      $watch_date_to_day = "xx";
    }
    $anime->set("watch_dates//to", (string)($watch_date_to_year . "-" . $watch_date_to_month . "-" . $watch_date_to_day));
    
    // The Tags
    // <textarea id="add_anime_tags" name="add_anime[tags]" class="textarea" rows="3" cols="45">Drama,  Romance,  School,  Supernatural,  Source:Original,  THIS ANIME HAS A 9.4 RATING; THE HIGHEST ON MY LIST,  OKAY THIS ANIME IS BEAUTIFUL I WATCHED IT THE DAY IT CAME OUT AND I CRIED,  Studio:CoMix Wave Films</textarea>
    $anime->set("tags", (string)html_entity_decode($html->find("#add_anime_tags", 0)->innertext, ENT_QUOTES));

    // The Priority
    // <select id="add_anime_priority" name="add_anime[priority]" class="inputtext">
    //    <option value="0" selected="selected">Low</option><option value="1">Medium</option><option value="2">High</option></select>
    $anime->set("priority", (int)$html->find("#add_anime_priority option[selected]", 0)->value);

    // The Storage
    // <select id="add_anime_storage_type" name="add_anime[storage_type]" class="inputtext">
    //    <option value="">Select storage type</option><option value="1">Hard Drive</option>...<option value="3">None</option></select>
    if($html->find("#add_anime_storage_type option[selected]", 0) && $html->find("#add_anime_storage_type option[selected]", 0)->value !== "") {
      $anime->set("storage", (int)$html->find("#add_anime_storage_type option[selected]", 0)->value);
    }

    // The Storage Amount
    // <input type="text" id="add_anime_storage_value" name="add_anime[storage_value]" class="inputtext" size="4" value="0">
    $anime->set("storage_amount", (int)$html->find("#add_anime_storage_value", 0)->value);

    // The Rewatching Status
    $anime->set("rewatching", (bool)$html->find("#add_anime_is_rewatching", 0)->checked);

    // The Rewatch Times
    // <input type="text" id="add_anime_num_watched_times" name="add_anime[num_watched_times]" class="inputtext" size="4" value="0">
    $anime->set("rewatch_times", (int)$html->find("#add_anime_num_watched_times", 0)->value);

    // The Rewatch Value
    // <select id="add_anime_rewatch_value" name="add_anime[rewatch_value]" class="inputtext">
    //    <option value="">Select rewatch value</option><option value="1">Very Low</option>...<option value="5">Very High</option></select>
    if($html->find("#add_anime_rewatch_value option[selected]", 0) && $html->find("#add_anime_rewatch_value option[selected]", 0)->value !== "") {
      $anime->set("rewatch_value", (int)$html->find("#add_anime_rewatch_value option[selected]", 0)->value);
    }

    // The Comments
    // <textarea id="add_anime_comments" name="add_anime[comments]" class="inputtext" rows="5" cols="45">Best anime of the year [b]2016[/b].</textarea>
    $anime->set("comments", (string)html_entity_decode($html->find("#add_anime_comments", 0)->innertext, ENT_QUOTES));

    return $anime->asArray();
  }

};

?>