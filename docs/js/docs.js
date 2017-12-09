$("#freewha").remove();
$(".ui.dropdown").dropdown();

$(document).ready(function() {
  if($(".tooltip, .property, span[class^='type-'").length !== 0) {
    $(".tooltip, .property, span[class^='type-']").popup({
      hoverable: true,
      transition: "fade"
    });
  }
  if($(".example-code").length !== 0) {
    $(".example-code .item").tab({
      history: true
    });
  }
});