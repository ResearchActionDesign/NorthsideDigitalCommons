if (!Berlin) {
  var Berlin = {};
}

(function ($) {
  Berlin.dropDown = function () {
    var dropdownMenu = $("#mobile-nav");
    dropdownMenu.prepend('<a href="#" class="menu">Menu</a>');
    //Hide the rest of the menu
    $("#mobile-nav .navigation").hide();

    //function the will toggle the menu
    $(".menu").click(function () {
      $("#mobile-nav .navigation").slideToggle();
    });
  };

  $().ready(function () {
    $(".download-file").attr("target", "_blank");
  });

  // Prefill search typed in search box in URL for advanced search link, so queries carry over into advanced search.
  $().ready(function () {
    $("#advanced-form a").mousedown(function (e) {
      $(this).attr("href", "/items/search?query=" + $("#query").val());
    });
  });

  // Filtering on topics & items page.
  $().ready(function () {
    if (!$("#grid__filter").length) return;
    $("#grid__filter input:checkbox").change(function () {
      var filter = $(this).attr("data-filter");
      if ($(this).is(":checked")) {
        if ($("#grid__filter input:checked").length === 1) {
          $(".grid div.grid__item").hide();
        }
        $(".grid div.grid__item." + filter).show();
      } else {
        if (!$("#grid__filter input:checked").length) {
          $(".grid div.grid__item").show();
        } else {
          $(".grid div.grid__item." + filter).hide();
        }
      }
    });
  });
})(jQuery);
