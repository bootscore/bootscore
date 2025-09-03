/**
 * WooCommerce JS - Bootscore v6.3.1
 */


jQuery(function ($) {

  // Single-product Tabs
  // First tab active by default
  $('.wc-tabs .nav-item:first-child a').addClass('active');
  $('.wc-tab').hide().first().show();

  // Function to switch tabs (used by both click and deep-link)
  function switchTab($tab) {
    var $tabs_wrapper = $tab.closest('.wc-tabs-wrapper, .woocommerce-tabs');

    // Remove active classes and hide all tab panels
    $tabs_wrapper.find('.wc-tabs li a').removeClass('active');
    $tabs_wrapper.find('.wc-tab').hide();

    // Activate clicked tab and show corresponding panel
    $tab.addClass('active');
    $tabs_wrapper.find($tab.attr('href')).show();
  }

  // Tab switching on click
  $('body').on('click', '.wc-tabs li a', function (e) {
    e.preventDefault();
    switchTab($(this));
  });

  // --- Reviews tab deep-link support (WooCommerce 10.2) ---

  function openReviewsTab() {
    var $reviewTabLink = $('.wc-tabs li.reviews_tab a');
    if ($reviewTabLink.length) {
      switchTab($reviewTabLink); // directly switch tab (no click trigger)
      // Scroll into view using CSS scroll behavior
      document.querySelector('#tab-reviews').scrollIntoView({ behavior: 'smooth' });
    }
  }

  // On page load, if URL has #reviews or #tab-reviews hash
  if (window.location.hash === '#reviews' || window.location.hash === '#tab-reviews') {
    openReviewsTab();
  }

  // When clicking any link pointing to #reviews or #tab-reviews
  $('body').on('click', 'a[href="#reviews"], a[href="#tab-reviews"]', function(e) {
    e.preventDefault();
    openReviewsTab();
  });


  // WC Quantity Input
  // Quantity "plus" and "minus" buttons
  $(document.body).on('click', 'form.cart .plus, form.cart .minus,' + // for single product page
    'form.woocommerce-cart-form .plus, form.woocommerce-cart-form .minus', // legacy cart
    function () {
    let $qty = $(this).closest('.quantity').find('.qty'),
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
      let newVal = currentVal;
      if ($(this).is('.plus')) {
        newVal += parseInt(step);
        // As the value on page load is always 1 or the min value, the minus button will be disabled by default.
        // As soon as we add some quantity, we enable the minus button. On further clicks you could go on 0 but
        // would get notified immediatly by the html validation
        $(this).closest('.quantity').find('.minus').attr('disabled', false);
      } else {
        newVal -= parseInt(step);
      }

      $qty.val( newVal );
      $qty[0].reportValidity();

      // needed to enable refresh cart button on legacy cart page
      $qty.trigger('change');
  });
}); // jQuery End