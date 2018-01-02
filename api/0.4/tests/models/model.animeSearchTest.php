<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/../../src/models/model.animeSearch.php");

class AnimeSearchTest extends TestCase {

  public function testID() {
    $anime = new AnimeSearch();
    $animeID = rand(1, 50000);
    $anime->set("id", $animeID);
    $this->assertEquals($animeID, $anime->get("id"));
  }

  public function testTitle() {
    $anime = new AnimeSearch();
    $animeTitle = str_shuffle("FoxInFlame is an admirable person.");
    $anime->set("title", $animeTitle);
    $this->assertEquals($animeTitle, $anime->get("title"));
  }

  public function testMal_Url() {
    $anime = new AnimeSearch();
    $animeMal_Url = "https://myanimelist.net/anime/" . (string)rand(1, 50000);
    $anime->set("mal_url", $animeMal_Url);
    $this->assertEquals($animeMal_Url, $anime->get("mal_url"));
  }

  public function testImage_Url() {
    $anime = new AnimeSearch();
    $animeImage_Url = "https://myanimelist.cdn-dena.com/images/anime/6/" . (string)rand(1, 50000) . "/.jpg";
    $anime->set("image_url", $animeImage_Url);
    $this->assertEquals($animeImage_Url, $anime->get("image_url"));
  }

  public function testScore() {
    $anime = new AnimeSearch();
    $animeScore = rand(pow(10, 2), pow(10, 3) - 1) / 100;
    $anime->set("score", $animeScore);
    $this->assertEquals($animeScore, $anime->get("score"));
  }

  public function testType() {
    $anime = new AnimeSearch();
    $animeType_arr = array('TV', 'Movie', 'Music', 'Music');
    $animeType = $animeType_arr[array_rand($animeType_arr)];
    $anime->set("type", $animeType);
    $this->assertEquals($animeType, $anime->get("type"));
  }

  public function testEpisodes() {
    $anime = new AnimeSearch();
    $animeEpisodes = rand();
    $anime->set("episodes", $animeEpisodes);
    $this->assertEquals($animeEpisodes, $anime->get("episodes"));
  }

  public function testAir_Dates() {
    $anime = new AnimeSearch();
    $animeAir_Date_From = str_shuffle('2014-10-09');
    $animeAir_Date_To = str_shuffle('2015-03-26');
    $anime->set("air_dates//from", $animeAir_Date_From);
    $anime->set("air_dates//to", $animeAir_Date_To);
    $this->assertEquals($animeAir_Date_From, $anime->get("air_dates//from"));
    $this->assertEquals($animeAir_Date_To, $anime->get("air_dates//to"));
  }

  public function testRating() {
    $anime = new AnimeSearch();
    $animeRating = str_shuffle("PG-13 - Teens 13 or older");
    $anime->set("rating", $animeRating);
    $this->assertEquals($animeRating, $anime->get("rating"));
  }

  public function testMembers_InList() {
    $anime = new AnimeSearch();
    $animeMembers_InList = rand();
    $anime->set("members_inlist", $animeMembers_InList);
    $this->assertEquals($animeMembers_InList, $anime->get("members_inlist"));
  }

  public function testNonExistentSet() {
    $anime = new AnimeSearch();
    $animeTmp = str_shuffle("weeeeeeee");
    $this->expectException(ModelKeyDoesNotExist::class);
    $anime->set("nonexistentstuff", $animeTmp);
  }

  public function testNonExistentGet() {
    $anime = new AnimeSearch();    
    $this->expectException(ModelKeyDoesNotExist::class);
    $anime->get("nonexistentstuff");
  }
  
  public function testAsArray() {
    $anime = new AnimeSearch();
    $array = $anime->asArray();
    $this->assertEquals(null, $array["id"]);
  }
}
?>