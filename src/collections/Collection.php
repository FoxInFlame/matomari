<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Collections;

/**
 * The base class for all collections.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class Collection
{

  /**
   * Contains the model array generated in the constructor.
   * 
   * @var Array
   */
  protected $array;

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