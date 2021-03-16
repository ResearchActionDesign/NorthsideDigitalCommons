if (!FromTheRockWall) {
  var FromTheRockWall = {};
}

(function ($) {
  //handle search bar open/close
  FromTheRockWall.searchToggle = function () {
    var searchToggle = $("header nav a[href='/search']");
    var searchFormContainer = $("#search-form-container");
    var navBar = $("header nav ul.navigation");
    searchToggle.attr("aria-expanded", false);
    searchToggle.attr("aria-controls", "search-form-container");
    searchToggle.append('<i class="fa fa-search"></i>');
    searchToggle.after("");

    searchToggle.click(function (e) {
      e.preventDefault();
      $(this).attr("aria-expanded", true);
      searchFormContainer.outerHeight(navBar.outerHeight());
      navBar.hide(0.5);
      searchFormContainer.show(0.5);
      $("#query").focus();
    });

    $("#search-close").click(function () {
      searchFormContainer.hide(0.5);
      navBar.show(0.5);
    });
  };

  // Add ellipses and read-more on person page item descriptions
  FromTheRockWall.readMore = function () {
    $(".person.show .oral-histories .item-description--text").each(function (
      index
    ) {
      var id = this.id;
      $(this).addClass("truncated");

      $(
        "<button class='read-more-button' data-read-more='" +
          id +
          "' aria-controls='" +
          id +
          "' aria-expanded='false'>Show more</button>"
      ).insertAfter($(this));
    });
    $("button.read-more-button").click(function () {
      var id = $(this).attr("data-read-more");
      var itemDescription = $("#" + id);
      if (itemDescription.hasClass("truncated")) {
        itemDescription.removeClass("truncated");
        $(this).html("Show less");
        $(this).attr("aria-expanded", true);
      } else {
        itemDescription.addClass("truncated");
        $(this).html("Show more");
        $(this).attr("aria-expanded", false);
      }
    });
  };

  FromTheRockWall.downloads = function () {
    $().ready(function () {
      $(".download-file").attr("target", "_blank");
    });
  };

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

    if ($grid && $grid.masonry) {
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

  FromTheRockWall.audioGreeting = function () {
    var audioGreetingElement = document.getElementById(
      "audio-greeting-element"
    );
    var audioGreetingButton = document.getElementById("audio-greeting-button");
    if (audioGreetingElement && audioGreetingButton) {
      function playAudio() {
        try {
          audioGreetingElement.play();
          audioGreetingButton.classList.add("playing");
        } catch (e) {
          audioGreetingButton.classList.remove("playing");
        }
      }
      audioGreetingButton.addEventListener("click", function () {
        if (audioGreetingElement.paused) {
          playAudio();
        } else {
          audioGreetingElement.pause();
          audioGreetingButton.classList.remove("playing");
        }
      });
    }
  };

  FromTheRockWall.homepageAudio = function () {
    var $muteButton = $("#unmute-button");
    var $pauseButton = $("#play-pause-button");
    var myvideo = document.getElementById("homepage-video-element");
    var myaudio = document.getElementById("homepage-background-audio");
    if ($muteButton) {
      $muteButton.on("click", function () {
        if ($(this).attr("data-toggled") === "true") {
          myaudio.pause();
          $(this).attr("data-toggled", false);
          $(this)
            .children("i")
            .removeClass("fa-volume-up")
            .addClass("fa-volume-off");
          $(this).children(".button-text").html("Listen");
        } else {
          myaudio.play();
          $(this).attr("data-toggled", true);
          $(this)
            .children("i")
            .removeClass("fa-volume-off")
            .addClass("fa-volume-up");
          $(this).children(".button-text").html("Mute");
        }
      });
    }
    if ($pauseButton) {
      $pauseButton.on("click", function () {
        if ($(this).attr("data-toggled") === "true") {
          if ($muteButton && $muteButton.attr("data-toggled") === "true") {
            myaudio.play();
          }
          myvideo.play();
          $(this).attr("data-toggled", false);
          $(this).children("i").removeClass("fa-play").addClass("fa-pause");
          $(this).children(".button-text").html("Pause");
        } else {
          if ($muteButton && $muteButton.attr("data-toggled") === "true") {
            myaudio.pause();
          }
          myvideo.pause();
          $(this).attr("data-toggled", true);
          $(this).children("i").removeClass("fa-pause").addClass("fa-play");
          $(this).children(".button-text").html("Play");
        }
      });
    }
  };
})(jQuery);
