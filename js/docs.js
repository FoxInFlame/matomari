$("#freewha").remove();
$(".ui.dropdown").dropdown();

$(document).ready(function() {
  if($(".property").length !== 0) {
    $(".property").popup();
  }
});