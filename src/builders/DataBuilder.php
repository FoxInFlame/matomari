<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Builders;

use Stash;
use Matomari\Exceptions\MatomariError;

/**
 * Return array of data for the request.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class DataBuilder
{

  /**
   * Array returned from cache or request.
   * @var Array
   */
  private $array;

  /**
   * Build Array.
   * 
   * @param String $cache_key The key for retrieving/setting cache.
   * @param Function $responsearray The response associative array.
   * @since 0.5 
   */
  public function build($cache_key, $fallback) {
    
    // Initialise the driver: FileSystem
    // We should set a cache path because the default one makes the keys too long.
    $driver = new Stash\Driver\FileSystem(array('path' => '/tmp/cache/'));

    // Initialise the Stash Pool using the driver.
    $pool = new Stash\Pool($driver);

    // Try to get an item from the pool using $cache_key.
    $item = $pool->getItem($cache_key);
    $data = $item->get();

    // Set ValidationMethod to OLD so it uses the old values when a new one is updating.
    $item->setInvalidationMethod(Stash\Invalidation::OLD);

    if($item->isMiss()) {

      // Lock the item so new requests don't fall into the same path.
      $item->lock();

      // The response should contain [Data Array, Time Limit in Seconds]
      $fallback_response = $fallback();

      // Set the cache data to the one retrieved in the fallback.
      $data = $fallback_response[0];
      $item->set($fallback_response[0]);

      // Set the cache timeout to the one retrieved in the fallback.
      $item->expiresAfter($fallback_response[1]);

      // Save the newly made cache item to the pool.
      $pool->save($item);

    }

    if(!$data) {
      throw new MatomariError('The servers have responded with an empty data.', 500);
    }

    $this->array = $data;

  }

  /**
   * Retrieve the built Array.
   * 
   * @return Array
   * @since 0.5
   */
  public function getArray() {
    return $this->array;
  }
  
}