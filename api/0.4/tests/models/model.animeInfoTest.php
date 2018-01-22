<?php

require './vendor/autoload.php';

require_once(dirname(__FILE__) . "/../../src/models/model.animeInfo.php");

class AnimeInfoTest extends PHPUnit_Framework_TestCase  {

  public function testID() {
    $anime = new AnimeInfo();
    $animeID = rand(1, 50000);
    $anime->set("id", $animeID);
    $this->assertEquals($animeID, $anime->get("id"));
  }

  public function testTitle() {
    $anime = new AnimeInfo();
    $animeTitle = str_shuffle("FoxInFlame is an admirable person.");
    $anime->set("title", $animeTitle);
    $this->assertEquals($animeTitle, $anime->get("title"));
  }

  public function testMal_Url() {
    $anime = new AnimeInfo();
    $animeMal_Url = "https://myanimelist.net/anime/" . (string)rand(1, 50000);
    $anime->set("mal_url", $animeMal_Url);
    $this->assertEquals($animeMal_Url, $anime->get("mal_url"));
  }

  public function testImage_Url() {
    $anime = new AnimeInfo();
    $animeImage_Url = "https://myanimelist.cdn-dena.com/images/anime/6/" . (string)rand(1, 50000) . "/.jpg";
    $anime->set("image_url", $animeImage_Url);
    $this->assertEquals($animeImage_Url, $anime->get("image_url"));
  }

  public function testScore() {
    $anime = new AnimeInfo();
    $animeScore = rand(pow(10, 2), pow(10, 3) - 1) / 100;
    $anime->set("score", $animeScore);
    $this->assertEquals($animeScore, $anime->get("score"));
  }

  public function testRank() {
    $anime = new AnimeInfo();
    $animeRank = rand(1, 50000);
    $anime->set("rank", $animeRank);
    $this->assertEquals($animeRank, $anime->get("rank"));
  }

  public function testPopularity() {
    $anime = new AnimeInfo();
    $animePopularity = rand(1, 50000);
    $anime->set("popularity", $animePopularity);
    $this->assertEquals($animePopularity, $anime->get("popularity"));
  }

  public function testSynopsis() {
    $anime = new AnimeInfo();
    $animeSynopsis = str_shuffle("<i>Shirobako</i> begins with the five members of the Kaminoyama High School animation club all making a pledge to work hard on their very first amateur production and make it into a success. After showing it to an audience at a culture festival, that pledge turned into a huge dream&mdash;to move to Tokyo, get jobs in the anime industry and one day join hands to create something amazing.<br />
<br />
Fast forward two and a half years and two of those members, Aoi Miyamori and Ema Yasuhara, have made their dreams into reality by landing jobs at a famous production company called Musashino Animation. Everything seems perfect at first. However, as the girls slowly discover, the animation industry is a bit tougher than they had imagined. Who said making your dream come true was easy?");
    $anime->set("synopsis", $animeSynopsis);
    $this->assertEquals($animeSynopsis, $anime->get("synopsis"));
  }

  public function testOther_Titles() {
    $anime = new AnimeInfo();
    $animeOther_Titles = array(
      "english" => array('I', 'Love', 'Icecream.'),
      "japanese" => array('Which', 'Flavour?', ' you', 'may', 'ask.'),
      "swedish" => array('The', 'Strange', 'Thing', 'Is'),
      "synonyms" => array('That', 'I', 'Don\'t', 'Care.')
    );
    $anime->set("other_titles", $animeOther_Titles);
    $this->assertEquals($animeOther_Titles, $anime->get("other_titles"));
  }

  public function testType() {
    $anime = new AnimeInfo();
    $animeType_arr = array('TV', 'Movie', 'Music', 'Music');
    $animeType = $animeType_arr[array_rand($animeType_arr)];
    $anime->set("type", $animeType);
    $this->assertEquals($animeType, $anime->get("type"));
  }

  public function testEpisodes() {
    $anime = new AnimeInfo();
    $animeEpisodes = rand();
    $anime->set("episodes", $animeEpisodes);
    $this->assertEquals($animeEpisodes, $anime->get("episodes"));
  }

  public function testAir_Status() {
    $anime = new AnimeInfo();
    $animeAir_Status_arr = ['currently airing', 'finished airing', 'not yet aired'];
    foreach($animeAir_Status_arr as $animeAir_Status) {
      $anime->set("air_status", $animeAir_Status);
      $this->assertEquals(str_replace(" ", "_", $animeAir_Status), $anime->get("air_status"));
    }
  }

  public function testAir_Dates() {
    $anime = new AnimeInfo();
    $animeAir_Date_From = str_shuffle('2014-10-09');
    $animeAir_Date_To = str_shuffle('2015-03-26');
    $anime->set("air_dates//from", $animeAir_Date_From);
    $anime->set("air_dates//to", $animeAir_Date_To);
    $this->assertEquals($animeAir_Date_From, $anime->get("air_dates//from"));
    $this->assertEquals($animeAir_Date_To, $anime->get("air_dates//to"));
  }
  
  public function testSeason() {
    $anime = new AnimeInfo();
    $animeSeason_arr = array('Fall ', 'Summer ', 'Winter ', 'Fox ');
    $animeSeason = $animeSeason_arr[array_rand($animeSeason_arr)] . (string)rand(1, 3000);
    $anime->set("season", $animeSeason);
    $this->assertEquals($animeSeason, $anime->get("season"));
  }

  public function testAir_Time() {
    $anime = new AnimeInfo();
    $animeAir_Time = str_shuffle("Thursdays at 23:30 (JST)");
    $anime->set("air_time", $animeAir_Time);
    $this->assertEquals($animeAir_Time, $anime->get("air_time"));
  }

  public function testPremier_Date() {
    $anime = new AnimeInfo();
    $animePremier_Date = str_shuffle('2014-10-09T08:00:00+00:00');
    $anime->set("premier_date", $animePremier_Date);
    $this->assertEquals($animePremier_Date, $anime->get("premier_date"));
  }

  public function testProducers() {
    $anime = new AnimeInfo();
    $animeProducers = array("Sotsu", "Movic", "Warner Bros.", "KlockWorx", "Showgate", "Infinite");
    $anime->set("producers", $animeProducers);
    $this->assertEquals($animeProducers, $anime->get("producers"));
  }

  public function testLicensors() {
    $anime = new AnimeInfo();
    $animeLicensors = array("Sentai Filmworks");
    $anime->set("licensors", $animeLicensors);
    $this->assertEquals($animeLicensors, $anime->get("licensors"));
  }

  public function testStudios() {
    $anime = new AnimeInfo();
    $animeStudios = array("P.A. Works", "Kyoto Animation");
    $anime->set("studios", $animeStudios);
    $this->assertEquals($animeStudios, $anime->get("studios"));
  }

  public function testSource() {
    $anime = new AnimeInfo();
    $animeSource = str_shuffle('Original');
    $anime->set("source", $animeSource);
    $this->assertEquals($animeSource, $anime->get("source"));
  }

  public function testGenres() {
    $anime = new AnimeInfo();
    $animeGenres = array(str_shuffle('Comedy'), str_shuffle('Drama'));
    $anime->set("genres", $animeGenres);
    $this->assertEquals($animeGenres, $anime->get("genres"));
  }

  public function testDuration() {
    $anime = new AnimeInfo();
    $animeDuration_Total = rand(1, 500);
    $animeDuration_PerEpisode = rand(1, 60);
    $anime->set("duration//total", $animeDuration_Total);
    $anime->set("duration//per_episode", $animeDuration_PerEpisode);
    $this->assertEquals($animeDuration_Total, $anime->get("duration//total"));
    $this->assertEquals($animeDuration_PerEpisode, $anime->get("duration//per_episode"));
  }

  public function testRating() {
    $anime = new AnimeInfo();
    $animeRating = str_shuffle("PG-13 - Teens 13 or older");
    $anime->set("rating", $animeRating);
    $this->assertEquals($animeRating, $anime->get("rating"));
  }

  public function testMembers_Scored() {
    $anime = new AnimeInfo();
    $animeMembers_Scored = rand();
    $anime->set("members_scored", $animeMembers_Scored);
    $this->assertEquals($animeMembers_Scored, $anime->get("members_scored"));
  }

  public function testMembers_InList() {
    $anime = new AnimeInfo();
    $animeMembers_InList = rand();
    $anime->set("members_inlist", $animeMembers_InList);
    $this->assertEquals($animeMembers_InList, $anime->get("members_inlist"));
  }

  public function testMembers_Favorited() {
    $anime = new AnimeInfo();
    $animeMembers_Favorited = rand();
    $anime->set("members_favorited", $animeMembers_Favorited);
    $this->assertEquals($animeMembers_Favorited, $anime->get("members_favorited"));
  }

  public function testBackground() {
    $anime = new AnimeInfo();
    $animeBackground = str_shuffle("<i>Shirobako</i> won the Animation Kobe Television Award in 2015.");
    $anime->set("background", $animeBackground);
    $this->assertEquals($animeBackground, $anime->get("background"));
  }

  public function testRelated() {
    $anime = new AnimeInfo();
    $animeRelated = array(
      "adaptation" => array(
        array(
          "type" => "manga",
          "title" => str_shuffle("Shirobako: Kaminoyama Koukou Animation Doukoukai"),
          "id" => rand()
        ),
        array(
          "type" => "manga",
          "title" => str_shuffle("Shirobako: Introduction"),
          "id" => rand()
        )
      ),
      "side_story" => array(
        array(
          "type" => "anime",
          "title" => str_shuffle("Shirobako Specials"),
          "id" => rand()
        )
      )
    );
    $anime->set("related", $animeRelated);
    $this->assertEquals($animeRelated, $anime->get("related"));
  }

  public function testTheme_Songs() {
    $anime = new AnimeInfo();
    $animeTheme_Songs = array(
      "opening" => array(
        str_shuffle("I'm Sorry EXODUS by Tracy (Mai Nakahara, Shizuka Itou, Ai Kayano) (ep 1)"),
        str_shuffle("COLORFUL BOX by Yoko Ishida (eps 2-11)"),
        str_shuffle("Takarabako -TREASURE BOX- by Masami Okui (eps 13-22)")
      ),
      "ending" => array(
        str_shuffle("COLORFUL BOX by Yoko Ishida (ep 1)"),
        str_shuffle("Animetic Love Letter by Aoi Miyamori (Juri Kimura), Ema Yasuhara (Haruka Yoshimura), Shizuka Sakaki (Haruka Chisuga) (eps 2-12)"),
        str_shuffle("Platinum Jet by Doughnut@Quintet (eps 13, 15-18, 20-24)"),
        str_shuffle("Yama Harinezumi Andes Cuhcky by Miyuki Kunitake (ep 19)")
      )
    );
    $anime->set("theme_songs", $animeTheme_Songs);
    $this->assertEquals($animeTheme_Songs, $anime->get("theme_songs"));
  }

  public function testNonExistentSet() {
    $anime = new AnimeInfo();
    $animeTmp = str_shuffle("weeeeeeee");
    $this->expectException(ModelKeyDoesNotExist::class);
    $anime->set("nonexistentstuff", $animeTmp);
  }

  public function testNonExistentGet() {
    $anime = new AnimeInfo();    
    $this->expectException(ModelKeyDoesNotExist::class);
    $anime->get("nonexistentstuff");
  }

  public function testAsArray() {
    $anime = new AnimeInfo();
    $array = $anime->asArray();
    $this->assertEquals(null, $array["id"]);
  }
}
?>