<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Parsers;

/**
 * The base class for all parsers.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class Parser
{
  
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