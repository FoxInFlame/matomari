<?php
/*

Purge all cache. A CronJob script runs this script every day at 00:00 SWT.

Authentication: None.
Parameters:
  - None.

Created by FoxInFlame.
A Part of the matomari API.

*/

if($_SERVER['REMOTE_ADDR'] != "88.99.90.240" && $_SERVER['REMOTE_ADDR'] != "162.158.88.211") {
  echo $_SERVER['REMOTE_ADDR'] . " is not our list of allowed sources.";
  http_response_code(404);
  return;
}

header("Access-Control-Allow-Origin: *");
require_once(dirname(__FILE__) . "/class/class.cache.php");

$data = new Data();

$data->purgeCache();

echo "Deleted.";
?>