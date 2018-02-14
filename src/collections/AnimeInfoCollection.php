<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Collections;

use GuzzleHttp\Client;
use Matomari\Exceptions\MatomariError;
use Matomari\Collections\Collection;
use Matomari\Parsers\AnimeInfoParser;

/**
 * Collection for anime info - grabs information from cache or URL, and creates Models.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class AnimeInfoCollection extends Collection
{

  /**
   * Call request, create a parser, and store the model generated from the parser
   * When the deepest level of caching is required (storing HTML files), it should be
   * done in this layer.
   * 
   * @param Integer $anime_id The Anime ID on MAL
   * @since 0.5
   */
  public function __construct($anime_id) {

    $guzzle_client = new Client();
    $response = $guzzle_client->request('GET', 'https://myanimelist.net/anime/' . $anime_id, ['http_errors' => false]);

    if($response->getStatusCode() === 429) {
      throw new MatomariError('Too many frequent requests. Please wait and retry.', 429);
    } else if($response->getStatusCode() === 404) {
      throw new MatomariError('Anime with specified ID could not be found.', 404);
    } else if($response->getStatusCode() !== 200) {
      throw new MatomariError('There was an unknown error with MAL. Contact FoxInFlame.', 500);
    }

    $this->array = AnimeInfoParser::parse($response->getBody());
  }

}