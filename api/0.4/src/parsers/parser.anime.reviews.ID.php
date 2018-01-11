<?php

require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");
require_once(dirname(__FILE__) . "/../models/model.animeReview.php");

class AnimeReviewsIDParser {

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

    $contentWrapper = $html->find("#contentWrapper", 0);
    $target_id = $contentWrapper->find("#myinfo_anime_id", 0)->value;
    $target_title = $contentWrapper->find("div h1.h1 span", 0)->innertext;
    $reviews = $contentWrapper->find("#content tr", 0)->children()[1]->find("div.borderDark");
    
    if(count($reviews) == 0) { // No reviews
      return array();
    }

    $reviews_arr = array();
    foreach($reviews as $value) {
      $review = new AnimeReview();

      // Important Information -------------------------------------------------------------------------------------

      // The ID
      // <a href="javascript:void(0);" data-id="244720" data-val="1" class="js-vote-review-button button_form">Helpful</a>
      $review->set("id", (int)substr($value->find(".textReadability div", 0)->id, 5));

      // The MAL URL
      $review->set("mal_url", (string)("https://myanimelist.net/reviews.php?id=" . (string)$review->get("id")));

      // The target anime
      $review->set("target//id", (int)$target_id);
      $review->set("target//title", (string)$target_title);

      // Episodes Seen
      // <div class="lightLink spaceit">
      //            24 of 24 episodes seen
      //        </div>
      $review->set("episodes_seen", (int)explode(" of ", $value->find(".spaceit .mb8 .lightLink", 0)->innertext)[0]);

      // Helpful Count
      // <strong>
      //          <span id="rhelp183786">372</span>
      //        </strong>
      $review->set("helpful_count", (int)$value->find(".spaceit table td", 1)->find(".lightLink strong span", 0)->innertext);

      // Scores
      $review_scores = $value->find(".textReadability div table", 0);
      foreach($review_scores->find("tr") as $tr) {
        if(strpos($tr->find("td", 0)->innertext, "Overall") !== false) {
          // Overall Score
          // <td class="borderClass bgColor1"><strong>10</strong></td>
          $review->set("scores//overall", (int)$tr->find("td strong", 1)->innertext);
        } else if(strpos($tr->find("td", 0)->innertext, "Story") !== false) {
          // Story Score
          // <td class="borderClass">8</td>
          $review->set("scores//story", (int)$tr->find("td", 1)->innertext);
        } else if(strpos($tr->find("td", 0)->innertext, "Animation") !== false) {
          // Animation Score
          // <td class="borderClass">9</td>
          $review->set("scores//animation", (int)$tr->find("td", 1)->innertext);
        } else if(strpos($tr->find("td", 0)->innertext, "Sound") !== false) {
          // Sound Score
          // <td class="borderClass">0</td>
          $review->set("scores//sound", (int)$tr->find("td", 1)->innertext);
        } else if(strpos($tr->find("td", 0)->innertext, "Character") !== false) {
          // Character Score
          // <td class="borderClass">10</td>
          $review->set("scores//character", (int)$tr->find("td", 1)->innertext);
        } else if(strpos($tr->find("td", 0)->innertext, "Enjoyment") !== false) {
          // Enjoyment Score
          // <td class="borderClass" style="border-width: 0;">10</td>
          $review->set("scores//enjoyment", (int)$tr->find("td", 1)->innertext);
        }
      }
      
      // The Review itself
      // ...
      $review_text_1 = explode("\r\n", str_replace($value->find(".textReadability #score" . $review->get("id"), 0)->plaintext, "", $value->find(".textReadability", 0)->plaintext));
      $review_text = htmlspecialchars_decode(html_entity_decode(substr(preg_replace('!\s+!', " ", trim(join("<br>", $review_text_1))), 0, -18), 0, "UTF-8"));
      $review->set("review", (string)$review_text);

      // The author username
      // <a href="https://myanimelist.net/profile/chesudesu">chesudesu</a>
      $review->set("author//username", (string)trim($value->find(".spaceit table td", 1)->find("a", 0)->innertext));

      // The author MAL URL
      // <a href="https://myanimelist.net/profile/chesudesu">chesudesu</a>
      $review->set("author//mal_url", (string)trim($value->find(".spaceit table td", 1)->find("a", 0)->href));

      // The author image URL
      // <img src="https://myanimelist.cdn-dena.com/images/userimages/thumbs/3643923_thumb.webp" data-src="https://myanimelist.cdn-dena.com/images/userimages/thumbs/3643923_thumb.webp" border="0" class=" lazyloaded">
      $review->set("author//image_url", (string)str_replace("/thumbs", "", str_replace("_thumb.webp", ".jpg", $value->find(".spaceit table td img", 0)->{'data-src'})));
      
      // The timestamp
      $review->set("timestamp", (string)getAbsoluteTimeGMT($value->find(".spaceit .mb8 div", 0)->innertext . $value->find(".spaceit .mb8 div", 0)->title, "M j, Y g:i A|")->format("c"));

      array_push($reviews_arr, $review->asArray());

    }

    return $reviews_arr;
  }

};

?>
