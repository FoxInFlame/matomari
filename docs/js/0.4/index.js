$(document).ready(function() {
  $.ajax({
    url: "/api/0.4/src/methods/info.php",
    type: "GET",
    dataType: "json",
    success: function(data) {
      $("#progress_overall").progress({
        value: data.total_tested_methods,
        total: data.total_methods,
        text: {
          active: '{value} of {total} methods completed.',
          success: 'All methods are completed and ready for you to use!'
        }
      });
      $("#progress_docs").progress({
        value: data.total_documented_methods,
        total: data.total_methods,
        text: {
          active: '{value} of {total} methods documented.',
          success: 'All methods are documented!'
        }
      });
      var source = [];
      for(var i = 0; i < data.methods.length; i++) {
        doc_name_parts = data.methods[i].split("/");
        for(var j = 0; j < doc_name_parts.length; j++) {
          if(doc_name_parts[j].charAt(0) === ":") {
            doc_name_parts[j] = doc_name_parts[j].toUpperCase();
          }
        }
        source.push({
          title: data.methods[i],
          url: "/docs/0.4/" + doc_name_parts.join(".")
        });
      }
      source.push({
        title: 'Response Codes',
        url: "/docs/0.4/responsecodes"
      });
      $("#search_api").search({
        source: source
      });
    }
  });
});