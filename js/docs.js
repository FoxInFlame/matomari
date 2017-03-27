$("#freewha").remove();
$(".ui.dropdown").dropdown();

$(document).ready(function() {
  if($(".tooltip, span[class^='type-'").length !== 0) {
    $(".tooltip, span[class^='type-']").popup();
  }
});