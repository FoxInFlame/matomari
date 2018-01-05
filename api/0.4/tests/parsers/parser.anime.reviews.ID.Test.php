<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/../../src/parsers/parser.anime.reviews.ID.php");

class AnimeReviewsIDParserTest extends TestCase {

  public function testParse() {
    $content = file_get_contents(dirname(__FILE__) . "/../mal_pages/mal_page.anime.25835.reviews.html");

    $reviews = AnimeReviewsIDParser::parse($content);
    $this->assertInternalType("array", $reviews);
    $this->assertEquals(183786, $reviews[0]["id"]);
    $this->assertEquals("https://myanimelist.net/reviews.php?id=183786", $reviews[0]["mal_url"]);
    $this->assertEquals(25835, $reviews[0]["target"]["id"]);
    $this->assertEquals(24, $reviews[0]["episodes_seen"]);
    $this->assertEquals(373, $reviews[0]["helpful_count"]);
    $this->assertEquals(10, $reviews[0]["ratings"]["overall"]); // Skip other ratings with the assumption that if the first worked others would work
    $this->assertEquals("chesudesu", $reviews[0]["author"]["username"]);
  }

}
?>