<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/../../src/parsers/parser.anime.search.QUERY.php");

class AnimeSearchQUERYParserTest extends TestCase {

  public function testParse() {
    $content = file_get_contents(dirname(__FILE__) . "/../mal_pages/mal_page.animesearch.eva.html");

    $anime = AnimeSearchQUERYParser::parse($content);
    $this->assertInternalType('array', $anime);
    $this->assertEquals(32, $anime[0]["id"]);
    $this->assertEquals("Neon Genesis Evangelion: The End of Evangelion", $anime[0]["title"]);
    $this->assertEquals("https://myanimelist.net/anime/32/Neon_Genesis_Evangelion__The_End_of_Evangelion", $anime[0]["mal_url"]);
    $this->assertEquals("https://myanimelist.cdn-dena.com/images/anime/12/39305.jpg", $anime[0]["image_url"]);
    $this->assertEquals("Movie", $anime[0]["type"]);
    $this->assertEquals(1, $anime[0]["episodes"]);
    $this->assertEquals(8.46, $anime[0]["score"]);
    $this->assertEquals("1997-07-19", $anime[0]["air_dates"]["from"]);
    $this->assertEquals("1997-07-19", $anime[0]["air_dates"]["to"]);
    $this->assertEquals(263654, $anime[0]["members_inlist"]);
    $this->assertEquals("R+", $anime[0]["rating"]);
  }

}
?>