<?php
function get_icon($type) {
  if(strtolower($type) == "array") {
    return "unordered list";
  } else if(strtolower($type) == "integer") {
    return "sort numeric ascending";
  } else if(strtolower($type) == "float") {
    return "circle";
  } else if(strtolower($type) == "string") {
    return "sort alphabet ascending";
  } else if(strtolower($type) == "object") {
    return "angle double right";
  } else if(strtolower($type) == "boolean") {
    return "options";
  }
}

$output = "<i>Hover over the icons to see the type of each key!</i><br><br><div class=\"ui list\">";
model2list($model);

function model2list($model) {
  global $output;
  foreach($model as $key => $value) {
    $output .= "
    <div class=\"item\">
      <i data-content=\"" . $value["type"] . "\" class=\"" . get_icon($value["type"]) . " icon\"></i>
      <div class=\"content\">
        <div class=\"header\">" . $key . "</div>
        <div class=\"description\">" . $value["description"] . "</div>";
    if(array_key_exists("children", $value) && is_array($value["children"])) {
      $output .= "<div class=\"list\">";
      $output .= model2list($value["children"]);
    }
    $output .= "</div></div>";
  }
  $output .= "</div>";
}
echo $output;
?>