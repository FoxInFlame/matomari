<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/../../src/models/model.userAnime.php");

class UserAnimeTest extends TestCase {

  public function testID() {
    $anime = new UserAnime();
    $animeID = rand(1, 50000);
    $anime->set("id", $animeID);
    $this->assertEquals($animeID, $anime->get("id"));
  }

  public function testStatus() {
    $anime = new UserAnime();
    $userAnimeStatus = rand(1, 6);
    $anime->set("status", $userAnimeStatus);
    $this->assertEquals($userAnimeStatus, $anime->get("status"));
  }

  public function testEpisodes() {
    $anime = new UserAnime();
    $userAnimeEpisodes = rand();
    $anime->set("episodes", $userAnimeEpisodes);
    $this->assertEquals($userAnimeEpisodes, $anime->get("episodes"));
  }

  public function testScore() {
    $anime = new UserAnime();
    $userAnimeScore = rand(pow(10, 2), pow(10, 3) - 1) / 100;
    $anime->set("score", $userAnimeScore);
    $this->assertEquals($userAnimeScore, $anime->get("score"));
  }

  public function testWatch_Dates() {
    $anime = new UserAnime();
    $userAnimeWatch_Date_From = str_shuffle('2014-10-09');
    $userAnimeWatch_Date_To = str_shuffle('2015-03-26');
    $anime->set("watch_date_from", $userAnimeWatch_Date_From);
    $anime->set("watch_date_to", $userAnimeWatch_Date_To);
    $this->assertEquals($userAnimeWatch_Date_From, $anime->get("watch_date_from"));
    $this->assertEquals($userAnimeWatch_Date_To, $anime->get("watch_date_to"));
  }

  public function testTags() {
    $anime = new UserAnime();
    $userAnimeTags = array("Example", "Tags", "Goes", "Here", "Wee!");
    $anime->set("tags", $userAnimeTags);
    $this->assertEquals($userAnimeTags, $anime->get("tags"));
  }

  public function testPriority() {
    $anime = new UserAnime();
    $userAnimePriority = rand(0, 2);
    $anime->set("priority", $userAnimePriority);
    $this->assertEquals($userAnimePriority, $anime->get("priority"));
  }

  public function testStorage() {
    $anime = new UserAnime();
    $userAnimeStorage = rand(1, 10);
    $anime->set("storage", $userAnimeStorage);
    $this->assertEquals($userAnimeStorage, $anime->get("storage"));
  }

  public function testStorage_Amount() {
    $anime = new UserAnime();
    $userAnimeStorage_Amount = rand();
    $anime->set("storage_amount", $userAnimeStorage_Amount);
    $this->assertEquals($userAnimeStorage_Amount, $anime->get("storage_amount"));
  }

  public function testRewatching() {
    $anime = new UserAnime();
    $userRewatching = rand(0, 1) == 1;
    $anime->set("rewatching", $userRewatching);
    $this->assertEquals($userRewatching, $anime->get("rewatching"));
  }

  public function testRewatch_Times() {
    $anime = new UserAnime();
    $userRewatch_Times = rand();
    $anime->set("rewatch_times", $userRewatch_Times);
    $this->assertEquals($userRewatch_Times, $anime->get("rewatch_times"));
  }

  public function testRewatch_Value() {
    $anime = new UserAnime();
    $userRewatch_Value = rand();
    $anime->set("rewatch_value", $userRewatch_Value);
    $this->assertEquals($userRewatch_Value, $anime->get("rewatch_value"));
  }

  public function testComments() {
    $anime = new UserAnime();
    $userComments = str_shuffle("This is a random comment - OMG I cried so much I love YLIA so much but I like GuP as well oh my god Kimi no Na wa awesome.");
    $anime->set("comments", $userComments);
    $this->assertEquals($userComments, $anime->get("comments"));
  }

  public function testNonExistentSet() {
    $anime = new UserAnime();
    $animeTmp = str_shuffle("weeeeeeee");
    $this->expectException(ModelKeyDoesNotExist::class);
    $anime->set("nonexistentstuff", $animeTmp);
  }

  public function testNonExistentGet() {
    $anime = new UserAnime();    
    $this->expectException(ModelKeyDoesNotExist::class);
    $anime->get("nonexistentstuff");
  }

  public function testAsArray() {
    $anime = new UserAnime();
    $array = $anime->asArray();
    $this->assertEquals(null, $array["id"]);
  }
}
?>