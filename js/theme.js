/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
1.  Header
2.  Theme
--------------------------------------------------------------*/


/*--------------------------------------------------------------
1. Header
--------------------------------------------------------------*/


jQuery(document).ready(function ($) {


    // Hide offcanvas menu in navbar and enable body scroll on resize through the breakpoints
    $(window).on('resize', function () {
        $('.navbar .offcanvas').offcanvas('hide');
    });


    // Close offcanvas on click a, keep .dropdown-menu open
    $('.offcanvas a:not(.dropdown-toggle):not(a.remove_from_cart_button), a.dropdown-item').on('click', function () {
        $('.offcanvas').offcanvas('hide');
    });


    // Dropdown menu animation
    // Add slideDown animation to Bootstrap dropdown when expanding.
    $('.dropdown').on('show.bs.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
    });

    // Add slideUp animation to Bootstrap dropdown when collapsing.
    $('.dropdown').on('hide.bs.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
    });


    // Search collapse button hide if empty
    if ($('#collapse-search').children().length == 0) {
        $('.top-nav-search-md, .top-nav-search-lg').remove();
    }

    // Searchform focus
    $('#collapse-search').on('shown.bs.collapse', function () {
        $('.top-nav-search input:first-of-type').focus();
    });
    
    // Close collapse if searchform loses focus
    $(".top-nav-search input:first-of-type").focusout(function () {
        $('#collapse-search').collapse("hide");
    });    


}); // jQuery End




/*--------------------------------------------------------------
2. Theme
--------------------------------------------------------------*/

jQuery(document).ready(function ($) {


    // Smooth Scroll. Will be removed when Safari supports scroll-behaviour: smooth (Bootstrap 5).
    $(function () {
        $('a[href*="#"]:not([href="#"]):not(a.comment-reply-link):not([href="#tab-reviews"]):not([href="#tab-additional_information"]):not([href="#tab-description"]):not([href="#reviews"]):not([href="#carouselExampleIndicators"]):not([data-smoothscroll="false"])').click(function () {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html, body').animate({
                        // Change your offset according to your navbar height
                        scrollTop: target.offset().top - 55
                    }, 1000);
                    return !1
                }
            }
        })
    });


    // Scroll to ID from external url. Will be removed when Safari supports scroll-behaviour: smooth (Bootstrap 5).
    if (window.location.hash) scroll(0, 0);
    setTimeout(function () {
        scroll(0, 0)
    }, 1);
    $(function () {
        $('.scroll').on('click', function (e) {
            e.preventDefault();
            $('html, body').animate({
                // Change your offset according to your navbar height
                scrollTop: $($(this).attr('href')).offset().top - 55
            }, 1000, 'swing')
        });
        if (window.location.hash) {
            $('html, body').animate({
                // Change your offset according to your navbar height
                scrollTop: $(window.location.hash).offset().top - 55
            }, 1000, 'swing')
        }
    });


    // Scroll to top Button
    $(window).scroll(function () {
        var scroll = $(window).scrollTop();

        if (scroll >= 500) {
            $(".top-button").addClass("visible");
        } else {
            $(".top-button").removeClass("visible");
        }
    });


    // div height, add class to your content
    $(".height-50").css("height", 0.5 * $(window).height());
    $(".height-75").css("height", 0.75 * $(window).height());
    $(".height-85").css("height", 0.85 * $(window).height());
    $(".height-100").css("height", 1.0 * $(window).height());


    // Forms
    $('select').addClass('form-select').removeClass('form-control'); // form-control is added to select by WooCommerce form filter


    // Alert links
    $('.alert a').addClass('alert-link');


}); // jQuery End
