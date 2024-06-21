<?php

/**
 * WooCommerce AJAX cart
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


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
 * This option determine is ajax Cart enable/disable.
 * This option is handle by WC `woocommerce_enable_ajax_add_to_cart`
 * Can be found in wp_options table
*/
$is_enabled_ajax_cart = get_option( 'woocommerce_enable_ajax_add_to_cart', 'no' );

if ( 'no' !== $is_enabled_ajax_cart ) {
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

          // Add new 'should_send_ajax_request.adding_to_cart' event to prevent standard WooCommerce Add To Cart AJAX request
          $(document.body).on('should_send_ajax_request.adding_to_cart', function(event, $button) {
            return false;
          });

          // Function to add the 'loading' class to the a.ajax_add_to_cart button
          function addLoadingClass(e, fragments, cart_hash, button) {
            button.addClass('loading');
          }

          // Add new 'ajax_request_not_sent.adding_to_cart' event to trigger 'addLoadingClass' function
          $(document.body).on('ajax_request_not_sent.adding_to_cart', addLoadingClass);

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
            url: wc_add_to_cart_params.wc_ajax_url.replace( '%%endpoint%%', 'add_to_cart' ),
            data: data,
            complete: function (response) {
              $thisbutton.addClass('added').removeClass('loading');

              // Remove 'should_send_ajax_request.adding_to_cart' and 'ajax_request_not_sent.adding_to_cart' events so they don't accumulate
              $(document.body).off('should_send_ajax_request.adding_to_cart');
              $(document.body).off('ajax_request_not_sent.adding_to_cart', addLoadingClass);
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

function bootscore_ajax_add_to_cart_js() {
  ?>
  <script>
    jQuery(function ($) {
      // WC Quantity Input
      if (!String.prototype.getDecimals) {
        String.prototype.getDecimals = function () {
          var num = this,
            match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
          if (!match) {
            return 0;
          }
          return Math.max(0, (match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0));
        };
      }
      // $(document.body).on('click', '.item-quantity .quantity .plus, .item-quantity .quantity .minus', function () {
      $(document.body).on('click', '.plus, .minus', function () {
        var $qty = $(this).closest('.quantity').find('.qty'),
          currentVal = parseInt($qty.val()),
          max = parseInt($qty.attr('max')),
          min = parseInt($qty.attr('min')),
          step = $qty.attr('step'),
          nonce = $('input[name="bootscore_update_cart_nonce"]').val(),
          prevInputVal = jQuery(this).prev('input.qty'),
          is_single_product_decrease_cart = $('.single form.cart .minus'),
          product_id = $(this).closest('.list-group-item').attr('data-bootscore_product_id');

          // step = (step && !isNaN(parseInt(step))) ? parseInt(step) : 1;

        // Format values
        if (!currentVal || currentVal === '' || currentVal === 'NaN')
          currentVal = 0;
        if (max === '' || max === 'NaN') max = '';
        if (min === '' || min === 'NaN') min = 0;
        if ( step === 'any' || step === '' || step === undefined || parseInt(step) === 'NaN' ){
          step = 1;
        }

        // Change the value
        if ($(this).is('.plus')) {
          if ( !max || currentVal < max ) {
            prevInputVal.val(currentVal + step).change();
          }

          if (max && currentVal >= max) {
            $qty.val(max);
            $(this).attr('disabled', true);
          } else {
            $qty.val( (currentVal + parseInt(step)));
          }

          // Perform the Quantity Update for Increment.
          var currentValWithPlusStep = (currentVal + parseInt(step));

          bootscore_quantity_update_buttons( $(this), currentValWithPlusStep, step, nonce, product_id, max );
        } else {
          if (min && currentVal <= min) {
            $qty.val(min);
          } else if (currentVal > 0) {
            $qty.val( (currentVal - parseInt(step)) );
          }
          // Perform the Quantity Update for Decrement.
          var currentValWithMinusStep = (currentVal - parseInt(step));

          bootscore_quantity_update_buttons( $(this), currentValWithMinusStep, step, nonce, product_id, max );
        }

        // Trigger change event
        $qty.trigger('change');
      });

      // Implement the change event.
      function bootscore_quantity_update_buttons(el, number, step, nonce, product_id, max) {
        var wrap = $(el).closest('.woocommerce-mini-cart-item'),
          key = $(wrap).data('key'),
          data = {
            action: 'bootscore_qty_update',
            key: key,
            number: number,
            step: step,
            nonce: nonce,
            product_id: product_id,
            max: max
          };

        // Perform Ajax Call.
        bootscore_perform_ajax_call(wrap, data);
      }

      jQuery(document).ready(function ($) {
        // Trigger the function on page load.
        handleQuantityChange();

        // Trigger the function when the quantity input changes.
        $(document).on('change', '.quantity input[type="number"]', function () {
          handleQuantityChange();
        });

        // Trigger the function when the minus button is clicked.
        $(document).on('click', '.quantity .minus', function () {
          handleQuantityChange();
        });

        // Function to handle quantity change minus button enable/disable.
        function handleQuantityChange() {
          $('.mini_cart_item').each(function () {
            var quantityInput = $(this).find('.quantity input[type="number"]'),
              minusButton = $(this).find('.quantity .minus');

            if (parseInt(quantityInput.val()) <= 1) {
              minusButton.prop('disabled', true);
            } else {
              minusButton.prop('disabled', false);
            }
          });
        }

        // Trigger the function when the input is blurred.
        $('body').on('blur', '.item-quantity .quantity input', function (e) {
          e.preventDefault();
          
          var input = $(this),
            max = input.attr('max'),
            currentValue = input.val(),
            intValue = Math.max(Math.ceil(parseInt(currentValue)), 1),
            nonce = $('input[name="bootscore_update_cart_nonce"]').val(),
            product_id = $(this).closest('.list-group-item').attr('data-bootscore_product_id');

          input.val(intValue);

          if ( currentValue === '' || parseInt(currentValue) === '0' || intValue === NaN ) {
            input.val(1);
            intValue = 1;
          }

          // If the new value is the same as the previous one, no need to re-render.
          if (input.data('prevValue') === currentValue) {
            return false;
          }

          // Update the previous value data attribute.
          input.data('prevValue', currentValue);

          // Perform the Quantity Update.
          bootscore_quantity_update_input_blur(input, intValue, nonce, product_id, max);
        });

        // Update cart on input blur.
        function bootscore_quantity_update_input_blur(input, number, nonce, product_id, max) {
          var wrap = $(input).closest('.woocommerce-mini-cart-item'),
            key = $(wrap).data('key'),
            data = {
              action: 'bootscore_qty_update',
              key: key,
              number: number,
              nonce: nonce,
            product_id: product_id,
            max: NaN,
            };

          // Perform Ajax Call.
          bootscore_perform_ajax_call(wrap, data);
        }
      });

      // Handle Ajax Call.
      function bootscore_perform_ajax_call(wrap, data) {
        $.ajax({
          url: bootscoreTheme.ajaxurl,
          type: 'POST',
          data: data,
          beforeSend: function () {
            // Loader HTML
            let loader = `
                <div class="blockUI blockOverlay" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background-color: rgb(0, 0, 0); opacity: 0.6; cursor: wait; position: absolute;"></div>
                <div class="blockUI blockMsg blockElement" style="z-index: 1011; display: none; position: absolute; left: 199px; top: 52px;"></div>
                <div class="blockUI" style="display:none"></div>
              `;

            // Append the loader inside the item you click
            wrap.append(loader);
          },
          success: function (res) {
            setTimeout(function () {
              wrap.find('.blockUI').remove();
            }, 300);

            let cart_res = res.data;
            $('.cart-content span.woocommerce-Price-amount.amount').html(
              cart_res['total']
            );
            wrap.find('.bootscore-custom-render-total').html(
              cart_res['item_price']
            );
            wrap.find('span.qty_text').html(cart_res['item_qty']);
            $('.cart-content-count').html(cart_res['total_items']);
            $('.woocommerce-mini-cart__total.total .amount').html(
              cart_res['total']
            );
            $('.cart-footer .amount').html(cart_res['total']);
            $('.cart-content .cart-total').html(cart_res['total']);
          },
          error: function (jqXHR, textStatus, errorThrown) {
            setTimeout(function () {
              wrap.find('.blockUI').remove();
            }, 300);
            console.error(
              'AJAX request failed: ' + textStatus + ', ' + errorThrown
            );
          },
        });
      }

      // Handle the cart plus and minus buttons enable/disable by their stop property.
      jQuery(document).ready(function($) {
        function toggleButtonAndInputState(input) {
            var maxValue = parseFloat(input.attr('max'));
            var value = parseFloat(input.val());
            var plusBtn = input.siblings('.plus');

            // Check if the value is equal to the max value
            if (value === maxValue) {
                plusBtn.prop('disabled', true);
                input.prop('disabled', true);
            } else {
                plusBtn.prop('disabled', false);
                input.prop('disabled', false);
            }
        }

        // On document ready, toggle the state of the plus button and input field for each quantity input
        $('.quantity input[type="number"]').each(function() {
            toggleButtonAndInputState($(this));
        });

        // Event listener for input value change
        $('.quantity input[type="number"]').on('input', function() {
            toggleButtonAndInputState($(this));
        });
    });

    }); // jQuery End
  </script>
  <?php
}
  add_action('wp_footer', 'bootscore_ajax_add_to_cart_js');

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

} else {
  // Remove WC Core add to cart handler to prevent double-add
  // remove_action('wp_loaded', array('WC_Form_Handler', 'add_to_cart_action'), 20);
}

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

// Mini cart Ajax implementation.
add_action( 'wp_ajax_bootscore_qty_update', 'bootscore_qty_update' );
add_action( 'wp_ajax_nopriv_bootscore_qty_update', 'bootscore_qty_update' );
function bootscore_qty_update(){
  if ( isset( $_POST['number'] ) ) {
    $key    = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
    $number = isset( $_POST['number'] ) ? sanitize_text_field( wp_unslash( $_POST['number'] ) ) : '';
    $max    = isset( $_POST['max'] ) ? sanitize_text_field( wp_unslash( $_POST['max'] ) ) : '';
    $step   = isset( $_POST['step'] ) ? sanitize_text_field( wp_unslash( $_POST['step'] ) ) : '';
    $nonce  = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

    $product_id = isset( $_POST['product_id'] ) ? intval( wp_unslash( $_POST['product_id'] ) ) : 0;
    $product    = wc_get_product($product_id);

    // Decline the Ajax request if the product is not purchasable.
    if ($product && $max !== 'NaN' ) {
      $max_quantity = $product->get_max_purchase_quantity();
      if ( $number > $max_quantity ) {
        wp_send_json_error(
          array(
            'message' => __( 'Maximum quantity allowed is ' . $max_quantity . ' for this product', 'bootscore' ),
          )
        );
      }
    }

    // Validate nonce for security.
    if ( ! wp_verify_nonce( $nonce, 'bootscore_update_cart' ) ) {
      wp_send_json_error(
          array(
              'message' => __( 'Nonce verification failed', 'bootscore' ),
          )
      );
    }

    $woocommerce_tax_display_cart = get_option( 'woocommerce_tax_display_cart' );

    if ( $key && $number > 0 ) {
      WC()->cart->set_quantity( $key, $number );
      $items               = WC()->cart->get_cart();
      $cart                = [];
      $cart['count']       = WC()->cart->cart_contents_count;
      $cart['total_items'] = WC()->cart->get_cart_contents_count();
      $cart['total']       = WC()->cart->get_cart_total();
      $cart['item_price']  = WC()->cart->get_product_subtotal( $items[ $key ]['data'], $items[ $key ]['quantity']);
      $cart['item_qty']    = $items[ $key ]['quantity'];
      $cart['message']     = __( 'Quantity updated successfully', 'bootscore' );

      if ( $woocommerce_tax_display_cart === 'excl' ) {
        $cart['total'] = wc_price( WC()->cart->get_cart_contents_total() );
      }

      wp_send_json_success( $cart );
    } else {
        wp_send_json_error( __('Invalid key or number', 'bootscore') );
    }
  }
}

// Add quantity fields in Mini Cart.
add_filter('woocommerce_widget_cart_item_quantity', 'add_minicart_quantity_fields', 10, 3);
function add_minicart_quantity_fields($html, $cart_item, $cart_item_key) {
  $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
  if ( $_product->is_sold_individually() ) {
    $min_quantity = 1;
    $max_quantity = 1;
  } else {
    $min_quantity = 0;
    $max_quantity = $_product->get_max_purchase_quantity();
  } 

  $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $cart_item['data'] ), $cart_item, $cart_item_key );
  wp_nonce_field('bootscore_update_cart', 'bootscore_update_cart_nonce');

  $output = '<span class="quantity">' . sprintf( '<span class="qty_text">%s</span> &times; %s', $cart_item['quantity'], $product_price ) . '</span>';
  $output .= woocommerce_quantity_input(
    array(
      'input_value' => $cart_item['quantity'],
      'max_value'   => $max_quantity,
      'min_value'   => $min_quantity,
    ),
    $cart_item['data'],
    false
  );

  return $output;
}
