<?php

require_once(dirname(__FILE__) . "/../SimpleHtmlDOM.php");
require_once(dirname(__FILE__) . "/../models/model.animeTop.php");

class AnimeTopParser {

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

    $top_ranking_table = $html->find(".top-ranking-table", 0);
    $ranking_items = $top_ranking_table->find("tr.ranking-list");
    $anime_arr = array();
    foreach($ranking_items as $item) {
      $anime = new AnimeTop();

      // The ID
      // <a class="hoverinfo_trigger fl-l fs14 fw-b" href="https://myanimelist.net/anime/32281/Kimi_no_Na_wa" id="#area32281" rel="#info32281" style="display: inline-block;">Kimi no Na wa.</a>
      $anime->set("id", (int)substr($item->find("td.title .hoverinfo_trigger", 0)->id, 5));

      // The Title
      // <a class="hoverinfo_trigger fl-l fs14 fw-b" href="https://myanimelist.net/anime/32281/Kimi_no_Na_wa" id="#area32281" rel="#info32281" style="display: inline-block;">Kimi no Na wa.</a>
      $anime->set("title", (string)$item->find("td.title .hoverinfo_trigger", 1)->innertext);

      // The MAL URL
      // <a class="hoverinfo_trigger fl-l fs14 fw-b" href="https://myanimelist.net/anime/32281/Kimi_no_Na_wa" id="#area32281" rel="#info32281" style="display: inline-block;">Kimi no Na wa.</a>
      $anime->set("mal_url", (string)$item->find("td.title .hoverinfo_trigger", 0)->href);

      // The Image URL
      // <img width="50" height="70" alt="Anime: Kimi no Na wa." class=" lazyloaded" border="0" data-src="https://myanimelist.net/cdn-dena.com/r/50x70/images/anime/5/87048.webp?s=14812900d1190a4ddd52031dd1e10be5" data-srcset="https://myanimelist.net.cdn-dena.com/r/50x70/images/anime/5/87048.webp?s=af84814ef9799d83545dd34b2eb9b0c4 2x" srcset="https://myanimelist.net.cdn-dena.com/r/50x70/images/anime/5/87048.webp?s=14812900d1190a4ddd52031dd1e10be5 1x, https://myanimelist.net/cdn-dena.com/r/100x140/images/anime/5/87048.webp?s=af84814ef9799d83545dd34b2eb9b0c4 2x" src="https://myanimelist.cdn-dena.com/r/50x70/images/anime/5/87048.webp?s=14812900d1190a4ddd52031dd1e10be5">
      if($item->find("td.title a img", 0)->{'data-srcset'}) {
        $anime->set("image_url", (string)str_replace("webp", "jpg", str_replace("/r/50x70", "", explode("?s=", $item->find("td.title a img", 0)->{'data-srcset'})[0])));
      } else {
        $anime->set("image_url", (string)str_replace("webp", "jpg", str_replace("/r/50x70", "", explode("?s=", $item->find("td.title a img", 0)->{'srcset'})[0])));
      }

      // The Score
      // <span class="text on">9.25</span>
      $anime->set("score", (float)$item->find("td.score span.text", 0)->innertext);

      // The Rank
      // <span class="lightLink top-anime-rank-text rank1">2</span>
      $anime->set("rank", (int)$item->find("td.rank span.top-anime-rank-text", 0)->innertext);

      $piecesofinformation = explode("<br>", $item->find("td.title .detail .information", 0)->innertext);
      foreach($piecesofinformation as $key => $piece) {
        if($key == 0) {
          // The Type and Episodes
          // TV (64 eps)
          $anime->set("type", (string)explode(" (", trim($piece))[0]);
          if(explode(" (", trim($piece))[1] !== '?') {
            $anime->set("episodes", (int)explode(" (", trim($piece))[1]);
          }
        } else if($key == 2) {
          if(strpos($piece, "members") !== false) {
            $anime->set("members_inlist", (int)str_replace(",", "", explode(" members", trim($piece))[0]));
          } else if(strpos($piece, "favorites") !== false) {
            $anime->set("members_favorited", (int)str_replace(",", "", explode(" favorites", trim($piece))[0]));
          }
        }
      }
      array_push($anime_arr, $anime->asArray());
    }

    return $anime_arr;
  }

};

?>