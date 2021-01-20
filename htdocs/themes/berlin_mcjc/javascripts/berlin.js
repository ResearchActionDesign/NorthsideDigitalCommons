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
    var closeSearch = $("#search-close");
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
    $(".person.show .oral-history .item-description").each(function (
      i,
      domItem
    ) {
      var textItem = $(domItem);
      var textToHide = textItem.text().slice(280);
      var ellipsis = "<span class='ellipsis'>...</span>";
      var visibleText = textItem.text().slice(1, 280) + ellipsis;
      var showReadMore = true;

      console.log("hello", textItem);

      textItem
        .html(
          visibleText +
            ("<span class='hidden'>" +
              textToHide +
              '<a class="read-less" title="Read Less" style="display: block; cursor: pointer;">Read Less&hellip;</a></span>')
        )
        .append(
          '<a class="read-more" title="Read More" style="display: block; cursor: pointer;">Read More&hellip;</a>'
        );

      const readMore = $(".read-more");
      const readLess = $(".read-less");
      const ellipsisSpan = $(".ellipsis");
      console.log(readMore);

      textItem.click(function () {
        if (showReadMore === true) {
          $(this).find(".hidden").hide();
          readMore.show();
          readLess.hide();
          ellipsisSpan.show();
          showReadMore = false;
        } else {
          $(this).find(".hidden").show();
          readLess.show();
          readMore.hide();
          ellipsisSpan.hide();
          showReadMore = true;
        }
      });
      $(".item-description .hidden").hide();
    });
  });

  // Filtering on topics & items page.
  $().ready(function () {
    if (!$("#grid__filter").length) return;
    $("#grid__filter input:checkbox").change(function () {
      var filter = $(this).attr("data-filter");
      if ($(this).is(":checked")) {
        if ($("#grid__filter input:checked").length === 1) {
          $(".item").hide();
        }
        $(".item." + filter).show();
      } else {
        if (!$("#grid__filter input:checked").length) {
          $(".item").show();
        } else {
          $(".item." + filter).hide();
        }
      }
      $(".masonry-grid .grid-items").masonry("layout");
    });
  });

  $().ready(function () {
    // Masonry grids.

    var $grid = $(".masonry-grid .grid-items");

    if ($grid) {
      $grid.masonry({
        itemSelector: ".item",
        columnWidth: 300, // Coordinate this with $square-thumbnail-size in base.scss and with the Derivative images width setting.
        gutter: 20,
        horizontalOrder: true,
        fitWidth: true,
        initLayout: true,
        isAnimated: false,
      });

      $grid.on("load", function () {
        $grid.masonry("layout");
      });

      $(".masonry-grid .item-img img").on("load", function () {
        $grid.masonry("layout");
      });
    }
  });
})(jQuery);
