/*--------------------------------------------------------------
Theme JS
--------------------------------------------------------------*/

jQuery(function ($) {
  // Close offcanvas on click a, keep .dropdown-menu open (see https://github.com/bootscore/bootscore/discussions/347)
  $('.offcanvas a:not(.dropdown-toggle, .remove_from_cart_button)').on('click', function () {
    $('.offcanvas').offcanvas('hide');
  });

  // Search collapse button hide if empty
  // Deprecated v5.2.3.4, done by php if (is_active_sidebar('top-nav-search')) in header.php
  // Remove in v6
  if ($('#collapse-search').children().length == 0) {
    $('.top-nav-search-md, .top-nav-search-lg').remove();
  }

  // Searchform focus
  $('#collapse-search').on('shown.bs.collapse', function () {
    $('.top-nav-search input:first-of-type').trigger('focus');
  });

  // Close collapse if click outside searchform
  $(document).on('click', function (event) {
    if ($(event.target).closest('#collapse-search').length === 0) {
      $('#collapse-search').collapse('hide');
    }
  });

  // Scroll to top Button
  $(window).on('scroll', function () {
    var scroll = $(window).scrollTop();

    if (scroll >= 500) {
      $('.top-button').addClass('visible');
    } else {
      $('.top-button').removeClass('visible');
    }
  });

  // div height, add class to your content
  $('.height-50').css('height', 0.5 * $(window).height());
  $('.height-75').css('height', 0.75 * $(window).height());
  $('.height-85').css('height', 0.85 * $(window).height());
  $('.height-100').css('height', 1.0 * $(window).height());

}); // jQuery End
