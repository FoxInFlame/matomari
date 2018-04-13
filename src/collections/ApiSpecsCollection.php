<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Collections;

use Matomari\Exceptions\MatomariError;
use Matomari\Collections\Collection;

/**
 * Collection for api specs - call Swagger's scanner and return the specification.
 * 
 * @OAS\Info(
 *   title="matomari API",
 *   version="0.5",
 *   description="An unofficial REST API for MyAnimeList",
 *   @OAS\Contact(
 *     email="burningfoxinflame@gmail.com"
 *   )
 * )
 * 
 * @OAS\Server(
 *   url="/api/{basePath}",
 *   @OAS\ServerVariable(
 *     serverVariable="basePath",
 *     enum={"0.5"},
 *     default="0.5"
 *   )
 * )
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class ApiSpecsCollection extends Collection
{

  /**
   * Create the overall API structure.
   * 
   * @since 0.5
   */
  public function __construct() {

    $swagger = \Swagger\scan('../src');

    $this->array = $swagger;

  }

}