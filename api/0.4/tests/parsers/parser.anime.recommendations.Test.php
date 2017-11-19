<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/../../src/parsers/parser.anime.recommendations.php");

class AnimeRecommendationsParserTest extends TestCase {

  public function testParse() {
    $content = file_get_contents(dirname(__FILE__) . "/../mal_pages/mal_page.animerecommendations.html");

    $recommendations = AnimeRecommendationsParser::parse($content);
    $this->assertInternalType('array', $recommendations);
    $this->assertEquals(1827, $recommendations[0]["from"]["id"]);
    $this->assertEquals("Seirei no Moribito", $recommendations[0]["from"]["title"]);
    $this->assertEquals("https://myanimelist.net/anime/1827/Seirei_no_Moribito", $recommendations[0]["from"]["mal_url"]);
    $this->assertEquals("https://myanimelist.cdn-dena.com/images/anime/4/50337.jpg", $recommendations[0]["from"]["image_url"]);
    $this->assertEquals(34599, $recommendations[0]["to"]["id"]);
    $this->assertEquals("Made in Abyss", $recommendations[0]["to"]["title"]);
    $this->assertEquals("https://myanimelist.net/anime/34599/Made_in_Abyss", $recommendations[0]["to"]["mal_url"]);
    $this->assertEquals("https://myanimelist.cdn-dena.com/images/anime/6/86733.jpg", $recommendations[0]["to"]["image_url"]);
  }

}
?>