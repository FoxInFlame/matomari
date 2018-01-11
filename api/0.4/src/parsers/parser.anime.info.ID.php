<?php

require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");
require_once(dirname(__FILE__) . "/../models/model.animeInfo.php");

class AnimeInfoIDParser {

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

    $anime = new AnimeInfo();

    // Important Information -------------------------------------------------------------------------------------

    // The ID
    // <input type="hidden" name="aid" value="16870">
    $anime->set("id", (int)$html->find("div#contentWrapper #editdiv input[name=\"aid\"]", 0)->value);

    // The Title
    // <span itemprop="name">The Last: Naruto the Movie</span>
    $anime->set("title", (string)$html->find("div#contentWrapper div h1.h1 span", 0)->innertext);

    // The MAL URL
    // <a href="https://myanimelist.net/anime/16870/The_Last__Naruto_the_Movie" class="horiznav_active">Details</a>
    $anime->set("mal_url", (string)$html->find("div#contentWrapper #content #horiznav_nav ul li a", 0)->href);

    // The Image URL
    // <img src="https://myanimelist.cdn-dena.com/images/anime/10/68631.jpg" alt="The Last: Naruto the Movie" class="ac" itemprop="image">
    if($html->find("div#contentWrapper div#content table div a img.ac", 0)) {
      $imageSource = $html->find("div#contentWrapper div#content table div a img.ac", 0)->src;
      if(strpos($imageSource, " 1x,") !== false) { // Contains two with ?s=
        $imageSource = explode(" 1x,", $imageSource)[0];
      }
      if(strpos($imageSource, "/r/") !== false) { // URL is /r/ and contains ?s=
        $imageSource = str_replace("/r/50x70", "", explode("?s=", $imageSource)[0]);
      }
      if(strpos($imageSource, "t.jpg") !== false || strpos($imageSource, "l.jpg") !== false) { // URL has a modifier
        $imageSource = str_replace("l.jpg", ".jpg", str_replace("t.jpg", ".jpg", $imageSource));
      }
      $anime->set("image_url", (string)$imageSource);
    }

    // The Score
    // <div class="fl-l score" data-title="score" data-user="80,686 users" title="indicates a weighted score. Please note that 'Not yet aired' titles are excluded.">     7.88  </div>
    if(trim($html->find("div#contentWrapper div#content div.anime-detail-header-stats .score", 0)->plaintext) !== "N/A") {
      $anime->set("score", (float)trim($html->find("div#contentWrapper div#content div.anime-detail-header-stats .score", 0)->plaintext));
    }

    // The Rank
    // <span class="numbers ranked" title="based on the top anime page. Please note that 'Not yet aired' and 'R18+' titles are excluded.">Ranked <strong>#735</strong></span>
    if($html->find("div#contentWrapper div#content div.anime-detail-header-stats span.ranked strong", 0)->plaintext != "N/A") {
      $anime->set("rank", (int)substr($html->find("div#contentWrapper div#content div.anime-detail-header-stats span.ranked strong", 0)->plaintext, 1));
    }

    // The Popularity
    // <span class="numbers popularity">Popularity <strong>#552</strong></span>
    if($html->find("div#contentWrapper div#content div.anime-detail-header-stats span.popularity strong", 0)->plaintext != "N/A") {
      $anime->set("popularity", (int)substr($html->find("div#contentWrapper div#content div.anime-detail-header-stats span.popularity strong", 0)->plaintext, 1));
    }

    // The Synopsis
    // <span itemprop="description">blahblah<br>blahblahblahb</span>
    if($html->find("div#contentWrapper div#content div.js-scrollfix-bottom-rel table td span[itemprop=description]", 0)) {
      $anime->set("synopsis", (string)htmlspecialchars_decode(html_entity_decode(trim($html->find("div#contentWrapper div#content div.js-scrollfix-bottom-rel table td span[itemprop=description]", 0)->innertext, " "), 0, "UTF-8")));
    }

    // Sidebar Information ---------------------------------------------------------------------------------------

    // The Alternative Titles
    // <div class="spaceit_pad">
    //   <span class="dark_text">English:</span>
    //     The Last: Naruto the Movie
    //
    // </div>
    $alternativeTitles = $html->find("div#contentWrapper div#content table div.js-scrollfix-bottom .spaceit_pad");
    $alternativeTitles_eng = array(); // Changed from string because there can be multiple
    $alternativeTitles_jap = array();
    $alternativeTitles_syn = array();
    foreach($alternativeTitles as $value) {
      if(strpos($value->plaintext, "English:") !== false) {
        $alternativeTitles_eng = explode(", ", trim($value->find("text", 2)->innertext));
      } else if(strpos($value->plaintext, "Japanese:") !== false) {
        $alternativeTitles_jap = explode(", ", trim($value->find("text", 2)->innertext));
      } else if(strpos($value->plaintext, "Synonyms:") !== false) {
        $alternativeTitles_syn = explode(", ", trim($value->find("text", 2)->innertext));
      }
    }
    unset($value);
    $anime->set("other_titles", array(
      "english" => $alternativeTitles_eng,
      "japanese" => $alternativeTitles_jap,
      "synonyms" => $alternativeTitles_syn
    ));

    $html_information = $html->find("div#contentWrapper div#content div.js-scrollfix-bottom div");
    foreach($html_information as $value) {

      // The Type
      // <div>
      //   <span class="dark_text">Type:</span>
      //   <a href="https://myanimelist.net/topanime.php?type=movie">Movie</a>
      // </div>
      if(strpos($value->plaintext, "Type:") !== false) {
        // MAL has an ending tag without a starting tag bug so remove that
        if(strpos($value->plaintext, "Unknown") === false) {
          $anime->set("type", (string)trim($value->find("a", 0)->innertext));
        }
      }

      // The Episodes
      // <div>
      //   <span class="dark_text">Episodes:</span>
      //   1
      // </div>
      if(strpos($value->plaintext, "Episodes:") !== false) {
        if(strpos($value->innertext, "Unknown") === false) {
          $anime->set("episodes", (int)trim($value->find("text", 2)->innertext));
        }
      }

      // The Airing Status
      // <div>
      //   <span class="dark_text">Status:</span>
      //   Finished Airing
      // </div>
      if(strpos($value->plaintext, "Status:") !== false) {
        if(strpos($value->innertext, "Unknown") === false) {
          $anime->set("air_status", strtolower(trim((string)$value->find("text", 2)->innertext)));
        }
      }

      // The Aired Date
      // <div class="spaceit">
      //   <span class="dark_text">Aired:</span>
      //   Dec 6th, 2014
      // </div>
      if(strpos($value->plaintext, "Aired:") !== false) {
        if(strpos($value->innertext, "Unknown") === false && strpos($value->innertext, "Not available") === false) {
          if(strpos($value->find("text", 2)->innertext, " to ") !== false) {
            // contains "to"
            $exploded = array_map("trim", explode(" to ", $value->find("text", 2)->innertext));
            if($exploded[0] !== "?") {
              $anime->set("air_dates//from", (string)getAbsoluteTimeGMT($exploded[0], "!M j, Y")->format("Y-m-d"));
            }
            if($exploded[1] !== "?") {
              $anime->set("air_dates//to", (string)getAbsoluteTimeGMT($exploded[1], "!M j, Y")->format("Y-m-d"));
            }
          } else if(strpos($value->find("text", 2), ",") !== false) {
            $anime->set("premier_date", (string)getAbsoluteTimeGMT($value->find("text", 2)->innertext, "!M j, Y")->format("Y-m-d"));
          } else {
            $anime->set("premier_date", (string)$value->find("text", 2)->innertext . "-xx-xx");
          }
        }
      }

      // The Season
      // <div>
      //   <span class="dark_text">Premiered:</span>
      //   <a href="https://myanimelist.net/anime/season/2014/fall">Fall 2014</a>
      // </div>
      if(strpos($value->plaintext, "Premiered:") !== false) {
        if(strpos($value->plaintext, "?") === false) {
          $anime->set("season", (string)trim($value->find("a", 0)->innertext));
        }
      }

      // The Broadcasting Time
      // <div class="spaceit">
      //   <span class="dark_text">Broadcast:</span>
      //   Thursdays at 23:30 (JST)
      // </div>
      if(strpos($value->plaintext, "Broadcast:") !== false) {
        if(strpos($value->plaintext, "Not scheduled once per week") !== false) {
          $anime->set("air_time", (string)"Irregular");
        } elseif(strpos($value->plaintext, "Unknown") === false) {
          $anime->set("air_time", (string)trim($value->find("text", 2)->innertext));
        }
      }

      // The Producers
      // <div>
      //   <span class="dark_text">Producers:</span>
      //   <a href="/anime/producer/64/Sotsu" title="Sotsu">Sotsu</a>
      //   ,
      //   <a href="/anime/producer/166/Movic" title="Movic">Movic</a>
      // </div>
      // TODO: Change below to joining the find("text") into an array and removing the first item
      if(strpos($value->plaintext, "Producers:") !== false) {
        if(strpos($value->innertext, "None found") === false) {
          $producers_str = trim(substr($value->plaintext, 16), " ");
          $producers_arr = explode(", ", $producers_str);
          $anime->set("producers", array_map("trim", $producers_arr));
        }
      }

      // The Licensors
      // <div class="spaceit">
      //   <span class="dark_text">Licensors:</span>
      //   <a href="/producer/376/Sentai_Filmworks" title="Sentai Filmworks">Sentai Filmworks</a>
      // </div>
      if(strpos($value->plaintext, "Licensors:") !== false) {
        if(strpos($value->innertext, "None found") === false) {
          $licensors_str = trim(substr($value->plaintext, 16), " ");
          $licensors_str = explode(", ", $licensors_str);
          $anime->set("licensors", array_map("trim", $licensors_str));
        }
      }

      // The Studios
      // <div>
      //   <span class="dark_text">Studios:</span>
      //   <a href="/anime/producer/132/PA_Works" title="P.A. Works">P.A. Works</a>
      // </div>
      if(strpos($value->plaintext, "Studios:") !== false) {
        if(strpos($value->innertext, "None found") === false) {
          $studios_str = trim(substr($value->plaintext, 14), " ");
          $studios_str = explode(", ", $studios_str);
          $anime->set("studios", array_map("trim", $studios_str));
        }
      }

      // The Source
      // <div class="spaceit">
      //   <span class="dark_text">Source:</span>
      //   Original
      // </div>
      if(strpos($value->plaintext, "Source:") !== false) {
        if(strpos($value->innertext, "Unknown") === false) {
          $anime->set("source", (string)trim($value->find("text", 2)->innertext));
        }
      }

      // The Genres
      // <div>
      //   <span class="dark_text">Genres:</span>
      //   <a href="/anime/genre/4/Comedy" title="Comedy">Comedy</a>
      // </div>
      if(strpos($value->plaintext, "Genres:") !== false) {
        if(strpos($value->innertext, "No genres have been added yet.") === false) {
          $genres_str = trim(substr($value->plaintext, 11), " ");
          $genres_str = explode(", ", $genres_str);
          $anime->set("genres", array_map("trim", $genres_str));
        }
      }

      // The Duration
      // <div class="spaceit">
      //   <span class="dark_text">Duration:</span>
      //   24 min. per ep.
      // </div>
      if(strpos($value->plaintext, "Duration:") !== false) {
        if(strpos($value->plaintext, "Unknown") === false) {
          if(strpos($value->plaintext, "hr.") !== false) {
            preg_match("/\d+(?= hr.)/", $value->plaintext, $matches);
            $hour = trim($matches[0], " ");
            $hour_minutes = intval($hour) * 60;
            $minutes = $hour_minutes;
          }
          if(strpos($value->plaintext, "min.") !== false) {
            preg_match("/\d+(?= min.)/", $value->plaintext, $matches);
            $minutes = trim($matches[0], " ");
            if(isset($hour_minutes)) {
              $minutes = intval($minutes) + intval($hour_minutes);
            }
          }
          if(strpos($value->plaintext, "sec.") !== false) { // Example id: 33902
            preg_match("/\d+(?= sec.)/", $value->plaintext, $matches);
            $seconds = trim($matches[0], " ");
            $seconds_minutes = $seconds / 60;
            if(isset($minutes)) {
              $minutes = intval($minutes) + intval($seconds_minutes);
            } else {
              $minutes = $seconds_minutes;
            }
          }

          $anime->set("duration//per_episode", (int)$minutes);
          if($anime->get("episodes") == null) {
            $anime->set("duration//total", null);
          } else {
            $anime->set("duration//total", (int)$anime->get("episodes") * (int)$minutes);
          }
        }
      }

      // The Rating
      // <div>
      //   <span class="dark_text">Rating:</span>
      //   PG-13 - Teens 13 or older
      // </div>
      if(strpos($value->plaintext, "Rating:") !== false) {
        if(strpos($value->plaintext, "Unknown") === false) {
          $anime->set("rating", (string)trim($value->find("text" , 2)->innertext));
        }
      }

      // The Members who Scored a score
      // <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="po-r js-statistics-info di-ib" data-id="info1">
      //   <span class="dark_text">Score:</span>
      //   <span itemprop="ratingValue">8.46</span>
      //   <sup>1</sup>
      //    (scored by )
      //   <span itemprop="ratingCount">66,462</span>
      //    users)
      //   <meta itemprop="bestRating" content="10">
      //   <meta itemprop="worstRating" content="1">
      // </div>
      if(strpos($value->plaintext, "Score:") !== false) {
        if(strpos($value->plaintext, "users") !== false) {
          $anime->set("members_scored", (int)trim(str_replace(",", "", $value->find("span", 2)->innertext)));
        }
      }

      // The Members who have it in their list
      // <div class="spaceit">
      //   <span class="dark_text">Members:</span>
      //   195,615
      // </div>
      if(strpos($value->plaintext, "Members:") !== false) {
        $anime->set("members_inlist", (int)trim(str_replace(",", "", $value->find("text", 2)->innertext)));
      }

      // The Members who favorited it
      // <div>
      //   <span class="dark_text">Favorites:</span>
      //   4,277
      // </div>
      if(strpos($value->plaintext, "Favorites:") !== false) {
        $anime->set("members_favorited", (int)trim(str_replace(",", "", $value->find("text", 2)->innertext)));
      }
    }

    // The Background
    // Complicated shit right here. 
    // It also seems like a <h2> appears after the background when ran from the server but locally it is a <div> that appears.
    $background_td = $html->find("div#contentWrapper div#content .js-scrollfix-bottom-rel", 0)->find("table td", 0)->innertext;
    $background_td_section = explode("</h2>", $background_td)[2];
    $background = explode("<h2", explode("<div", $background_td_section)[0])[0];
    if(strpos($background, "No background information has been added to this title") === false) {
      $anime->set("background", (string)trim($background));
    }

    return $anime->asArray();
  }

};

?>
