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
 * @OA\Info(
 *   title="matomari API",
 *   version="0.5",
 *   description="

## An unofficial REST API for MyAnimeList

The matomari API is a RESTful API for MyAnimeList designed using PHP.
It is not expected to be used in production yet, due to its lack of features. 

This page describes all the endpoints, their parameters, and their responses. It is rendered using ReDoc supplied with OpenAPI 3 spec compliant API data.
     ",
 *   @OA\Contact(
 *     email="burningfoxinflame@gmail.com"
 *   )
 * )
 * 
 * @OA\Server(
 *   url="/api/0.5"
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

    $swagger = \OpenApi\scan('../src');

    $this->array = $swagger;

  }

}