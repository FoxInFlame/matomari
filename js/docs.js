$("#freewha").remove();
$(".ui.dropdown").dropdown();

$(document).ready(function() {
  if($(".tooltip, .property, span[class^='type-'").length !== 0) {
    $(".tooltip, .property, span[class^='type-']").popup();
  }
});