<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/../../src/models/model.animeReview.php");

class AnimeReviewTest extends TestCase {

  public function testID() {
    $review = new AnimeReview();
    $reviewID = rand(1, 50000);
    $review->set("id", $reviewID);
    $this->assertEquals($reviewID, $review->get("id"));
  }

  public function testMal_Url() {
    $review = new AnimeReview();
    $reviewMal_Url = str_shuffle("https://myanimelist.net/reviews.php?id=183786");
    $review->set("mal_url", $reviewMal_Url);
    $this->assertEquals($reviewMal_Url, $review->get("mal_url"));
  }

  public function testTargetID() {
    $review = new AnimeReview();
    $reviewTargetID = rand(1, 100000);
    $review->set("target//id", $reviewTargetID);
    $this->assertEquals($reviewTargetID, $review->get("target//id"));
  }

  public function testTargetTitle() {
    $review = new AnimeReview();
    $reviewTargetTitle = str_shuffle("Shirobako");
    $review->set("target//title", $reviewTargetTitle);
    $this->assertEquals($reviewTargetTitle, $review->get("target//title"));
  }

  public function testEpisodes_Seen() {
    $review = new AnimeReview();
    $reviewEpisodes_Seen = rand(1, 48);
    $review->set("episodes_seen", $reviewEpisodes_Seen);
    $this->assertEquals($reviewEpisodes_Seen, $review->get("episodes_seen"));
  }

  public function testHelpful_Count() {
    $review = new AnimeReview();
    $reviewHelpful_Count = rand(1, 48);
    $review->set("helpful_count", $reviewHelpful_Count);
    $this->assertEquals($reviewHelpful_Count, $review->get("helpful_count"));
  }

  public function testScores() {
    $review = new AnimeReview();
    $reviewScoreOverall = rand(0, 10);
    $review->set("scores//overall", $reviewScoreOverall);
    $this->assertEquals($reviewScoreOverall, $review->get("scores//overall"));
    $reviewScoreStory = rand(0, 10);
    $review->set("scores//story", $reviewScoreStory);
    $this->assertEquals($reviewScoreStory, $review->get("scores//story"));
    $reviewScoreAnimation = rand(0, 10);
    $review->set("scores//animation", $reviewScoreAnimation);
    $this->assertEquals($reviewScoreAnimation, $review->get("scores//animation"));
    $reviewScoreSound = rand(0, 10);
    $review->set("scores//sound", $reviewScoreSound);
    $this->assertEquals($reviewScoreSound, $review->get("scores//sound"));
    $reviewScoreCharacter = rand(0, 10);
    $review->set("scores//character", $reviewScoreCharacter);
    $this->assertEquals($reviewScoreCharacter, $review->get("scores//character"));
    $reviewScoreEnjoyment = rand(0, 10);
    $review->set("scores//enjoyment", $reviewScorenjoyment);
    $this->assertEquals($reviewScoreEnjoyment, $review->get("scores//enjoyment"));
  }

  public function testReview() {
    $review = new AnimeReview();
    $reviewReview = str_shuffle("I recommend you to observe FoxInFlame's weird behaviour everyday of trying to become a carrot by putting soil over themselves. Of course, this is only good for you if you enjoy watching yourself in a mirror.");
    $review->set("review", $reviewReview);
    $this->assertEquals($reviewReview, $review->get("review"));
  }

  public function testAuthorUsername() {
    $review = new AnimeReview();
    $reviewAuthorUsername = str_shuffle("FoxInFlameIsAwesome");
    $review->set("author//username", $reviewAuthorUsername);
    $this->assertEquals($reviewAuthorUsername, $review->get("author//username"));
  }
  
  public function testAuthorMal_Url() {
    $review = new AnimeReview();
    $reviewAuthorMal_Url = str_shuffle("https://myanimelist.net/profile/FoxInFlameIsAwesome");
    $review->set("author//mal_url", $reviewAuthorMal_Url);
    $this->assertEquals($reviewAuthorMal_Url, $review->get("author//mal_url"));
  }

  public function testAuthorImage_Url() {
    $review = new AnimeReview();
    $reviewAuthorImage_Url = str_shuffle("https://myanimelist.cdn-dena.com/images/userimages/5280115.jpg");
    $review->set("author//image_url", $reviewAuthorImage_Url);
    $this->assertEquals($reviewAuthorImage_Url, $review->get("author//image_url"));
  }

  public function testTimestamp() {
    $review = new AnimeReview();
    $reviewTimestamp = str_shuffle("2018-01-03T02:05:32+00:00");
    $review->set("timestamp", $reviewTimestamp);
    $this->assertEquals($reviewTimestamp, $review->get("timestamp"));
  }

  public function testNonExistentSet() {
    $anime = new AnimeReview();
    $animeTmp = str_shuffle("weeeeeeee");
    $this->expectException(ModelKeyDoesNotExist::class);
    $anime->set("nonexistentstuff", $animeTmp);
  }

  public function testNonExistentGet() {
    $anime = new AnimeReview();    
    $this->expectException(ModelKeyDoesNotExist::class);
    $anime->get("nonexistentstuff");
  }

  public function testAsArray() {
    $anime = new AnimeReview();
    $array = $anime->asArray();
    $this->assertEquals(null, $array["id"]);
  }
}
?>