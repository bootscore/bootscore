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

  // Remove the "View cart" link WooCommerce adds after the add to cart button.
  // The cart page is skipped by default, and if enabled, the mini cart footer has a "View cart" button.
  $(document.body).on('added_to_cart', function (event, fragments, cartHash, $button) {
    if ($button && $button.length) {
      $button.siblings('.added_to_cart').remove();
    }
  });

  // Swap the icon, WooCommerce toggles .display-password, the input type and the aria-label
  $(document.body).on('click', '.form-adorn .show-password-input', function () {
    $(this).html($(this).hasClass('display-password') ? bootscoreTheme.icons.eyeSlash : bootscoreTheme.icons.eye);
  });

  // Single product tabs
  // WooCommerce (single-product.js) shows the panel and sets aria-selected, .nav-tabs needs .active.
  // Tabs that don't fit are copied into the Nav Overflow menu as .menu-item, .active is synced to both
  // and to the "More" toggle.
  function bootscoreTabsActive($tabs, id) {
    $tabs.find('a[aria-controls]').removeClass('active');
    $tabs.find('a[aria-controls="' + id + '"]').addClass('active');
    $tabs.find('.nav-overflow-toggle').toggleClass('active', $tabs.find('.menu-item[aria-controls="' + id + '"]').length > 0);
  }

  // Delegated jQuery handler, so it also runs when WooCommerce triggers a click (URL hash, review link)
  $(document.body).on('click', '.wc-tabs .nav-link[role="tab"]', function () {
    bootscoreTabsActive($(this).closest('.wc-tabs'), $(this).attr('aria-controls'));
  });

  // A copy in the menu clicks the original tab, so WooCommerce handles it as usual, then closes the menu.
  // Runs before WooCommerce's handler on body, stopPropagation keeps WooCommerce away from the copy.
  $('.wc-tabs').on('click', '.menu-item[aria-controls]', function (e) {
    e.preventDefault();
    e.stopPropagation();

    const $tabs = $(this).closest('.wc-tabs');
    $tabs.find('.nav-link[aria-controls="' + $(this).attr('aria-controls') + '"]').trigger('click');

    const toggle = $tabs.find('.nav-overflow-toggle')[0];
    if (toggle) {
      bootstrap.Menu.getInstance(toggle)?.hide();
    }
  });

  // Copies are menu items, not tabs. Nav Overflow rebuilds them on every resize and may have run before this script.
  // .active follows aria-selected, WooCommerce may have opened a tab from the URL hash before this script.
  $('.wc-tabs-wrapper [data-bs-toggle="nav-overflow"]').each(function () {
    const wrapper = this;

    function fixCopies() {
      const $tabs = $(wrapper).find('.wc-tabs');
      $tabs.find('.menu-item[aria-controls]').removeAttr('role aria-selected tabindex');
      bootscoreTabsActive($tabs, $tabs.find('.nav-link[aria-selected="true"]').attr('aria-controls'));
    }

    fixCopies();
    wrapper.addEventListener('overflow.bs.navoverflow', fixCopies);
  });

  // Gallery zoom trigger, built by WooCommerce (single-product.js) with an emoji.
  // aria-label stays, classes from wc-enqueue.php
  $('.woocommerce-product-gallery__trigger').addClass(bootscoreTheme.classes.galleryTrigger).html(bootscoreTheme.icons.search);

  // Gallery thumbnails, built by FlexSlider (single-product.js) as ol.flex-control-thumbs > li > img.
  // Columns from woocommerce_product_thumbnails_columns, active state (.flex-active) in SCSS
  $('.woocommerce-product-gallery .flex-control-thumbs').addClass(bootscoreTheme.classes.galleryThumbs).find('img').addClass(bootscoreTheme.classes.galleryThumb);

  // Review rating, WooCommerce (single-product.js) replaces the select with text links "1 of 5 stars" etc.
  // Each link gets a star icon, the text stays for screen readers. Hover and selected state in SCSS
  $('#respond p.stars > span').addClass(bootscoreTheme.classes.starRating).find('a').each(function () {
    const label = $('<span class="visually-hidden"></span>').text($(this).text());
    $(this).empty().append(bootscoreTheme.icons.star, label);
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