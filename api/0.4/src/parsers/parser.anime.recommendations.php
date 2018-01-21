<?php

require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");
require_once(dirname(__FILE__) . "/../models/model.recommendation.php");

class AnimeRecommendationsParser {

  public static function parse($response) {
    $html = str_get_html($response);

    if(!is_object($html)) {
      echo json_encode(array(
        "message" => "The code for MAL is not valid HTML markup.",
      ));
      http_response_code(502);
      return;
    }
    
    $recommendation_items = $html->find("#contentWrapper #content", 0)->children(2)->children();
    $recommendations_arr = array();

    foreach($recommendation_items as $key => $item) {

      if($key === 0) {
        continue;
      }
      if($key === (count($recommendation_items) - 1)) {
        continue;
      }

      $recommendation = new Recommendation();

      // The recommendation ID
      // <a href="dbchanges.php?go=reportanimerecommendation&amp;id=139395" class="lightLink">report</a>
      $tmp_url_explode = explode("=", $item->find("div.lightLink a.lightLink", 0)->href);
      $recommendation->set("id", (int)end($tmp_url_explode));
      $from = $item->find("table td", 0);

      // The From ID
      // <a class="hoverinfo_trigger" id="#raArea1_801" rel="#raInfo1_801" href="/anime/801/Ghost_in_the_Shell__Stand_Alone_Complex_2nd_GIG">...</a>
      $recommendation->set("rec_from//id", (int)substr($from->find(".picSurround a", 0)->id, 9));

      // The From Title
      // <strong>Ghost in the Shell: Stand Alone Complex 2nd GIG</strong>
      $recommendation->set("rec_from//title", (string)$from->find("a strong", 0)->innertext);

      // The MAL URL
      // <a href="/anime/801/Ghost_in_the_Shell__Stand_Alone_Complex_2nd_GIG" title="Ghost in the Shell: Stand Alone Complex 2nd GIG"><strong>Ghost in the Shell: Stand Alone Complex 2nd GIG</strong></a>
      $recommendation->set("rec_from//mal_url", (string)"https://myanimelist.net" . $from->find("a", 0)->href);

      // The From Image URL
      // <img src="https://myanimelist.cdn-dena.com/images/anime/11/51465t.webp" data-src="https://myanimelist.cdn-dena.com/images/anime/11/51465t.webp" data-srcset="https://myanimelist.cdn-dena.com/images/anime/11/51465t.webp 1x,https://myanimelist.cdn-dena.com/r/100x140/images/anime/11/51465.webp?s=713477ab78fb1bbe684e9805e5ed0b55 2x" width="50" alt="Anime: Ghost in the Shell: Stand Alone Complex 2nd GIG" class=" lazyloaded" srcset="https://myanimelist.cdn-dena.com/images/anime/11/51465t.webp 1x,https://myanimelist.cdn-dena.com/r/100x140/images/anime/11/51465.webp?s=713477ab78fb1bbe684e9805e5ed0b55 2x">
      if($from->find(".picSurround a img", 0)->{'data-srcset'}) {
        $recommendation->set("rec_from//image_url", (string)str_replace("t.webp", ".jpg", str_replace("t.jpg", ".jpg", explode(" 1x", $from->find(".picSurround a img", 0)->{'data-srcset'})[0])));
      } else {
        $recommendation->set("rec_from//image_url", (string)str_replace("t.webp", ".jpg", str_replace("t.jpg", ".jpg", explode(" 1x", $from->find(".picSurround a img", 0)->srcset)[0])));
      }

      $to = $item->find("table td", 1);

      // The To ID
      // <a class="hoverinfo_trigger" id="#raArea2_1096" rel="#raInfo2_1096" href="/anime/1096/Mobile_Police_Patlabor_2__The_Movie">...</a>
      $recommendation->set("rec_to//id", (int)substr($to->find(".picSurround a", 0)->id, 9));

      // The To Title
      // <strong>Mobile Police Patlabor 2: The Movie</strong>
      $recommendation->set("rec_to//title", (string)$to->find("a strong", 0)->innertext);

      // The MAL URL
      // <a href="/anime/1096/Mobile_Police_Patlabor_2__The_Movie" title="Mobile Police Patlabor 2: The Movie"><strong>Mobile Police Patlabor 2: The Movie</strong></a>
      $recommendation->set("rec_to//mal_url", (string)"https://myanimelist.net" . $to->find("a", 0)->href);
      // The To Image URL
      // <img src="https://myanimelist.cdn-dena.com/images/anime/6/74764t.webp" data-src="https://myanimelist.cdn-dena.com/images/anime/6/74764t.webp" data-srcset="https://myanimelist.cdn-dena.com/images/anime/6/74764t.webp 1x,https://myanimelist.cdn-dena.com/r/100x140/images/anime/6/74764.webp?s=9c4ea7009abb614a65d2b313c1ad03de 2x" width="50" alt="Anime: Mobile Police Patlabor 2: The Movie" class=" lazyloaded" srcset="https://myanimelist.cdn-dena.com/images/anime/6/74764t.webp 1x,https://myanimelist.cdn-dena.com/r/100x140/images/anime/6/74764.webp?s=9c4ea7009abb614a65d2b313c1ad03de 2x">
      if($to->find(".picSurround a img", 0)->{'data-srcset'}) {
        $recommendation->set("rec_to//image_url", (string)str_replace("t.webp", ".jpg", str_replace("t.jpg", ".jpg", explode(" 1x", $to->find(".picSurround a img", 0)->{'data-srcset'})[0])));
      } else {
        $recommendation->set("rec_to//image_url", (string)str_replace("t.webp", ".jpg", str_replace("t.jpg", ".jpg", explode(" 1x", $to->find(".picSurround a img", 0)->srcset)[0])));
      }

      // The Reason
      // <div class="spaceit recommendations-user-recs-text">Both have realistic near-future science fiction settings in which teams of (mostly) competent professional adult protagonists combat crime. Both combine sociopolitical themes with tense action. Both were done by Production I.G.<br><br>There are also plot similarities, but elaborating on those would go into spoiler territory.</div>
      $recommendation->set("reason", (string)$item->find(".recommendations-user-recs-text", 0)->innertext);

      // The Author
      // <div class="lightLink spaceit"><div style="float: right;"> <a href="dbchanges.php?go=reportanimerecommendation&amp;id=138133" class="lightLink">report</a></div>Anime rec by <a href="/profile/Chartsengrafs">Chartsengrafs</a> - 17 minutes ago</div>
      $recommendation->set("author", (string)$item->children(2)->find("a", 1)->innertext);

      // The timestamp
      $time_htmlarray = explode(" - ", $item->children(2)->innertext);
      $recommendation->set("timestamp", (string)getAbsoluteTimeGMT(end($time_htmlarray), "M j, Y|")->format("c"));

      array_push($recommendations_arr, $recommendation->asArray());
    }

    return $recommendations_arr;
  }

};

?>