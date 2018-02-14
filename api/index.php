<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

require __DIR__ . '/../vendor/autoload.php';

use Matomari\Builders\URLRequestBuilder;
use Matomari\Builders\RequestBuilder;
use Matomari\Matomari;

try {
  // Build instance of Request.
  $request_builder = new RequestBuilder();
  $request_builder->build($_SERVER);
  $request = $request_builder->getRequest();

  // Start core.
  $matomari = new Matomari();
  $matomari->handle($request);
} catch (Exception $e) {
  echo json_encode(array(
    'code' => $e->getCode(),
    'message' => $e->getMessage()
  ));
  http_response_code($e->getCode());
  return;
}