<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/../../src/models/model.recommendation.php");

class RecommendationTest extends TestCase {

  public function testFromID() {
    $recommendation = new Recommendation();
    $fromID = rand(1, 50000);
    $recommendation->set("from_id", $fromID);
    $this->assertEquals($fromID, $recommendation->get("from_id"));
  }

  public function testFromTitle() {
    $recommendation = new Recommendation();
    $fromTitle = str_shuffle("FoxInFlame is an admirable person.");
    $recommendation->set("from_title", $fromTitle);
    $this->assertEquals($fromTitle, $recommendation->get("from_title"));
  }

  public function testFromMal_Url() {
    $recommendation = new Recommendation();
    $fromMal_Url = "https://myanimelist.net/anime/" . (string)rand(1, 50000);
    $recommendation->set("from_mal_url", $fromMal_Url);
    $this->assertEquals($fromMal_Url, $recommendation->get("from_mal_url"));
  }

  public function testFromImage_Url() {
    $recommendation = new Recommendation();
    $fromImage_Url = "https://myanimelist.cdn-dena.com/images/anime/6/" . (string)rand(1, 50000) . "/.jpg";
    $recommendation->set("from_image_url", $fromImage_Url);
    $this->assertEquals($fromImage_Url, $recommendation->get("from_image_url"));
  }

  public function testToID() {
    $recommendation = new Recommendation();
    $toID = rand(1, 50000);
    $recommendation->set("to_id", $toID);
    $this->assertEquals($toID, $recommendation->get("to_id"));
  }

  public function testToTitle() {
    $recommendation = new Recommendation();
    $toTitle = str_shuffle("FoxInFlame is an admirable person.");
    $recommendation->set("to_title", $toTitle);
    $this->assertEquals($toTitle, $recommendation->get("to_title"));
  }

  public function testToMal_Url() {
    $recommendation = new Recommendation();
    $toMal_Url = "https://myanimelist.net/anime/" . (string)rand(1, 50000);
    $recommendation->set("to_mal_url", $toMal_Url);
    $this->assertEquals($toMal_Url, $recommendation->get("to_mal_url"));
  }

  public function testToImage_Url() {
    $recommendation = new Recommendation();
    $toImage_Url = "https://myanimelist.cdn-dena.com/images/anime/6/" . (string)rand(1, 50000) . "/.jpg";
    $recommendation->set("to_image_url", $toImage_Url);
    $this->assertEquals($toImage_Url, $recommendation->get("to_image_url"));
  }

  public function testReason() {
    $recommendation = new Recommendation();
    $reason = str_shuffle("An adventure? Yes. A journey? Indeed. Hard decisions? Yup. Monsters? Of course. Lovely characters? You bet. Tears? I'm sorry, most probably yes. A little laugh? Thankfully yes. In both cases, the MCs -along with people they care about- take a long, harsh journey in order to achieve a goal/meet their fate, something that initially appears to be impossible. Clear up your schedule, once you start following them, you'll be hooked for good.");
    $recommendation->set("reason", $reason);
    $this->assertEquals($reason, $recommendation->get("reason"));
  }

  public function testAuthor() {
    $recommendation = new Recommendation();
    $author = str_shuffle("FoxInFlame");
    $recommendation->set("author", $author);
    $this->assertEquals($author, $recommendation->get("author"));
  }

  public function testTimestamp() {
    $recommendation = new Recommendation();
    $timestamp = str_shuffle("2017-11-11T12:40:04+00:00");
    $recommendation->set("timestamp", $timestamp);
    $this->assertEquals($timestamp, $recommendation->get("timestamp"));
  }

  public function testNonExistentSet() {
    $anime = new Recommendation();
    $animeTmp = str_shuffle("weeeeeeee");
    $this->expectException(ModelKeyDoesNotExist::class);
    $anime->set("nonexistentstuff", $animeTmp);
  }

  public function testNonExistentGet() {
    $anime = new Recommendation();    
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