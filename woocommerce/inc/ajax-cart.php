<?php

/**
 * WooCommerce AJAX cart
 *
 * @package Bootscore
 * @version 6.0.1
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
          // PART 1: Enable AJAX for single product pages
          $('form.cart').on('submit', function (e) {
            // Only apply to single product pages, not external products
            if (!$(this).closest('.product-type-external').length) {
              e.preventDefault();

              const form = $(this);
              var button = form.find('.single_add_to_cart_button');

              if (button.hasClass('disabled')) {
                return;
              }

              var formData = new FormData(form[0]);
              formData.append('add-to-cart', form.find('[name=add-to-cart]').val());

              // Add loading class
              button.addClass('loading');

              // Ajax action
              $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'ace_add_to_cart'),
                data: formData,
                type: 'POST',
                processData: false,
                contentType: false,
                complete: function (response) {
                  button.removeClass('loading');
                },
                success: function (response) {
                  if (response.error && response.product_url) {
                    window.location = response.product_url;
                    return;
                  }

                  // Redirect to cart option -- Not sure if necessary or it is right, because we have the "no cart" option in the theme?
                  if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
                    window.location = wc_add_to_cart_params.cart_url;
                    return;
                  }

                  // Trigger event so themes can refresh other areas
                  $(document.body).trigger('added_to_cart', [
                    response.fragments,
                    response.cart_hash,
                    button
                  ]);
                }
              });
            }
          });

          // Add loading spinner to add_to_cart_button // loop buttons are via a php filter in ajax-cart.php
          $('.single_add_to_cart_button').prepend('<div class="btn-loader"><span class="spinner-border spinner-border-sm"></span></div>');

        });
      </script>

      <script>
        jQuery(function ($) {
          $('a.ajax_add_to_cart[href*="?add-to-cart"]').on('click', function (e) {
            e.preventDefault();

            // Add new 'should_send_ajax_request.adding_to_cart' event to prevent standard WooCommerce Add To Cart AJAX request
            $(document.body).on('should_send_ajax_request.adding_to_cart', function (event, $button) {
              return false;
            });

            let button = $(this);
            let product_id = button.attr('href').split('=')[1];
            //parse as float
            let quantity = parseInt(button.attr('data-quantity')) || 1;
            let data = {
              "add-to-cart": product_id,
              "quantity": quantity,
            };

            // Interesting section here. it seems that the 'adding_to_cart' event removes the loading class from the button.
            // Therefore this approach is needed because it adds the loading after the removal. I'm not sure if this is the best way to do it.'
            // Function to add the 'loading' class to the a.ajax_add_to_cart button
            function addLoadingClass(e, fragments, cart_hash, button) {
              button.addClass('loading');
            }

            // Add new 'ajax_request_not_sent.adding_to_cart' event to trigger 'addLoadingClass' function
            $(document.body).on('ajax_request_not_sent.adding_to_cart', addLoadingClass);

            $(document.body).trigger('adding_to_cart', [button, data]);

            $.ajax({
              type: 'POST',
              url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'ace_add_to_cart'),
              data: data,
              complete: function (response) {
                button.addClass('added').removeClass('loading');

                // Remove 'should_send_ajax_request.adding_to_cart' and 'ajax_request_not_sent.adding_to_cart' events so they don't accumulate
                $(document.body).off('should_send_ajax_request.adding_to_cart');
                // Symptom of the addition of this event above..
                $(document.body).off('ajax_request_not_sent.adding_to_cart', addLoadingClass);
              },
              success: function (response) {
                if (response.error && response.product_url) { // I assume that it was a typo. if not please let me know
                  console.log(response.error);
                } else {
                  $(document.body).trigger('added_to_cart', [
                    response.fragments,
                    response.cart_hash,
                    button
                  ]);
                }
              }
            });
          });
        });
      </script>

      <script>
        jQuery(function ($) {
          // Chromium-specific fix for browser back button
          if (window.chrome) {
            $(window).on('pageshow', function (e) {
              if (e.originalEvent.persisted) {
                setTimeout(function () {
                  $(document.body).trigger('wc_fragment_refresh');
                }, 100);
              }
            });
          }

          // Open offcanvas when product is added to cart
          $(document.body).on('added_to_cart', function () {
            // This will throw an error if #offcanvas-cart doesn't exist
            $('#offcanvas-cart').offcanvas('show');
          });

          // Handle offcanvas closing - remove notices, so the cart is always empty on "reopening"
          $('#offcanvas-cart').on('hidden.bs.offcanvas', function () {
            $('#offcanvas-cart .woocommerce-message, #offcanvas-cart .woocommerce-error, #offcanvas-cart .woocommerce-info:not(.woocommerce-mini-cart__empty-message)').remove();
          });

          // That function is not "filtered" at the moment, but should have no impact if there are no toasts in the offcanvas cart.
          $(document.body).on('added_to_cart qty_updated qty_update_failed wc_fragments_refreshed removed_from_cart', function () {
            // Timeout is needed because "on" the added_to_cart event the fragments are not yet updated.
            // wc_fragments_refreshed seems to not be triggered because the added_to_cart event already has the fragments retrieved by the ajax request.
            setTimeout(function () {
              const toastElList = document.querySelectorAll('#offcanvas-cart .toast');
              if (toastElList && toastElList.length > 0) {
                const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl, {}).show());
              }
            }, 100); // Small delay to ensure fragments are processed
          });
        });
      </script>
    <?php
  }
  add_action('wp_footer', 'bootscore_product_page_ajax_add_to_cart_js');

  function bootscore_add_btn_loader_to_loop( $html ) {
    global $product;

    // Check if product is in stock
    if(!$product->is_in_stock()) {
      return 	'<p class="stock out-of-stock text-wrap mb-0 mt-auto">' .
        esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ) .
        '</p>';
    }

    // Implement Buttonloader directly without js
    $pattern = '/(<a href=\"\?add-to-cart=[^>]+>)/';
    $replacement = '$1<div class="btn-loader"><span class="spinner-border spinner-border-sm"></span></div>';

    return preg_replace($pattern, $replacement, $html);
  }
  add_filter( 'woocommerce_loop_add_to_cart_link', 'bootscore_add_btn_loader_to_loop', 12 );

function bootscore_ajax_add_to_cart_js() {
  ?>
  <script>
    jQuery(function ($) {
      // Handling of quantity buttons in single product form, has nothing to do with ajax cart.
      $(document.body).on('click', 'form.cart .plus, form.cart .minus,' + // for single product page
        'form.woocommerce-cart-form .plus, form.woocommerce-cart-form .minus', // legacy cart
        function () {
        let $qty = $(this).closest('.quantity').find('.qty'),
          currentVal = parseInt($qty.val()),
          max = parseInt($qty.attr('max')),
          min = parseInt($qty.attr('min')),
          step = $qty.attr('step');

        // Format values
        if (! currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
        if (max === '' || max === 'NaN') max = '';
        if (min === '' || min === 'NaN') min = 0;
        if (step === 'any' || step === '' || step === undefined || parseInt(step) === 'NaN'){ step = 1; }

        // Perform the Quantity Update for Increment.
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

    // Handling of quantity buttons in the ajax mini cart qty buttons
      $(document.body).on('click', '.item-quantity .plus, .item-quantity .minus', function () {
        let $qty = $(this).closest('.quantity').find('.qty'),
          currentVal = parseInt($qty.val()),
          max = parseInt($qty.attr('max')),
          min = parseInt($qty.attr('min')),
          step = $qty.attr('step'),
          nonce = $('input[name="bootscore_update_cart_nonce"]').val(),
          product_id = $(this).closest('.list-group-item').attr('data-bootscore_product_id');

        // Format values
        if (!currentVal || currentVal === '' || currentVal === 'NaN')
          currentVal = 0;
        if (max === '' || max === 'NaN') max = '';
        if (min === '' || min === 'NaN') min = 0;
        if (step === 'any' || step === '' || step === undefined || parseInt(step) === 'NaN'){ step = 1; }

        // Perform the Quantity Update for Increment.
        if ($(this).is('.plus')) {
          currentVal += parseInt(step);
        } else {
          currentVal -= parseInt(step);
        }

        let wrap = $($(this)).closest('.woocommerce-mini-cart-item');
        bootscore_quantity_update( wrap, currentVal, nonce, product_id, max, step );
      });

      jQuery(document).ready(function ($) {

        // Capture the old value on focus
        $('body').on('focus', '.item-quantity .quantity input', function () {
          $(this).data('prevValue', $(this).val());
        });

        // Trigger the function when the input is blurred
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

          let prevValue = input.data('prevValue');
          // If the new value is the same as the previous one, no need to re-render.
          if (prevValue === currentValue) {
            return false;
          }

          // Check if the submitted value is valid and return html error handling if not
          if (! this.checkValidity()){
            this.reportValidity();
            return false;
          }

          // Update the previous value data attribute.
          input.data('prevValue', currentValue);

          // Perform the Quantity Update.
          let wrap = $(input).closest('.woocommerce-mini-cart-item');
          bootscore_quantity_update(wrap, intValue, nonce, product_id, max);
        });
      });

      function bootscore_quantity_update(wrap, number, nonce, product_id, max=-1, step = 1) {
        let key = $(wrap).data('key');
        let data = {
          action: 'bootscore_qty_update',
          key: key,
          number: number,
          step: step,
          nonce: nonce,
          product_id: product_id,
          max: max,
        };

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
            let cart_res = res.data;
            if (res.success) {
              if (cart_res['force_fragments_refresh']) {
                $(document.body).trigger('wc_fragment_refresh');
                return;
              } else {
                console.log('in_update_js_sucess');
                $('#offcanvas-cart .cart-content span.woocommerce-Price-amount.amount').html(
                  cart_res['total']
                );

                $.each(cart_res['fragments_replace'], function (selector, content) {
                  $(selector).replaceWith(content);
                });

                $.each(cart_res['fragments_append'], function (selector, content) {
                  $(selector).append(content);
                });

                // Dispatch the custom event
                $(document.body).trigger('qty_updated', [res]);
              }
            } else {
              wrap.find('.blockUI').remove();

              $.each(cart_res['fragments_replace'], function (selector, content) {
                $(selector).replaceWith(content);
              });

              $.each(cart_res['fragments_append'], function (selector, content) {
                $(selector).append(content);
              });

              $(document.body).trigger('qty_update_failed', [res]);
            }
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

}

/**
 * Add fragments for notices
 */
function bootscore_ajax_add_to_cart_add_fragments($fragments) {
  $all_notices  = WC()->session->get('wc_notices', array());
  $notice_types = apply_filters('woocommerce_notice_types', array('error', 'success', 'notice'));

  $notices_html = '';
  if (apply_filters('bootscore_use_toasts_in_minicart', true)) {
    ob_start();
    echo '<div class="toast-container position-fixed top-0 end-0 p-3 px-4">';

    foreach ($notice_types as $notice_type) {
      if (wc_notice_count($notice_type) > 0) {
        // Shadow and bg is hidden because that would produce a faulty looking appearance. Solvable with a rewrite of notice.php or adding special notice files for toasts.
        echo '<div class="toast align-items-center bg-transparent shadow-none mb-2" role="alert" aria-live="assertive" aria-atomic="true">';
        echo '<div class="position-relative">';
        echo '<div class="toast-body p-0 row gap-2">';
        wc_get_template("notices/{$notice_type}.php", array(
          'notices' => array_filter($all_notices[$notice_type]),
        ));
        echo '</div>';
        // Issue that that would only be visible on the "first" notice of each type as they are all shonw in 1 toast. therefore hidden for now.
        // The only solution I can think of would be to rewrite the notice.php files.
        //echo '<button type="button" class="btn-close m-auto position-absolute end-0 top-0 p-2" data-bs-dismiss="toast" aria-label="Close"></button>';
        echo '</div>';
        echo '</div>';
      }
    }
    echo '</div>';

    $notices_html = '<div class="woocommerce-messages">' . ob_get_clean() . '</div>';
    // Set margin on woocommerce messages to 0. To not touch the original notice files we just do a "dirty" str_replace
    $notices_html = preg_replace('/class="woocommerce-(message|info|error)"/', 'class="woocommerce-$1 m-0 shadow"', $notices_html);

  } else {
    ob_start();
    foreach ($notice_types as $notice_type) {
      if (wc_notice_count($notice_type) > 0) {
        wc_get_template("notices/{$notice_type}.php", array(
          'notices' => array_filter($all_notices[$notice_type]),
        ));
      }
    }
    $notices_html = '<div class="woocommerce-messages">' . ob_get_clean() . '</div>';
  }
  $fragments['notices_html'] = $notices_html;
  $fragments['.woocommerce-messages'] = $fragments['notices_html'];

  wc_clear_notices();
  return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'bootscore_ajax_add_to_cart_add_fragments');
add_filter('bootscore_add_to_cart_fragments_on_qty_update', 'bootscore_ajax_add_to_cart_add_fragments');

/**
 * Stop redirecting after stock error
 */
add_filter('woocommerce_cart_redirect_after_error', '__return_false');

// Mini cart Ajax implementation.
add_action( 'wp_ajax_bootscore_qty_update', 'bootscore_qty_update' );
add_action( 'wp_ajax_nopriv_bootscore_qty_update', 'bootscore_qty_update' );
function bootscore_qty_update(){
  if (isset($_POST['number'])) {
    $cart_item_key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';
    $qty = isset($_POST['number']) ? sanitize_text_field(wp_unslash($_POST['number'])) : '';
    $max = isset($_POST['max']) ? sanitize_text_field(wp_unslash($_POST['max'])) : '';
    $step = isset($_POST['step']) ? sanitize_text_field(wp_unslash($_POST['step'])) : '';
    $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

    $product_id = isset($_POST['product_id']) ? intval(wp_unslash($_POST['product_id'])) : 0;
    $product = wc_get_product($product_id);

    // Make sure both values are defined before accessing them
    $response_item = array(
      'fragments_replace' => array(),
      'fragments_append' => array(),
    );

    // Validate nonce for security. Seems not to be completely DRY but I think it should be checked early here before going through other validations.
    if (!wp_verify_nonce($nonce, 'bootscore_update_cart')) {
      wc_add_notice(__('Nonce verification failed', 'bootscore'), 'error');
      $response_item['fragments_replace'] = apply_filters('bootscore_add_to_cart_fragments_on_qty_update', $response_item['fragments_replace'], 'error');
      wp_send_json_error($response_item);
    }

    if ($cart_item_key && $qty >= 0) {
      $response_item = apply_filters('bootscore_ajax_before_qty_update', $response_item, $cart_item_key);
      $cart_content_before = WC()->cart->get_cart();

      // First is the stock validation filter of woocommerce, to also implement third party checks here this is also implemented
      $passed_validation_wc_standard = apply_filters('woocommerce_update_cart_validation', true, $cart_item_key, $cart_content_before[$cart_item_key], $qty);
      // To not interfere with other areas of woocommerce we also implement our own filter here. Users can decide if they want to apply the filter everywhere or just on the qty update in the mini cart
      $passed_validation_qty_update = apply_filters('bootscore_update_cart_validation', true, $cart_item_key, $cart_content_before[$cart_item_key], $qty);

      // If one check fails. we return and append all the errors in the session to the notices.
      if (!($passed_validation_wc_standard && $passed_validation_qty_update)) {
        $response_item['fragments_replace'] = apply_filters('bootscore_add_to_cart_fragments_on_qty_update', $response_item['fragments_replace'], 'error');
        wp_send_json_error($response_item);
      }

      // Seems to nearly always return true, There is no validation as far as I understand. Therefore the checks happen before
      WC()->cart->set_quantity($cart_item_key, $qty);

      $items = WC()->cart->get_cart();
      $updated_item = $items[$cart_item_key];

      $response_item['fragments_replace'][".woocommerce-mini-cart-item[data-key=\"$cart_item_key\"]"] = retrieve_cart_item_html($cart_item_key, $updated_item);

      // Get Mini Cart Footer Fragment
      ob_start();
      wc_get_template('cart/mini-cart-footer.php');
      $response_item['fragments_replace']['.widget_shopping_cart_content .cart-footer > div'] = trim(ob_get_clean());

      // Get mini-cart totals and amount for the cart symbol in the header
      if (get_option('woocommerce_tax_display_cart') === 'excl') {
        $cart_total = wc_price(WC()->cart->get_cart_contents_total());
      } else {
        $cart_total = WC()->cart->get_cart_total();
      }
      $response_item['fragments_replace']['.cart-toggler .woocommerce-Price-amount > bdi'] = $cart_total;
      $response_item['fragments_replace']['.cart-content-count'] = '<span class="cart-content-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">' . WC()->cart->get_cart_contents_count() . '</span>';

      wc_add_notice("Quantity of {$updated_item['data']->get_name()} updated successfully", 'success');

      // Filter to force complete fragments refresh on qty update if some incompatibility comes up.
      $response_item['force_fragments_refresh'] = apply_filters('bootscore_ajax_force_qty_update_refresh', false, $response_item, $cart_item_key, $qty);

      $cart_content_after = WC()->cart->get_cart();

      // Look for Items added to the cart while updating - E.g. if there is a bogo plugin active that adds a different item:
      $new_items = array_diff_key($cart_content_after, $cart_content_before);
      foreach ($new_items as $key => $item) {
        $response_item['fragments_append'][".woocommerce-mini-cart.cart_list"] .= retrieve_cart_item_html($key, $item);
        wc_add_notice("{$item['data']->get_title()} was added to the cart", 'success');
      }

      // Look for Items removed from the cart while updating - E.g. if there is a bogo plugin active and the conditions don't apply anymore:
      $removed_items = array_diff_key($cart_content_before, $cart_content_after);
      foreach ($removed_items as $key => $item) {
        $response_item['fragments_replace'][".woocommerce-mini-cart-item[data-key=\"$key\"]"] = '';
        wc_add_notice("{$item['data']->get_title()} was removed from cart", 'notice');
      }

      // Do something to the cart just before submission but before the session notifications are added
      $response_item = apply_filters('bootscore_ajax_after_qty_update', $response_item, $updated_item, $cart_item_key, $cart_content_after, $cart_content_before);

      // If a complete fragments refresh is forced, don't get the messages here, because it would remove them from the session.
      if (!$response_item['force_fragments_refresh']) {
        // Add notices to the fragments used
        $response_item['fragments_replace'] = apply_filters('bootscore_add_to_cart_fragments_on_qty_update', $response_item['fragments_replace'], 'success');
      }

      wp_send_json_success($response_item);
    } else {
      wc_add_notice(__('Invalid key or number', 'bootscore'), 'error');
      $response_item['fragments_replace'] = apply_filters('bootscore_add_to_cart_fragments_on_qty_update', $response_item['fragments_replace'], 'error');
      wp_send_json_error($response_item);
    }
  }
}

function retrieve_cart_item_html($cart_item_key, $cart_item): string
{
  ob_start();
  wc_get_template(
    'cart/mini-cart-item.php',
    array(
      'cart_item_key' => $cart_item_key,
      'cart_item' => $cart_item  // Note: Send the whole cart_item array
    )
  );
  return trim(ob_get_clean());
}

add_action('woocommerce_before_mini_cart_contents', 'add_wc_messages_container');
function add_wc_messages_container() {
  // Extra container for easier handling and probable height animations
  echo '<div class="woocommerce-messages-container"><div class="woocommerce-messages"><div></div></div></div>'; // Inner div will be replaced by update and fragments function
}

add_filter('bootscore_update_cart_validation', 'validate_qty_before_update', 10, 4);
function validate_qty_before_update($passed, $cart_item_key, $cart_item, $qty){
  // Decline the Ajax request if the product is not purchasable.
  $product = $cart_item['data'];
  if ($product) {
    $max_quantity = $product->get_max_purchase_quantity();
    // If quantity is unlimited the function will return -1
    if ($max_quantity != -1 && $qty > $max_quantity) {
      wc_add_notice(sprintf(__('We only have %1$d of (%2$s) in stock.', 'bootscore'), $max_quantity, $product->get_name()), 'error');
      return false;
    }
  }
  return $passed;
}

  // Show removal of products as well
add_action('woocommerce_remove_cart_item', function ($cart_item_key, $cart) {
  $removed = $cart->removed_cart_contents[$cart_item_key];
  $product_name = wc_get_product($removed['product_id'])->get_name();

  // Add your notice (WC will show these via AJAX too)
  wc_add_notice(sprintf(__('%s has been removed from your cart.', 'woocommerce'), $product_name), 'success');
}, 10, 2);

add_filter('woocommerce_widget_cart_item_quantity', 'add_minicart_quantity_fields', 10, 3);
function add_minicart_quantity_fields($html, $cart_item, $cart_item_key){
  $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
  if ($_product->is_sold_individually()) {
    $min_quantity = 1;
    $max_quantity = 1;
  } else {
    $min_quantity = 0;
    $max_quantity = $_product->get_max_purchase_quantity();
  }

  $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($cart_item['data']), $cart_item, $cart_item_key);
  wp_nonce_field('bootscore_update_cart', 'bootscore_update_cart_nonce');

  $output = '<span class="quantity">' . sprintf('<span class="qty_text">%s</span> &times; %s', $cart_item['quantity'], $product_price) . '</span>';
  $output .= woocommerce_quantity_input(
    array(
      'input_value' => $cart_item['quantity'],
      'max_value' => $max_quantity,
      'min_value' => $min_quantity,
    ),
    $cart_item['data'],
    false
  );

  return $output;
}


/**
 * Disable quantity input if only 1 product in stock left or sold individually
 *
 * https://github.com/bootscore/bootscore/issues/801
 * https://github.com/bootscore/bootscore/pull/823
 */
function bootscore_disable_quantity() {
  ?>
  <script>
  jQuery(function ($) {
    // Function to disable quantity inputs and buttons
    function disableQuantityInputs() {
      $('.quantity').each(function() {
        var $input = $(this).find('input[type="number"]');
        var max = $input.attr('max');

        if (max == 1) { // Check if max is 1
          $(this).find('button').attr('disabled', 'disabled');
          $input.attr('disabled', 'disabled');
        }
      });
    }

    // Trigger the function after AJAX completes
    $(document).ajaxComplete(function() {
      disableQuantityInputs();
    });

    // Optionally, call it on document ready to handle initial load cases
    disableQuantityInputs();
  });
  </script>
  <?php
}

add_action('wp_footer', 'bootscore_disable_quantity');