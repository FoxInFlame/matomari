<?php
/*

Not a method.

Function required to login to MAL and scrape private pages.

Created by FoxInFlame.
A Part of the matomari API.

*/

function getSession($username, $password) {
  $authenticated = false;
  
  // First cURL to get cookies.
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://myanimelist.net/pressroom');
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);
  
  $doc = new DOMDocument();
  @$doc->loadHTML($response);
  $nodes = $doc->getElementsByTagName("meta");
  
  // Get csrf_token, required for logging in
  for($i = 0; $i < $nodes->length; $i++) {
    $meta = $nodes->item($i);
    if($meta->getAttribute("name") == "csrf_token") {
      $csrf_token = $meta->getAttribute("content");
    }
  }
  
  // Remember cookies.
  list($header, $body) = explode("\r\n\r\n", $response, 2);
  preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
  $cookies = array();
  $nextCurlcookies = "";
  foreach($matches[1] as $item) {
    parse_str($item, $cookie);
    $cookies = array_merge($cookies, $cookie);
    if(array_key_exists("MALSESSIONID", $cookie)) {
      $nextCurlcookies = "MALSESSIONID=" . $cookie["MALSESSIONID"];
    } else if(array_key_exists("MALHLOGSESSID", $cookie)) {
      $nextCurlcookies .= "; MALHLOGSESSID=" . $cookie["MALHLOGSESSID"];
    }
  }
  
  // Request to login using the csrf_token gained above and the cookies.
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://myanimelist.net/login.php?from=%2F');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_COOKIE, $nextCurlcookies);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('user_name' => $username, 'password' => $password, 'cookie' => '1', 'sublogin' => 'Login', 'submit' => '1', 'csrf_token' => $csrf_token)));
  $response = curl_exec($ch);
  list($header, $body) = explode("\r\n\r\n", $response, 2);
  preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
  $cookies = array();
  foreach($matches[1] as $item) {
    parse_str($item, $cookie);
    $cookies = array_merge($cookies, $cookie);
    if(array_key_exists("MALSESSIONID", $cookie)) {
      $requestCurlcookies = "MALSESSIONID=" . $cookie["MALSESSIONID"];
    } else if(array_key_exists("MALHLOGSESSID", $cookie)) {
      $requestCurlcookies .= "; MALHLOGSESSID=" . $cookie["MALHLOGSESSID"];
    }
  }
  if(strpos($response, "Location: https://myanimelist.net/") !== false && isset($cookies["is_logged_in"])) {
    $authenticated = true;
  }
  if($authenticated) {
    $requestCurlcookies .= "; is_logged_in=1";
    return array(
      "cookie_string" => $requestCurlcookies,
      "csrf_token" => $csrf_token
    );
  } else {
    header('HTTP/1.0 401 Unauthorized');
    echo json_encode(array(
      "error" => "Wrong Credentials."
    ));
    return false;
  }
}
?>