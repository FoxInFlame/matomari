<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/../../src/models/model.userListAnime.php");

class UserListAnimeTest extends TestCase {

  public function testID() {
    $anime = new UserListAnime();
    $animeID = rand(1, 50000);
    $anime->set("id", $animeID);
    $this->assertEquals($animeID, $anime->get("id"));
  }

  public function testStatus() {
    $userListAnimeStatus = [1, 2, 3, 4, 6];
    foreach($userListAnimeStatus as $status) {
      $anime = new UserListAnime();
      $anime->set("watch_status", $status);
      $this->assertEquals($status, $anime->get("watch_status"));
    }
  }

  public function testInvalidStatus () {
    $userListAnimeStatus = "invalid";
    $anime = new UserListAnime();
    $this->expectException(ModelValueNotValid::class);
    $anime->set("watch_status", $userListAnimeStatus);
  }

  public function testEpisodes() {
    $anime = new UserListAnime();
    $userListAnimeEpisodes = rand();
    $anime->set("watched_episodes", $userListAnimeEpisodes);
    $this->assertEquals($userListAnimeEpisodes, $anime->get("watched_episodes"));
  }

  public function testScore() {
    $anime = new UserListAnime();
    $userListAnimeScore = rand(pow(10, 2), pow(10, 3) - 1) / 100;
    $anime->set("score", $userListAnimeScore);
    $this->assertEquals($userListAnimeScore, $anime->get("score"));
  }

  public function testWatch_Dates() {
    $anime = new UserListAnime();
    $userListAnimeWatch_Date_From = str_shuffle('2014-10-09');
    $userListAnimeWatch_Date_To = str_shuffle('2015-03-26');
    $anime->set("watch_date_from", $userListAnimeWatch_Date_From);
    $anime->set("watch_date_to", $userListAnimeWatch_Date_To);
    $this->assertEquals($userListAnimeWatch_Date_From, $anime->get("watch_date_from"));
    $this->assertEquals($userListAnimeWatch_Date_To, $anime->get("watch_date_to"));
  }

  public function testTags() {
    $anime = new UserListAnime();
    $userListAnimeTags = array("Example", "Tags", "Goes", "Here", "Wee!");
    $anime->set("tags", $userListAnimeTags);
    $this->assertEquals($userListAnimeTags, $anime->get("tags"));
  }

  public function testPriority() {
    $userListAnimePriority = [0, 1, 2];
    foreach($userListAnimePriority as $priority) {
      $anime = new UserListAnime();
      $anime->set("priority", $priority);
      $this->assertEquals($priority, $anime->get("priority"));
    }
  }

  public function testInvalidPriority() {
    $userListAnimePriority = "invalid";
    $anime = new UserListAnime();
    $this->expectException(ModelValueNotValid::class);
    $anime->set("priority", $userListAnimePriority);
  }

  public function testStorage() {
    $userListAnimeStorage = [0, 1, 2, 3, 4, 5, 6, 7, 8];
    foreach($userListAnimeStorage as $storage) {
      $anime = new UserListAnime();
      $anime->set("storage", $storage);
      $this->assertEquals($storage, $anime->get("storage"));
    }
  }

  public function testInvalidStorage() {
    $userListAnimeStorage = "invalid";
    $anime = new userListAnime();
    $this->expectException(ModelValueNotValid::class);
    $anime->set("storage", $userListAnimeStorage);
  }

  public function testStorage_Amount() {
    $anime = new UserListAnime();
    $userListAnimeStorage_Amount = rand();
    $anime->set("storage_amount", $userListAnimeStorage_Amount);
    $this->assertEquals($userListAnimeStorage_Amount, $anime->get("storage_amount"));
  }

  public function testRewatching() {
    $anime = new UserListAnime();
    $userListRewatching = rand(0, 1) == 1;
    $anime->set("rewatching", $userListRewatching);
    $this->assertEquals($userListRewatching, $anime->get("rewatching"));
  }

  public function testRewatch_Times() {
    $anime = new UserListAnime();
    $userListRewatch_Times = rand();
    $anime->set("rewatch_times", $userListRewatch_Times);
    $this->assertEquals($userListRewatch_Times, $anime->get("rewatch_times"));
  }

  public function testRewatch_Value() {
    $userListRewatch_Value = [0, 1, 2, 3, 4, 5];
    foreach($userListRewatch_Value as $rewatch_value) {
      $anime = new UserListAnime();
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
    $anime = new UserListAnime();
    $this->expectException(ModelValueNotValid::class);
    $anime->set("rewatch_value", $userListRewatch_Value);
  }

  public function testComments() {
    $anime = new UserListAnime();
    $userListComments = str_shuffle("This is a random comment - OMG I cried so much I love YLIA so much but I like GuP as well oh my god Kimi no Na wa awesome.");
    $anime->set("comments", $userListComments);
    $this->assertEquals($userListComments, $anime->get("comments"));
  }

  public function testNonExistentSet() {
    $anime = new UserListAnime();
    $animeTmp = str_shuffle("weeeeeeee");
    $this->expectException(ModelKeyDoesNotExist::class);
    $anime->set("nonexistentstuff", $animeTmp);
  }

  public function testNonExistentGet() {
    $anime = new UserListAnime();    
    $this->expectException(ModelKeyDoesNotExist::class);
    $anime->get("nonexistentstuff");
  }

  public function testAsArray() {
    $anime = new UserListAnime();
    $array = $anime->asArray();
    $this->assertEquals(null, $array["id"]);
  }
}
?>