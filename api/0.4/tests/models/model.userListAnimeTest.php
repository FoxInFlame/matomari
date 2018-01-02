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

  public function testTitle() {
    $anime = new UserListAnime();
    $animeTitle = str_shuffle("FoxInFlame is an admirable person.");
    $anime->set("title", $animeTitle);
    $this->assertEquals($animeTitle, $anime->get("title"));
  }

  public function testMal_Url() {
    $anime = new UserListAnime();
    $animeMal_Url = "https://myanimelist.net/anime/" . (string)rand(1, 50000);
    $anime->set("mal_url", $animeMal_Url);
    $this->assertEquals($animeMal_Url, $anime->get("mal_url"));
  }

  public function testImage_Url() {
    $anime = new UserListAnime();
    $animeImage_Url = "https://myanimelist.cdn-dena.com/images/anime/6/" . (string)rand(1, 50000) . "/.jpg";
    $anime->set("image_url", $animeImage_Url);
    $this->assertEquals($animeImage_Url, $anime->get("image_url"));
  }

  public function testOther_Titles() {
    $anime = new UserListAnime();
    $animeOther_Titles = array(
      "synonyms" => ['Hello', 'There', 'Wazup', 'Take care.']
    );
    $anime->set("other_titles", $animeOther_Titles);
    $this->assertEquals($animeOther_Titles, $anime->get("other_titles"));
  }

  public function testType() {
    $animeType_arr = ['TV', 'Movie', 'Music', 'OVA', 'ONA'];
    foreach($animeType_arr as $animeType) {
      $anime = new UserListAnime();
      $anime->set("type", $animeType);
      $this->assertEquals($animeType, $anime->get("type"));
    }
  }

  public function testEpisodes() {
    $anime = new UserListAnime();
    $animeEpisodes = rand();
    $anime->set("episodes", $animeEpisodes);
    $this->assertEquals($animeEpisodes, $anime->get("episodes"));
  }

  public function testAir_Status() {
    $animeAir_Status_arr = ['currently airing', 'finished airing', 'not yet aired'];
    foreach($animeAir_Status_arr as $animeAir_Status) {
      $anime = new UserListAnime();
      $anime->set("air_status", $animeAir_Status);
      $this->assertEquals(str_replace(" ", "_", $animeAir_Status), $anime->get("air_status"));
    }
  }

  public function testAir_Dates() {
    $anime = new UserListAnime();
    $animeAir_Date_From = str_shuffle('2014-10-09');
    $animeAir_Date_To = str_shuffle('2015-03-26');
    $anime->set("air_dates//from", $animeAir_Date_From);
    $anime->set("air_dates//to", $animeAir_Date_To);
    $this->assertEquals($animeAir_Date_From, $anime->get("air_dates//from"));
    $this->assertEquals($animeAir_Date_To, $anime->get("air_dates//to"));
  }

  public function testRating() {
    $anime = new UserListAnime();
    $animeRating = str_shuffle("PG-13 - Teens 13 or older");
    $anime->set("rating", $animeRating);
    $this->assertEquals($animeRating, $anime->get("rating"));
  }

  public function testWatch_Status() {
    $animeWatch_Status_arr = ['watching', 'completed', 'on_hold', 'dropped', 'plan_to_watch'];
    foreach($animeWatch_Status_arr as $animeWatch_Status) {
      $anime = new UserListAnime();
      $anime->set("watch_status", $animeWatch_Status);
      $this->assertEquals($animeWatch_Status, $anime->get("watch_status"));
    }
  }

  public function testWatched_Episodes() {
    $anime = new UserListAnime();
    $userListAnimeEpisodes = rand();
    $anime->set("watched_episodes", $userListAnimeEpisodes);
    $this->assertEquals($userListAnimeEpisodes, $anime->get("watched_episodes"));
  }

  public function testWatch_Score() {
    $anime = new UserListAnime();
    $userListAnimeScore = rand(pow(10, 2), pow(10, 3) - 1) / 100;
    $anime->set("watch_score", $userListAnimeScore);
    $this->assertEquals($userListAnimeScore, $anime->get("watch_score"));
  }

  public function testWatch_Dates() {
    $anime = new UserListAnime();
    $userListAnimeWatch_Date_From = str_shuffle('2014-10-09');
    $userListAnimeWatch_Date_To = str_shuffle('2015-03-26');
    $anime->set("watch_dates//from", $userListAnimeWatch_Date_From);
    $anime->set("watch_dates//to", $userListAnimeWatch_Date_To);
    $this->assertEquals($userListAnimeWatch_Date_From, $anime->get("watch_dates//from"));
    $this->assertEquals($userListAnimeWatch_Date_To, $anime->get("watch_dates//to"));
  }

  public function testTags() {
    $anime = new UserListAnime();
    $userListAnimeTags = array("Example", "Tags", "Goes", "Here", "Wee!");
    $anime->set("tags", $userListAnimeTags);
    $this->assertEquals($userListAnimeTags, $anime->get("tags"));
  }

  public function testPriority() {
    $userListAnimePriority = ["high", "medium", "low"];
    foreach($userListAnimePriority as $priority) {
      $anime = new UserListAnime();
      $anime->set("priority", $priority);
      $this->assertEquals($priority, $anime->get("priority"));
    }
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
    $anime = new UserListAnime();
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

  public function testLast_Updated() {
    $anime = new UserListAnime();
    $animeLast_Updated = rand();
    $anime->set("last_updated", $animeLast_Updated);
    $this->assertEquals($animeLast_Updated, $anime->get("last_updated"));
  }

  public function testDays_Spent_Watching() {
    $anime = new UserListAnime();
    $animeDays_Spent_Watching = rand();
    $anime->set("days_spent_watching", $animeDays_Spent_Watching);
    $this->assertEquals($animeDays_Spent_Watching, $anime->get("days_spent_watching"));
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