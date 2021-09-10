jQuery(function ($) {
  // Single add to cart button
  $('.single_add_to_cart_button:not(.product_type_variable):not(.product_type_external):not(.product_type_grouped)').attr('data-bs-toggle', 'offcanvas').attr('data-bs-target', '#offcanvas-cart');
  // Single add to cart button END

  // Add loading class to offcanvas-cart
  $('body').bind('adding_to_cart', function () {
    $('#offcanvas-cart').addClass('loading');
  });

  $('body').bind('added_to_cart', function () {
    $('#offcanvas-cart').removeClass('loading');
  });
  // Add loading class to offcanvas-cart END

  // Keep offcanvas-user open on reload if contains login or register error alert
  if ($('#offcanvas-user .alert').length > 0) {
    $('#offcanvas-user').offcanvas('show');
  }
  // Keep offcanvas-user open on reload if contains login or register error alert END

  // Review Checkbox Products
  $('.comment-form-cookies-consent').addClass('form-check');
  $('#wp-comment-cookies-consent').addClass('form-check-input');
  $('.comment-form-cookies-consent label').addClass('form-check-label');
  // Review Checkbox END

  // Checkout Form Validation
  $('body').on('blur change', '.form-row input', function () {
    $('.woocommerce form .form-row.woocommerce-validated .select2-container, .woocommerce form .form-row.woocommerce-validated input.input-text, .woocommerce form .form-row.woocommerce-validated select, .woocommerce form .form-row.woocommerce-validated .form-check-input[type=checkbox]').removeClass('is-invalid').addClass('is-valid');
    $('.woocommerce form .form-row.woocommerce-invalid .select2-container, .woocommerce form .form-row.woocommerce-invalid input.input-text, .woocommerce form .form-row.woocommerce-invalid select, .woocommerce form .form-row.woocommerce-invalid .form-check-input[type=checkbox]').removeClass('is-valid').addClass('is-invalid');
  });
  // Checkout Form Validation END
}); // jQuery End
