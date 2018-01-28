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

// Build instance of Request.
$request_builder = new RequestBuilder();
$request_builder->build($_SERVER);
$request = $request_builder->getRequest();

// Start core.
$matomari = new Matomari();
$matomari->handle($request);