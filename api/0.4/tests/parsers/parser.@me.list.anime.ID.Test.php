<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/../../src/parsers/parser.@me.list.anime.ID.php");

class MeListAnimeIDParserTest extends TestCase {

  public function testParse() {
    $content = file_get_contents(dirname(__FILE__) . "/../mal_pages/mal_page.editlist.anime.34240.html");

    $anime = MeListAnimeIDParser::parse($content);
    $this->assertInternalType("array", $anime);
    $this->assertEquals(34240, $anime["id"]);
    $this->assertEquals("completed", $anime["watch_status"]);
    $this->assertEquals(1, $anime["watched_episodes"]);
    $this->assertEquals(9, $anime["watch_score"]);
    $this->assertEquals("2016-10-29", $anime["watch_dates"]["from"]);
    $this->assertEquals("2016-10-29", $anime["watch_dates"]["from"]);
    $this->assertEquals("Music, Sci-Fi, Source:Music, Studio:A-1 Pictures, AWESOME, LOVE THE MUSIC, Collaboration of A1 and Crunchy, BEAUTIFUL, Duration:6min", $anime["tags"]);
    $this->assertEquals("high", $anime["priority"]);
    $this->assertEquals(6, $anime["storage"]);
    $this->assertEquals(16, $anime["storage_amount"]);
    $this->assertEquals(false, $anime["rewatching"]);
    $this->assertEquals(36, $anime["rewatch_times"]);
    $this->assertEquals(5, $anime["rewatch_value"]);
    $this->assertEquals("Music Video produced by A-1 Pictures in cooperation with Crunchyroll. Original Music by Porter Robinson & Madeon.", $anime["comments"]);
  }

}
?>