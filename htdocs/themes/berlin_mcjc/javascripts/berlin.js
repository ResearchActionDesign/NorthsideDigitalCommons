if (!Berlin) {
    var Berlin = {};
}

(function ($) {    
    Berlin.dropDown = function(){
        var dropdownMenu = $('#mobile-nav');
        dropdownMenu.prepend('<a href="#" class="menu">Menu</a>');
        //Hide the rest of the menu
        $('#mobile-nav .navigation').hide();

        //function the will toggle the menu
        $('.menu').click(function() {
            $("#mobile-nav .navigation").slideToggle();
        });
    };

    $().ready(function() { $('.download-file').attr('target', '_blank'); });

    // Prefill search typed in search box in URL for advanced search link, so queries carry over into advanced search.
    $().ready(function() {
        $("#advanced-form a").mousedown(function (e) {
            $(this).attr('href', '/items/search?query=' + $("#query").val());
        })
        }
    );
})(jQuery);
