<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/../../src/models/model.meListAnime.php");

class MeListAnimeTest extends TestCase {

  public function testID() {
    $anime = new MeListAnime();
    $animeID = rand(1, 50000);
    $anime->set("id", $animeID);
    $this->assertEquals($animeID, $anime->get("id"));
  }

  public function testWatch_Status() {
    $userListAnimeStatus = ["watching", "completed", "on_hold", "dropped", "plan_to_watch"];
    foreach($userListAnimeStatus as $status) {
      $anime = new MeListAnime();
      $anime->set("watch_status", $status);
      $this->assertEquals($status, $anime->get("watch_status"));
    }
  }

  public function testInvalidStatus() {
    $userListAnimeStatus = "invalid";
    $anime = new MeListAnime();
    $this->expectException(ModelValueNotValid::class);
    $anime->set("watch_status", $userListAnimeStatus);
  }

  public function testWatched_Episodes() {
    $anime = new MeListAnime();
    $userListAnimeEpisodes = rand();
    $anime->set("watched_episodes", $userListAnimeEpisodes);
    $this->assertEquals($userListAnimeEpisodes, $anime->get("watched_episodes"));
  }

  public function testWatch_Score() {
    $anime = new MeListAnime();
    $userListAnimeScore = rand(pow(10, 2), pow(10, 3) - 1) / 100;
    $anime->set("watch_score", $userListAnimeScore);
    $this->assertEquals($userListAnimeScore, $anime->get("watch_score"));
  }

  public function testWatch_Dates() {
    $anime = new MeListAnime();
    $userListAnimeWatch_Date_From = str_shuffle('2014-10-09');
    $userListAnimeWatch_Date_To = str_shuffle('2015-03-26');
    $anime->set("watch_dates//from", $userListAnimeWatch_Date_From);
    $anime->set("watch_dates//to", $userListAnimeWatch_Date_To);
    $this->assertEquals($userListAnimeWatch_Date_From, $anime->get("watch_dates//from"));
    $this->assertEquals($userListAnimeWatch_Date_To, $anime->get("watch_dates//to"));
  }

  public function testTags() {
    $anime = new MeListAnime();
    $userListAnimeTags = array("Example", "Tags", "Goes", "Here", "Wee!");
    $anime->set("tags", $userListAnimeTags);
    $this->assertEquals($userListAnimeTags, $anime->get("tags"));
  }

  public function testPriority() {
    $userListAnimePriority = ["high", "medium", "low"];
    foreach($userListAnimePriority as $priority) {
      $anime = new MeListAnime();
      $anime->set("priority", $priority);
      $this->assertEquals($priority, $anime->get("priority"));
    }
  }

  public function testInvalidPriority() {
    $userListAnimePriority = "invalid";
    $anime = new MeListAnime();
    $this->expectException(ModelValueNotValid::class);
    $anime->set("priority", $userListAnimePriority);
  }

  public function testStorage() {
    $userListAnimeStorage = [0, 1, 2, 3, 4, 5, 6, 7, 8];
    foreach($userListAnimeStorage as $storage) {
      $anime = new MeListAnime();
      $anime->set("storage", $storage);
      $this->assertEquals($storage, $anime->get("storage"));
    }
  }

  public function testInvalidStorage() {
    $userListAnimeStorage = "invalid";
    $anime = new MeListAnime();
    $this->expectException(ModelValueNotValid::class);
    $anime->set("storage", $userListAnimeStorage);
  }

  public function testStorage_Amount() {
    $anime = new MeListAnime();
    $userListAnimeStorage_Amount = rand();
    $anime->set("storage_amount", $userListAnimeStorage_Amount);
    $this->assertEquals($userListAnimeStorage_Amount, $anime->get("storage_amount"));
  }

  public function testRewatching() {
    $anime = new MeListAnime();
    $userListRewatching = rand(0, 1) == 1;
    $anime->set("rewatching", $userListRewatching);
    $this->assertEquals($userListRewatching, $anime->get("rewatching"));
  }

  public function testRewatch_Times() {
    $anime = new MeListAnime();
    $userListRewatch_Times = rand();
    $anime->set("rewatch_times", $userListRewatch_Times);
    $this->assertEquals($userListRewatch_Times, $anime->get("rewatch_times"));
  }

  public function testRewatch_Value() {
    $userListRewatch_Value = [0, 1, 2, 3, 4, 5];
    foreach($userListRewatch_Value as $rewatch_value) {
      $anime = new MeListAnime();
      $anime->set("rewatch_value", $rewatch_value);
      if($rewatch_value === 0) {
        $this->assertNull($anime->get("rewatch_value"));
      } else {
        $this->assertEquals($rewatch_value, $anime->get("rewatch_value"));
      }
    }
  }

  public function testInvalidRewatch_Value() {
    $userListRewatch_Value = "invalid";
    $anime = new MeListAnime();
    $this->expectException(ModelValueNotValid::class);
    $anime->set("rewatch_value", $userListRewatch_Value);
  }

  public function testComments() {
    $anime = new MeListAnime();
    $userListComments = str_shuffle("This is a random comment - OMG I cried so much I love YLIA so much but I like GuP as well oh my god Kimi no Na wa awesome.");
    $anime->set("comments", $userListComments);
    $this->assertEquals($userListComments, $anime->get("comments"));
  }

  public function testNonExistentSet() {
    $anime = new MeListAnime();
    $animeTmp = str_shuffle("weeeeeeee");
    $this->expectException(ModelKeyDoesNotExist::class);
    $anime->set("nonexistentstuff", $animeTmp);
  }

  public function testNonExistentGet() {
    $anime = new MeListAnime();    
    $this->expectException(ModelKeyDoesNotExist::class);
    $anime->get("nonexistentstuff");
  }

  public function testAsArray() {
    $anime = new MeListAnime();
    $array = $anime->asArray();
    $this->assertEquals(null, $array["id"]);
  }
}
?>