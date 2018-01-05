<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . "/../../src/parsers/parser.user.list.anime.USERNAME.php");

class UserListAnimeUSERNAMEParserTest extends TestCase {

  private $anime; 

  public function testParse() {
    $xmlcontent = file_get_contents(dirname(__FILE__) . "/../mal_pages/mal_page.malappinfo.Drutol.xml");
    $jsoncontent = file_get_contents(dirname(__FILE__) . "/../mal_pages/mal_page.listload.Drutol.json");

    return UserListAnimeUSERNAMEParser::parse($xmlcontent, $jsoncontent);
  }

  /**
  * @depends testParse
  */
  public function testType($anime) {
    $this->assertInternalType("array", $anime);
    $this->assertInternalType("array", $anime[0]);
    $this->assertInternalType("array", $anime[1]);
  }

  /**
  * @depends testParse
  */
  public function testStats($anime) {
    $this->assertEquals(259, $anime[0]["total"]);
  }

  /**
  * @depends testParse
  */
  public function testAnime($anime) {
    $this->assertEquals(1, $anime[1][0]["id"]);
    $this->assertEquals(array(
      "synonyms" => array("Cowboy Bebop")), $anime[1][0]["other_titles"]);
    $this->assertEquals("2016-10-20", $anime[1][0]["watch_dates"]["from"]);
    $this->assertEquals("2016-10-31T18:46:23+00:00", $anime[1][0]["last_updated"]);
  }
  
}
?>