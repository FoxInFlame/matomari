<?php
// Thank you,
// http://www.sean-barton.co.uk/2009/03/turning-an-array-or-object-into-xml-using-php/
// I couldn't do this on my own.

function generate_xml_from_array($array, $node_name) {
  $xml = '';

  if (is_array($array) || is_object($array)) {
    foreach ($array as $key => $value) {
      if (is_numeric($key)) {
        $key = $node_name;
      }

      $xml .= "<" . $key . ">" . "\n" . generate_xml_from_array($value, $node_name) . "</" . $key . ">" . "\n";
    }
  } else {
    if(is_bool($array) === true) {
      if($array) {
        $xml = "true" . "\n";
      } else {
        $xml = "false" . "\n";
      }
    } else {
      $xml = htmlspecialchars($array) . "\n";
    }
  }
  return $xml;
}

function generate_valid_xml_from_array($array, $node_block='matomari', $node_name='item') {
  $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>" . "\n";

  $xml .= "<" . $node_block . ">" . "\n";
  $xml .= generate_xml_from_array($array, $node_name);
  $xml .= "</" . $node_block . ">" . "\n";

  return $xml;
}

function json_to_xml($json) {
  $array = json_decode(utf8_encode($json));
  $xml_data = generate_valid_xml_from_array($array, "matomari", "item");
  
  return $xml_data;
}
?>