/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
1.  Header
2.  Theme
--------------------------------------------------------------*/

/*--------------------------------------------------------------
1. Header
--------------------------------------------------------------*/

jQuery(function ($) {
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
  $('.top-nav-search input:first-of-type').focusout(function () {
    $('#collapse-search').collapse('hide');
  });
}); // jQuery End

/*--------------------------------------------------------------
2. Theme
--------------------------------------------------------------*/

jQuery(function ($) {
  // Smooth Scroll. Will be removed when Safari supports scroll-behaviour: smooth (Bootstrap 5).
  $(function () {
    $('a[href*="#"]:not([href="#"]):not(a.comment-reply-link):not([href="#tab-reviews"]):not([href="#tab-additional_information"]):not([href="#tab-description"]):not([href="#reviews"]):not([href="#carouselExampleIndicators"]):not(.wc-tabs .nav-link):not([data-smoothscroll="false"])').click(function () {
      if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        if (target.length) {
          // Change your offset according to your navbar height
          $('html, body').animate({ scrollTop: target.offset().top - 55 }, 1000);
          return !1;
        }
      }
    });
  });

  // Scroll to ID from external url. Will be removed when Safari supports scroll-behaviour: smooth (Bootstrap 5).
  if (window.location.hash) scroll(0, 0);
  setTimeout(function () {
    scroll(0, 0);
  }, 1);
  $(function () {
    $('.scroll').on('click', function (e) {
      e.preventDefault();
      // Change your offset according to your navbar height
      $('html, body').animate({ scrollTop: $($(this).attr('href')).offset().top - 55 }, 1000, 'swing');
    });
    if (window.location.hash) {
      // Change your offset according to your navbar height
      $('html, body').animate({ scrollTop: $(window.location.hash).offset().top - 55 }, 1000, 'swing');
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

  // WC Quantity Input
  if (!String.prototype.getDecimals) {
    String.prototype.getDecimals = function () {
      var num = this,
        match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
      if (!match) {
        return 0;
      }
      return Math.max(0, (match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0));
    }
  }
  // Quantity "plus" and "minus" buttons
  $(document.body).on('click', '.plus, .minus', function () {
    var $qty = $(this).closest('.quantity').find('.qty'),
      currentVal = parseFloat($qty.val()),
      max = parseFloat($qty.attr('max')),
      min = parseFloat($qty.attr('min')),
      step = $qty.attr('step');

    // Format values
    if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
    if (max === '' || max === 'NaN') max = '';
    if (min === '' || min === 'NaN') min = 0;
    if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;

    // Change the value
    if ($(this).is('.plus')) {
      if (max && (currentVal >= max)) {
        $qty.val(max);
      } else {
        $qty.val((currentVal + parseFloat(step)).toFixed(step.getDecimals()));
      }
    } else {
      if (min && (currentVal <= min)) {
        $qty.val(min);
      } else if (currentVal > 0) {
        $qty.val((currentVal - parseFloat(step)).toFixed(step.getDecimals()));
      }
    }

    // Trigger change event
    $qty.trigger('change');
  });
  // WC Quantity Input End

}); // jQuery End
