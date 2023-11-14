<?php

/**
 * WooCommerce AJAX cart
 *
 * @package Bootscore
 * @version 5.3.4
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Add the product name as data argument to Ajax add to cart buttons
 * We can add and use a title="" in /loop/add-to-cart.php as well instead using this filter 
 * https://github.com/bootscore/bootscore/commit/598d1f1b4454f8826985a7c2210568bd5a814fe1
 */
add_filter("woocommerce_loop_add_to_cart_args", "filter_wc_loop_add_to_cart_args", 20, 2);
function filter_wc_loop_add_to_cart_args($args, $product) {
  if ($product->supports('ajax_add_to_cart') && $product->is_purchasable() && $product->is_in_stock()) {
    $args['attributes']['product-title'] = $product->get_name();
  }

  return $args;
}


/**
 * JS for AJAX Add to Cart handling 
 * https://aceplugins.com/ajax-add-to-cart-button-on-the-product-page-woocommerce/
 */
function bootscore_product_page_ajax_add_to_cart_js() {
  ?>
  <script>
    jQuery(function ($) {

      $('form.cart:not(.product-type-external form.cart)').on('submit', function (e) {
        e.preventDefault();
        $(document.body).trigger('adding_to_cart', []);

        const form = $(this);

        // Add loading class to button, hide in grouped products if product is out of stock
        $(this).find('.single_add_to_cart_button:not(.outofstock .single_add_to_cart_button)').addClass('loading');

        const formData = new FormData(form[0]);
        formData.append('add-to-cart', form.find('[name=add-to-cart]').val());

        // Ajax action.
        $.ajax({
          url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'ace_add_to_cart'),
          data: formData,
          type: 'POST',
          processData: false,
          contentType: false,
          complete: function (response) {
            response = response.responseJSON;

            if (!response) {
              return;
            }

            if (response.error && response.product_url) {
              window.location = response.product_url;
              return;
            }

            // Redirect to cart option
            if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
              window.location = wc_add_to_cart_params.cart_url;
              return;
            }

            const $thisbutton = form.find('.single_add_to_cart_button'); //

            // Trigger event so themes can refresh other areas.
            $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);

            // Remove existing notices
            $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();

            // Add new notices to offcanvas
            $('.woocommerce-mini-cart').prepend(response.fragments.notices_html);

            form.unblock();
          }
        });

      });

      $('a.ajax_add_to_cart').on('click', function (e) {

        e.preventDefault();

        $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();

        // Get product name from product-title=""
        let prod_title = '';
        prod_title = $(this).attr('product-title');

        $(document.body).trigger('adding_to_cart', []);

        let $thisbutton = $(this);
        let href = '';
        try {
          href = $thisbutton.prop('href').split('?')[1];

          if (href.indexOf('add-to-cart') === -1) return;
        } catch (err) {
          return;
        }

        let product_id = href.split('=')[1];

        let data = {
          product_id: product_id
        };

        $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

        $.ajax({

          type: 'post',
          url: wc_add_to_cart_params.wc_ajax_url.replace(
            '%%endpoint%%',
            'add_to_cart'
          ),
          data: data,

          complete: function (response) {
            $thisbutton.addClass('added').removeClass('loading');

          },

          success: function (response) {

            if (response.error & response.product_url) {
              console.log(response.error);

            } else {
              $(document.body).trigger('added_to_cart', [
                response.fragments,
                response.cart_hash,
                $thisbutton
              ]);

              console.log('Error-: ' + response.error);

              //Remove existing notices
              $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();

              let notice = '';
              if (response.error == true) {
                let message = `<?= sprintf(__('You cannot add another "%s" to your cart.', 'woocommerce'), '{{product_title}}') ?>`;
                notice = `<div class="woocommerce-error">${message.replace('{{product_title}}', prod_title)}</div>`;
              } else {
                let message = `<?= sprintf(_n('%s has been added to your cart.', '%s have been added to your cart.', 1, 'woocommerce'), '“{{product_title}}”') ?>`;
                notice = `<div class="woocommerce-message">${message.replace('{{product_title}}', prod_title)}</div>`;
              }

              // Add new notices to offcanvas
              setTimeout(function () {
                $('.woocommerce-mini-cart').prepend(notice);
              }, 100);

            }
          }

        });

      });


      // Add loading spinner to add_to_cart_button
      $('.single_add_to_cart_button, .ajax_add_to_cart').prepend('<div class="btn-loader"><span class="spinner-border spinner-border-sm"></span></div>');

      $('body').on('added_to_cart', function () {
        // Open offcanvas-cart when cart is loaded
        $('#offcanvas-cart').offcanvas('show');
      });

      // Hide alert in offcanvas-cart when offcanvas is closed
      $('#offcanvas-cart').on('hidden.bs.offcanvas', function () {
        $('#offcanvas-cart .woocommerce-message, #offcanvas-cart .woocommerce-error, #offcanvas-cart .woocommerce-info:not(.woocommerce-mini-cart__empty-message)').remove();
      });

      // Refresh ajax mini-cart on browser back button
      // https://github.com/woocommerce/woocommerce/issues/32454
      const isChromium = window.chrome;
      if (isChromium) {
        $(window).on('pageshow', function (e) {
          if (e.originalEvent.persisted) {
            setTimeout(function () {
              $(document.body).trigger('wc_fragment_refresh');
            }, 100);
          }
        });
      }

    });
  </script>
  <?php
}

add_action('wp_footer', 'bootscore_product_page_ajax_add_to_cart_js');


/**
 * Add to cart handler
 */
function bootscore_ajax_add_to_cart_handler() {
  WC_Form_Handler::add_to_cart_action();
  WC_AJAX::get_refreshed_fragments();
}

add_action('wc_ajax_ace_add_to_cart', 'bootscore_ajax_add_to_cart_handler');
add_action('wc_ajax_nopriv_ace_add_to_cart', 'bootscore_ajax_add_to_cart_handler');

// Remove WC Core add to cart handler to prevent double-add
remove_action('wp_loaded', array('WC_Form_Handler', 'add_to_cart_action'), 20);


/**
 * Add fragments for notices
 */
function bootscore_ajax_add_to_cart_add_fragments($fragments) {
  $all_notices  = WC()->session->get('wc_notices', array());
  $notice_types = apply_filters('woocommerce_notice_types', array('error', 'success', 'notice'));

  // Comment or delete this to hide Alerts
  ob_start();
  foreach ($notice_types as $notice_type) {
    if (wc_notice_count($notice_type) > 0) {
      wc_get_template("notices/{$notice_type}.php", array(
        'notices' => array_filter($all_notices[$notice_type]),
      ));
    }
  }
  $fragments['notices_html'] = ob_get_clean();
  // Comment or delete this to hide Alerts

  wc_clear_notices();

  return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'bootscore_ajax_add_to_cart_add_fragments');


/**
 * Stop redirecting after stock error
 */
add_filter('woocommerce_cart_redirect_after_error', '__return_false');
