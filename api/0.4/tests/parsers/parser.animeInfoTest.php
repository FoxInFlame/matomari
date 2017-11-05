<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/../../src/parsers/parser.animeInfo.php");

class AnimeInfoParserTest extends TestCase {

  public function testParse_21() {
    $content = file_get_contents(dirname(__FILE__) . "/../mal_pages/mal_page.anime.21.html");

    $anime = AnimeInfoParser::parse($content);
    $this->assertInternalType('array', $anime);
    $this->assertEquals(21, $anime["id"]);
    $this->assertEquals("One Piece", $anime["title"]);
    $this->assertEquals("https://myanimelist.net/anime/21/One_Piece", $anime["mal_url"]);
    $this->assertEquals("https://myanimelist.cdn-dena.com/images/anime/6/73245.jpg", $anime["image_url"]);
    $this->assertEquals(8.55, $anime["score"]);
    $this->assertEquals(86, $anime["rank"]);
    $this->assertEquals(32, $anime["popularity"]);
    $this->assertContains("Gol D. Roger was known as the \"Pirate King,\" the strongest and most infamous being to have sailed the Grand Line.", $anime["synopsis"]);
    $this->assertEquals("One Piece", $anime["other_titles"]["english"][0]);
    $this->assertEquals("ONE PIECE", $anime["other_titles"]["japanese"][0]);
    $this->assertEquals("TV", $anime["type"]);
    $this->assertNull($anime["episodes"]);
    $this->assertEquals("Currently Airing", $anime["air_status"]);
    $this->assertEquals("1999-10-20T08:00:00+00:00", $anime["air_dates"]["from"]);
    $this->assertNull($anime["air_dates"]["to"]);
    $this->assertNull($anime["premier_date"]);
    $this->assertEquals("Fall 1999", $anime["season"]);
    $this->assertEquals("Sundays at 09:30 (JST)", $anime["air_time"]);
    $this->assertEquals(array("Fuji TV", "TAP", "Shueisha"), $anime["producers"]);
    $this->assertEquals(array("Funimation", "4Kids Entertainment"), $anime["licensors"]);
    $this->assertEquals(array("Toei Animation"), $anime["studios"]);
    $this->assertEquals("Manga", $anime["source"]);
    $this->assertEquals(array("Action", "Adventure", "Comedy", "Super Power", "Drama", "Fantasy", "Shounen"), $anime["genres"]);
    $this->assertNull($anime["duration"]["total"]);
    $this->assertEquals(24, $anime["duration"]["per_episode"]);
    $this->assertEquals("PG-13 - Teens 13 or older", $anime["rating"]);
    $this->assertEquals(378030, $anime["members_scored"]);
    $this->assertEquals(632534, $anime["members_inlist"]);
    $this->assertEquals(63822, $anime["members_favorited"]);
    $this->assertEquals("Several anime-original arcs have been adapted into light novels, and the series has inspired 40 video games as of 2016.", $anime["background"]);
  }

  public function testParse_25835() {
    $content = file_get_contents(dirname(__FILE__) . "/../mal_pages/mal_page.anime.25835.html");

    $anime = AnimeInfoParser::parse($content);
    $this->assertInternalType('array', $anime);
    $this->assertEquals(25835, $anime["id"]);
    $this->assertEquals("Shirobako", $anime["title"]);
    $this->assertEquals("2014-10-09T08:00:00+00:00", $anime["air_dates"]["from"]);
    $this->assertEquals("2015-03-26T08:00:00+00:00", $anime["air_dates"]["to"]);
    $this->assertEquals(576, $anime["duration"]["total"]);
    $this->assertEquals(24, $anime["duration"]["per_episode"]);
  }

  public function testParse_29949() {
    $content = file_get_contents(dirname(__FILE__) . "/../mal_pages/mal_page.anime.29949.html");

    $anime = AnimeInfoParser::parse($content);
    $this->assertInternalType('array', $anime);
    $this->assertEquals(29949, $anime["id"]);
    $this->assertEquals("Nami", $anime["title"]);
    $this->assertEmpty($anime["producers"]);
    $this->assertEmpty($anime["licensors"]);
    $this->assertEmpty($anime["studios"]);
    $this->assertEquals(3, $anime["duration"]["total"]);
    $this->assertEquals(3, $anime["duration"]["per_episode"]);
  }

  public function testParse_36474() {
    $content = file_get_contents(dirname(__FILE__) . "/../mal_pages/mal_page.anime.36474.html");

    $anime = AnimeInfoParser::parse($content);
    $this->assertInternalType('array', $anime);
    $this->assertEquals(36474, $anime["id"]);
    $this->assertNull($anime["score"]);
    $this->assertEquals("ソードアート・オンライン アリシゼーション", $anime["other_titles"]["japanese"][0]);
    $this->assertNull($anime["air_dates"]["from"]);
    $this->assertNull($anime["air_dates"]["to"]);
    $this->assertNull($anime["premier_date"]);
    $this->assertEmpty($anime["studios"]);
  }
}
?>