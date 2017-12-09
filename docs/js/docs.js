$("#freewha").remove();
$(".ui.dropdown").dropdown();

$(document).ready(function() {
  if($(".tooltip, .property, span[class^='type-'], .ui.tab[data-tab=model] i.icon[data-content]").length !== 0) {
    $(".tooltip, .property, span[class^='type-'], .ui.tab[data-tab=model] i.icon[data-content]").popup({
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