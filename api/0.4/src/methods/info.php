<?php
/*

Shows information about the API version.

This method is cached for a month on the browser side.
Method: GET
        /info
Authentication: None Required.
Parameters:
  - None

Created by FoxInFlame.
A Part of the matomari API.

*/

// [+] ============================================== [+]
// [+] ---------------------------------------------- [+]
// [+] -------------------HEADERS-------------------- [+]
// [+] ---------------------------------------------- [+]
// [+] ============================================== [+]

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Cache-Control: max-age=86400, public"); // 1 day

call_user_func(function() {
  
  $methods_arr = array();
  $methods_dir = new DirectoryIterator(dirname(__FILE__));
  require_once(dirname(__FILE__) . "/../../../../docs/0.4/methods.php");
  $tested_methods_arr = array();
  $documented_methods_arr = array();
  foreach($methods_dir as $fileinfo) {
    if($fileinfo->isFile()) {
      $method_name = $fileinfo->getFilename();
      $method_sections = explode(".", $method_name);
      $method_name_final_arr = array();
      foreach($method_sections as $key => $section) {
        if($key === count($method_sections) - 1) break;
        if(strtoupper($section) === $section) {
          $method_name_final_arr[$key] = ":" . strtolower($section);
        } else {
          $method_name_final_arr[$key] = $section;
        }
      }

      $contents = file(dirname(__FILE__) . "/" . $fileinfo->getFilename());
      if(strpos($contents[3], "Status: Completed and Tested.") !== false) {
        array_push($tested_methods_arr, implode("/", $method_name_final_arr));
      }

      if(file_exists(dirname(__FILE__) . "/../../../../docs/0.4/" . str_replace(".php", ".html", $fileinfo->getFilename()))) {
        array_push($documented_methods_arr, implode("/", $method_name_final_arr));
      }
    }
  }

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/FoxInFlame/matomari/commits?sha=0.4");
  curl_setopt($ch, CURLOPT_USERAGENT, "matomariAPI/0.4");
  curl_Setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = json_decode(curl_exec($ch));

  $now = new DateTime();
  $then = new DateTime($response[0]->commit->author->date);
  $difference = $then->diff($now);

  echo json_encode(array(
    "version" => 0.4,
    "stable" => false,
    "latest_commit" => array(
      "hash" => $response[0]->sha,
      "author" => $response[0]->commit->author->name,
      "date" => $response[0]->commit->author->date,
      "days_ago" => $difference->days
    ),
    "completed_percentage" => round(count($tested_methods_arr) / count($filenames) * 100, 2), // Round to 2 decimal places.
    "documented_percentage_total" => round(count($documented_methods_arr) / count($filenames) * 100, 2),
    "documented_percentage_tested" => round(count($documented_methods_arr) / count($tested_methods_arr) * 100, 2),
    "total_methods" => count($filenames),
    "methods" => $filenames,
    "total_tested_methods" => count($tested_methods_arr),
    "tested_methods" => $tested_methods_arr,
    "total_documented_methods" => count($documented_methods_arr),
    "documented_methods" => $documented_methods_arr
  ));
});

?>