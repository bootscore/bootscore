/**
 * WooCommerce JS - Bootscore v7.0.0
 */


jQuery(function ($) {

  // Notice markup for notices built in JS, same as wc-notices.php: icon | text
  // type: 'success', 'error' or 'notice'
  window.bootscoreNotice = function (type, html) {
    const classes = { success: 'woocommerce-message', error: 'woocommerce-error', notice: 'woocommerce-info' };

    return '<div class="' + (classes[type] || classes.notice) + '" role="alert">' +
      (bootscoreTheme.icons.notice[type] || '') +
      '<span class="wc-notice-text">' + html + '</span></div>';
  };

  // Password show/hide toggle as Bootstrap form adorn
  // WooCommerce wraps password inputs in span.password-input and adds button.show-password-input (woocommerce.js),
  // in the account drawer this is done by ajax-login.js. There is no PHP hook, so the classes are added here.
  window.bootscorePasswordInputs = function (context) {
    $(context || document).find('.password-input:not(.form-adorn)').each(function () {
      const $wrapper = $(this);
      const $button = $wrapper.find('.show-password-input');

      $wrapper.addClass('form-control form-adorn form-adorn-end');
      $wrapper.find('input').addClass('form-ghost');
      $button.addClass('btn-icon btn-text theme-secondary').html(bootscoreTheme.icons.eye);
    });
  };

  bootscorePasswordInputs();

  // Swap the icon, WooCommerce toggles .display-password, the input type and the aria-label
  $(document.body).on('click', '.form-adorn .show-password-input', function () {
    $(this).html($(this).hasClass('display-password') ? bootscoreTheme.icons.eyeSlash : bootscoreTheme.icons.eye);
  });

  // Single-product Tabs
  // First item active
  $('.wc-tabs .nav-item:first-child a').addClass('active');
  $('.wc-tab').hide().first().show();

  // Tab switching
  $('body').on('click', '.wc-tabs li a', function (e) {
    e.preventDefault();

    var $tab = $(this);
    var $tabs_wrapper = $tab.closest('.wc-tabs-wrapper, .woocommerce-tabs');

    // Remove active classes and hide all tab panels
    $tabs_wrapper.find('.wc-tabs li a').removeClass('active');
    $tabs_wrapper.find('.wc-tab').hide();

    // Activate clicked tab and show corresponding panel
    $tab.addClass('active');
    $tabs_wrapper.find($tab.attr('href')).show();
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
      } else {
        newVal -= parseInt(step);
      }

      $qty.val( newVal );
      $qty[0].reportValidity();

      // needed to enable refresh cart button on legacy cart page
      $qty.trigger('change');
  });

  // Button states, same rules as in wc-qty-btn.php: minus disabled at the minimum or 1, plus at the maximum
  function bootscoreQtyButtons($input) {
    const val = parseFloat($input.val()) || 0;
    const min = parseFloat($input.attr('min')) || 0;
    const max = parseFloat($input.attr('max'));
    const $quantity = $input.closest('.quantity');

    $quantity.find('.minus').prop('disabled', val <= Math.max(min, 1));
    $quantity.find('.plus').prop('disabled', !Number.isNaN(max) && val >= max);
  }

  $(document.body).on('change', 'form.cart .qty, form.woocommerce-cart-form .qty', function () {
    bootscoreQtyButtons($(this));
  });

  // Variable products: WooCommerce only hides the quantity if the variation is sold individually.
  // Also hide it if only 1 can be bought, e.g. 1 left in stock.
  $(document.body).on('show_variation', 'form.variations_form', function (event, variation) {
    const max = parseFloat(variation.max_qty);
    const min = parseFloat(variation.min_qty) || 0;

    if (!Number.isNaN(max) && max <= Math.max(min, 1)) {
      $(this).find('.quantity').hide();
    }

    bootscoreQtyButtons($(this).find('.qty'));
  });
}); // jQuery End