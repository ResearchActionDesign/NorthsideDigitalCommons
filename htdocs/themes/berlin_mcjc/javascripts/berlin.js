if (!Berlin) {
  var Berlin = {};
}

(function ($) {
  Berlin.dropDown = function () {
    //Hide the rest of the menu
    $("#mobile-nav .menu").show();
    $("#mobile-nav > ul").hide();
    $("#mobile-nav .search-form-container").hide();

    //function the will toggle the menu
    $(".menu").click(function () {
      $("#mobile-nav > ul").toggle();
      $("#mobile-nav .search-form-container").toggle();
    });
  };

  //handle search bar open/close
  $().ready(function () {
    var searchToggle = $("#search-toggle");
    var closeSearch = $(".search-close");
    var primaryNav = $("#primary-nav");
    var searchContainer = $(".search-form-container");
    if (searchToggle.length > 0) {
      searchToggle.on("click", () => {
        searchContainer.css("display", "flex");
        searchToggle.hide();
        primaryNav.hide();
      });

      closeSearch.on("click", () => {
        searchContainer.hide();
        searchToggle.show();
        primaryNav.show();
      });
    }
  });

  $().ready(function () {
    $(".download-file").attr("target", "_blank");
  });

  // Prefill search typed in search box in URL for advanced search link, so queries carry over into advanced search.
  $().ready(function () {
    $("#advanced-form a").mousedown(function (e) {
      $(this).attr("href", "/items/search?query=" + $("#query").val());
    });
  });

  //readmore/less related-items
  $().ready(function () {
    $(".oral-history .item-description").each(function (i, domItem) {
      var textItem = $(domItem);
      var textToHide = textItem.text().slice(280);
      var visibleText = textItem.text().slice(1, 280);

      textItem
        .html(
          visibleText +
            ("<div class='hidden'>" +
              textToHide +
              '<a id="read-less" title="Read Less" style="display: block; cursor: pointer;">Read Less&hellip;</a>')
        )
        .append(
          '<a id="read-more" title="Read More" style="display: block; cursor: pointer;">Read More&hellip;</a>'
        );

      textItem.click(function () {
        $(this).find(".hidden").toggle();
        $(this).find("a:last").hide();
      });
      $(".item-description .hidden").hide();
      $("#read-more").click(function () {
        $(this).hide();
        $("#read-less").show();
      });
      $("#read-less").click(function () {
        $(this).hide();
        $("#read-more").show();
      });
    });
  });

  // Filtering on topics & items page.
  $().ready(function () {
    if (!$("#grid__filter").length) return;
    $("#grid__filter input:checkbox").change(function () {
      var filter = $(this).attr("data-filter");
      if ($(this).is(":checked")) {
        if ($("#grid__filter input:checked").length === 1) {
          $(".grid .item").hide();
        }
        $(".grid .item." + filter).show();
      } else {
        if (!$("#grid__filter input:checked").length) {
          $(".grid .item").show();
        } else {
          $(".grid .item." + filter).hide();
        }
      }
    });
  });
})(jQuery);
