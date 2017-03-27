$("#freewha").remove();
$(".ui.dropdown").dropdown();

$(".ui.sidebar").sidebar("show");

if($(".property").length !== 0) {
  $(".property").popup();
}