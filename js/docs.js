$("#freewha").remove();
$(".ui.dropdown").dropdown();

$(document).ready(function() {
  if($(".tooltip").length !== 0) {
    $(".tooltip, span[class^=type-]").popup();
  }
});