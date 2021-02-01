jQuery(document).ready(function ($) {


    // Temp Offcanvas
    // Freeze Body
    $('.navbar-toggler.right').on('click', function () {
        $('#offcanvas-menu-right').addClass('show')
        $('body').toggleClass('offcanvas-backdrop offcanvas-freeze offcanvas-open')
    });

    // Remove Freeze Body
    $('.offcanvas-header, .backdrop-overlay').on('click', function () {
        $('#offcanvas-menu-right').removeClass('show')
        $('body').removeClass('offcanvas-backdrop offcanvas-freeze offcanvas-open')
    });
    // Temp Offcanvas


    // Dropdown menu animation
    $('.dropdown-menu').addClass('invisible'); //FIRST TIME INVISIBLE

    // Add slideup animation to dropdown open
    $('.dropdown').on('show.bs.dropdown', function (e) {
        $('.dropdown-menu').removeClass('invisible');
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
    });

    // Add slideup animation to dropdown close
    $('.dropdown').on('hide.bs.dropdown', function (e) {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
    });


    // Close dropdown and offcanvas on click .dropdown .nav-link, keep .dropdown-menu open
    $('.navbar-nav>li>a:not(.dropdown-toggle), a.dropdown-item').on('click', function () {
        $('.offcanvas').removeClass('show')
        $('body').removeClass('offcanvas-backdrop offcanvas-freeze offcanvas-open')
    });


    // Close offcanvas, dropdown-menu and backdrop-overlay on resize
    window.onresize = function () {
        $('.offcanvas, .dropdown-menu').removeClass('show')
        $('body').removeClass('offcanvas-backdrop offcanvas-freeze offcanvas-open')
        $('.dropdown-menu').addClass('invisible')
    }


    // Mobile search button hide if empty
    if ($('.searchform').length != 1) {
        $('.top-nav-search-md, .top-nav-search-lg').addClass('hide');
    }
    if ($('.searchform').length != 0) {
        $('.top-nav-search-md, .top-nav-search-lg').removeClass('hide');
    }


    // Active menu item workaround, check navwalker when ready
    var url = window.location.pathname,
        urlRegExp = new RegExp(url.replace(/\/$/, '') + "$"); // create regexp to match current url pathname and remove trailing slash if present as it could collide with the link in navigation in case trailing slash wasn't present there
    // now grab every link from the navigation
    $('.nav-link').each(function () {
        // and test its normalized href against the url pathname regexp
        if (urlRegExp.test(this.href.replace(/\/$/, ''))) {
            $(this).addClass('active');
        }
    });
    // Remove active on frontpage
    if ($('body.home').length) {
        $('.nav-link').removeClass('active');
    }
    // Add active to nav-link if menu-item is active
    if ($('.current_page_item').hasClass('active')) {
        $('.current_page_item.active .nav-link').addClass('active');
    }
    if ($('.current-menu-item').hasClass('active')) {
        $('.current-menu-item.active .nav-link').addClass('active');
    }
    if ($('.current-post-ancestor').hasClass('active')) {
        $('.current-post-ancestor .nav-link').addClass('active');
    }
    if ($('.current_page_parent').hasClass('active')) {
        $('.current_page_parent .nav-link').addClass('active');
    }
    // Active menu item workaround End


}); // jQuery End