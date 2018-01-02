<?php

require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");
require_once(dirname(__FILE__) . "/../models/model.animeSearch.php");

class AnimeSearchQUERYParser {

  public static function parse($response) {
    $html = str_get_html($response);

    if(!is_object($html)) {
      echo json_encode(array(
        "message" => "The code for MAL is not valid HTML markup.",
      ));
      http_response_code(500);
      return;
    }

    $tr = $html->find("#contentWrapper #content div.list table tbody tr");
    if(count($tr) == 0) { // No results
      return array();
    }

    array_shift($tr); // Remove first item (the table head)
    $anime_arr = array();
    foreach($tr as $value) {
      $td_image = $value->find("td", 0);
      $td_title = $value->find("td", 1);
      $td_type = $value->find("td", 2);
      $td_episodes = $value->find("td", 3);
      $td_score = $value->find("td", 4);
      $td_air_date_from = $value->find("td", 5);
      $td_air_date_to = $value->find("td", 6);
      $td_members_inlist = $value->find("td", 7);
      $td_rating = $value->find("td", 8);

      $anime = new AnimeSearch();

      // The ID
      // <a class="hoverinfo_trigger" href="https://myanimelist.net/anime/32/Neon_Genesis_Evangelion__The_End_of_Evangelion" id="sarea32" rel="#sinfo32"><img....></a>
      $anime->set("id", (int)substr(trim($td_image->find("div.picSurround a", 0)->id), 5));

      // The Title
      // <strong>Neon Genesis Evangelion: The End of Evangelion</strong>
      $anime->set("title", (string)$td_title->find("a.hoverinfo_trigger strong", 0)->innertext);

      // The MAL URL
      // <a class="hoverinfo_trigger fw-b fl-l" href="https://myanimelist.net/anime/32/Neon_Genesis_Evangelion__The_End_of_Evangelion" id="sinfo32" rel="#sinfo32" style="display: inline-block;">
      $anime->set("mal_url", (string)$td_title->find("a.hoverinfo_trigger", 0)->href);

      // The Image URL
      // <img width="50" height="70" alt="Neon Genesis Evangelion: The End of Evangelion" border="0" data-src="https://myanimelist.cdn-dena.com/r/50x70/images/anime/12/39305.webp?s=bac27bf278a72f9183efddf91d6b82fb" data-srcset="https://myanimelist.cdn-dena.com/r/50x70/images/anime/12/39305.webp?s=bac27bf278a72f9183efddf91d6b82fb 1x, https://myanimelist.cdn-dena.com/r/100x140/images/anime/12/39305.webp?s=32459820b2bc4896d87f77accae13005 2x" class=" lazyloaded" srcset="https://myanimelist.cdn-dena.com/r/50x70/images/anime/12/39305.webp?s=bac27bf278a72f9183efddf91d6b82fb 1x, https://myanimelist.cdn-dena.com/r/100x140/images/anime/12/39305.webp?s=32459820b2bc4896d87f77accae13005 2x" src="https://myanimelist.cdn-dena.com/r/50x70/images/anime/12/39305.webp?s=bac27bf278a72f9183efddf91d6b82fb">
      if($td_image->find("div.picSurround a img", 0)->{'data-srcset'}) {
        $anime->set("image_url", (string)str_replace("webp", "jpg", str_replace("/r/50x70", "", explode("?s=", $td_image->find("div.picSurround a img", 0)->{'data-srcset'})[0])));
      } else {
        $anime->set("image_url", (string)str_replace("webp", "jpg", str_replace("/r/50x70", "", explode("?s=", $td_image->find("div.picSurround a img", 0)->srcset)[0])));
      }

      // The Score
      // <td class="borderClass ac bgColor0" width="50">    8.46  </td>
      if(trim($td_score->innertext) !== "N/A") {
        $anime->set("score", (float)trim($td_score->innertext));
      }

      // The Type
      // <td class="borderClass ac bgColor0" width="45">    Movie  </td>
      if(trim($td_type->innertext) !== "-") {
        $anime->set("type", (string)trim($td_type->innertext));
      }

      // The Episodes
      // <td class="borderClass ac bgColor0" width="40">    1  </td>
      if(trim($td_episodes->innertext) !== "-") {
        $anime->set("episodes", (int)trim($td_episodes->innertext));
      }

      // The Air-From Date
      // MM-DD-YY (and ?? if unknown, - if everything is unknown)
      $mal_air_date_from = trim($td_air_date_from->innertext);
      if($mal_air_date_from !== "-") {
        foreach(explode("-", $mal_air_date_from) as $index => $number) {
          /* Will turn 2017-04-?? into 2017-04-- using the ISO 8601 standard on Wikipedia*/
          if($index == 0) {
            $month = $number;
            if($month == "??") {
              $month = "-";
            }
          }
          if($index == 1) {
            $day = $number;
            if($day == "??") {
              $day = "-";
            }
          }
          if($index == 2) {
            $year = $number;
            if($year == "??") {
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
        $anime->set("air_dates//from", (string)$year . "-" . $month . "-" .$day);
      }

      // The Air-To Date
      $mal_air_date_to = trim($td_air_date_to->innertext);
      if($mal_air_date_to !== "-") {
        foreach(explode("-", $mal_air_date_to) as $index => $number) {
          if($index == 0) {
            $month = $number;
            if($month == "??") {
              $month = "-";
            }
          }
          if($index == 1) {
            $day = $number;
            if($day == "??") {
              $day = "-";
            }
          }
          if($index == 2) {
            $year = $number;
            if($year == "??") {
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

      // The Rating
      // <td class="borderClass ac bgColor0" width="75">    R+  </td>
      if(trim($td_rating->innertext) !== "-") {
        $anime->set("rating", (string)trim($td_rating->innertext));
      }

      // The Members who have it in their list
      // <td class="borderClass ac bgColor0" width="75">    262,866  </td>
      $anime->set("members_inlist", (int)str_replace(",", "", trim($td_members_inlist->innertext)));

      array_push($anime_arr, $anime->asArray());

    }

    return $anime_arr;
  }

};

?>