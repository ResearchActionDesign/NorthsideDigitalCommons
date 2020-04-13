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

  //handle search bar open/close
  $().ready(function () {
    var searchToggle = $("#search-toggle");
    var closeSearch = $(".search-close");
    var primaryNav = $("#primary-nav");
    var searchContainer = $(".search-form-container");
    console.log(searchToggle);
    if (searchToggle.length > 0) {
      searchToggle.on("click", () => {
        console.log("click");
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
    var textItem = $("#item-description");
    var textToHide = textItem.text().substring(280);
    var visibleText = textItem.text().substring(1, 280);

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
      console.log(this);
      $(this).find(".hidden").toggle();
    });
    $("#item-description .hidden").hide();
    $("#read-more").click(function () {
      $("#read-more").hide();
      $("#read-less").show();
    });
    $("#read-less").click(function () {
      $("#read-less").hide();
      $("#read-more").show();
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
