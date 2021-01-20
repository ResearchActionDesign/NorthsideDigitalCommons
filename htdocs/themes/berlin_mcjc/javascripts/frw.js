if (!FromTheRockWall) {
  var FromTheRockWall = {};
}

(function ($) {
  //handle search bar open/close
  FromTheRockWall.searchToggle = function () {
    var searchToggle = $("#search-toggle");
    var searchFormContainer = $("#search-form-container");
    var navBar = $("header nav ul.navigation");
    searchToggle.show();
    searchToggle.click(function () {
      $(this).hide();
      $(this).attr("aria-expanded", true);
      navBar.hide();
      searchFormContainer.show();
      $("#query").focus();
    });
    searchFormContainer.hide();

    $("#search-close").click(function () {
      searchFormContainer.hide();
      searchToggle.show();
      $(this).attr("aria-expanded", false);
      navBar.show();
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

  //readmore/less related-items
  $().ready(function () {
    $(".person.show .oral-history .item-description").each(function (
      i,
      domItem
    ) {
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
  FromTheRockWall.filters = function () {
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
  };

  FromTheRockWall.grids = function () {
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
  };
})(jQuery);
