<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Parsers;

use Sunra\PhpSimple\HtmlDomParser;

/**
 * The base class for all parsers.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class Parser
{

  /**
   * Parser class to be used for parsing HTML data.
   * @var HtmlDomParser
   */
  protected $parser;

  /**
   * @param HtmlDomParser Parser class to be used
   * @since 0.5
   */
  public function __construct(HtmlDomParser $parser) {

    $this->parser = $parser;

  }
  
  /**
   * Retrieve the model.
   * 
   * @return Array
   * @since 0.5
   */
  public function getArray() {

    return $this->array;

  }

}