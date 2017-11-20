<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/../../src/models/model.animeTop.php");

class AnimeTopTest extends TestCase {

  public function testID() {
    $anime = new AnimeTop();
    $animeID = rand(1, 50000);
    $anime->set("id", $animeID);
    $this->assertEquals($animeID, $anime->get("id"));
  }

  public function testTitle() {
    $anime = new AnimeTop();
    $animeTitle = str_shuffle("FoxInFlame is an admirable person.");
    $anime->set("title", $animeTitle);
    $this->assertEquals($animeTitle, $anime->get("title"));
  }

  public function testMal_Url() {
    $anime = new AnimeTop();
    $animeMal_Url = "https://myanimelist.net/anime/" . (string)rand(1, 50000);
    $anime->set("mal_url", $animeMal_Url);
    $this->assertEquals($animeMal_Url, $anime->get("mal_url"));
  }

  public function testImage_Url() {
    $anime = new AnimeTop();
    $animeImage_Url = "https://myanimelist.cdn-dena.com/images/anime/6/" . (string)rand(1, 50000) . "/.jpg";
    $anime->set("image_url", $animeImage_Url);
    $this->assertEquals($animeImage_Url, $anime->get("image_url"));
  }

  public function testScore() {
    $anime = new AnimeTop();
    $animeScore = rand(pow(10, 2), pow(10, 3) - 1) / 100;
    $anime->set("score", $animeScore);
    $this->assertEquals($animeScore, $anime->get("score"));
  }

  public function testRank() {
    $anime = new AnimeTop();
    $animeRank = rand(1, 50000);
    $anime->set("rank", $animeRank);
    $this->assertEquals($animeRank, $anime->get("rank"));
  }

  public function testType() {
    $anime = new AnimeTop();
    $animeType = array_rand(array('TV', 'Wow it\'s an OVA', 'Music', 'MOVIE!'));
    $anime->set("type", $animeType);
    $this->assertEquals($animeType, $anime->get("type"));
  }

  public function testEpisodes() {
    $anime = new AnimeTop();
    $animeEpisodes = rand();
    $anime->set("episodes", $animeEpisodes);
    $this->assertEquals($animeEpisodes, $anime->get("episodes"));
  }

  public function testMembers_InList() {
    $anime = new AnimeTop();
    $animeMembers_InList = rand();
    $anime->set("members_inlist", $animeMembers_InList);
    $this->assertEquals($animeMembers_InList, $anime->get("members_inlist"));
  }

  public function testMembers_Favorited() {
    $anime = new AnimeTop();
    $animeMembers_Favorited = rand();
    $anime->set("members_favorited", $animeMembers_Favorited);
    $this->assertEquals($animeMembers_Favorited, $anime->get("members_favorited"));
  }

  public function testNonExistentSet() {
    $anime = new AnimeTop();
    $animeTmp = str_shuffle("weeeeeeee");
    $this->expectException(ModelKeyDoesNotExist::class);
    $anime->set("nonexistentstuff", $animeTmp);
  }

  public function testNonExistentGet() {
    $anime = new AnimeTop();    
    $this->expectException(ModelKeyDoesNotExist::class);
    $anime->get("nonexistentstuff");
  }

  public function testAsArray() {
    $anime = new AnimeTop();
    $array = $anime->asArray();
    $this->assertEquals(null, $array["id"]);
  }
}
?>