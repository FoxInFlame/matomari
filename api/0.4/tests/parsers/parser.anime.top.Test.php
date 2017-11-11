<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/../../src/parsers/parser.animeTop.php");

class AnimeTopParserTest extends TestCase {

  public function testParse() {
    $content = file_get_contents(dirname(__FILE__) . "/../mal_pages/mal_page.topanime.html");

    $anime = AnimeTopParser::parse($content);
    $this->assertInternalType('array', $anime);
    $this->assertEquals(5114, $anime[0]["id"]);
    $this->assertEquals("Fullmetal Alchemist: Brotherhood", $anime[0]["title"]);
    $this->assertEquals("https://myanimelist.net/anime/5114/Fullmetal_Alchemist__Brotherhood", $anime[0]["mal_url"]);
    $this->assertEquals("https://myanimelist.cdn-dena.com/images/anime/5/47421.jpg", $anime[0]["image_url"]);
    $this->assertEquals(9.25, $anime[0]["score"]);
    $this->assertEquals(1, $anime[0]["rank"]);
    $this->assertEquals("TV", $anime[0]["type"]);
    $this->assertEquals(64, $anime[0]["episodes"]);
    $this->assertEquals(1031153, $anime[0]["members_inlist"]);
    $this->assertNull($anime[0]["members_favorited"]);
  }

}
?>