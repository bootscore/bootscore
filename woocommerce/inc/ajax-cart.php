<?php

  /**
   * WooCommerce AJAX cart
   *
   * @package Bootscore
   * @version 6.2.0
   */


// Exit if accessed directly
  defined('ABSPATH') || exit;

  /**
   * Add the product name as data argument to Ajax add to cart buttons
   * We can add and use a title="" in /loop/add-to-cart.php as well instead using this filter
   * https://github.com/bootscore/bootscore/commit/598d1f1b4454f8826985a7c2210568bd5a814fe1
   */
  add_filter("woocommerce_loop_add_to_cart_args", "filter_wc_loop_add_to_cart_args", 20, 2);
  function filter_wc_loop_add_to_cart_args($args, $product)
  {
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
  $is_enabled_ajax_cart = get_option('woocommerce_enable_ajax_add_to_cart', 'no');

  if ('no' !== $is_enabled_ajax_cart) {
    /**
     * JS for AJAX Add to Cart handling
     * https://aceplugins.com/ajax-add-to-cart-button-on-the-product-page-woocommerce/
     */

    function bootscore_add_btn_loader_to_loop($html)
    {
      global $product;

      // Check if product is in stock
      if (!$product->is_in_stock()) {
        return '<p class="stock out-of-stock text-wrap mb-0 mt-auto">' .
          esc_html(apply_filters('woocommerce_out_of_stock_message', __('This product is currently out of stock and unavailable.', 'woocommerce'))) .
          '</p>';
      }

      // Implement btn-loader element directly on the loop items without js
      // Helps to decrease computing on client devices on page load and makes it cacheable
      $pattern = '/(<a href=\"\?add-to-cart=[^>]+>)/';
      $replacement = '$1<div class="btn-loader"><span class="spinner-border spinner-border-sm"></span></div>';

      return preg_replace($pattern, $replacement, $html);
    }

    add_filter('woocommerce_loop_add_to_cart_link', 'bootscore_add_btn_loader_to_loop', 12);

    /**
     * Add to cart handler
     */
    function bootscore_ajax_add_to_cart_handler()
    {
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
    function bootscore_ajax_add_to_cart_add_fragments($fragments)
    {
      $all_notices = WC()->session->get('wc_notices', array());
      $notice_types = apply_filters('woocommerce_notice_types', array('error', 'success', 'notice'));

      $notices_html = '';
      if (apply_filters('bootscore/woocommerce/notifications/mini-cart/use_toasts', false)) {
        ob_start();
        echo '<div class="toast-container ' . apply_filters('bootscore/class/woocommerce/toast-container', 'position-fixed top-0 end-0 p-3 px-4') . '">';

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
            // Issue that that would only be visible on the "first" notice of each type as they are all shown in 1 toast. therefore hidden for now.
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
    add_filter('bootscore/woocommerce/ajax-cart/update-qty/fragments/replace', 'bootscore_ajax_add_to_cart_add_fragments');

    /**
     * Stop redirecting after stock error
     */
    add_filter('woocommerce_cart_redirect_after_error', '__return_false');

// Mini cart Ajax implementation.
    add_action('wp_ajax_bootscore_qty_update', 'bootscore_qty_update');
    add_action('wp_ajax_nopriv_bootscore_qty_update', 'bootscore_qty_update');
    function bootscore_qty_update()
    {
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
          $response_item['fragments_replace'] = apply_filters('bootscore/woocommerce/ajax-cart/update-qty/fragments/replace', $response_item['fragments_replace'], 'error');
          wp_send_json_error($response_item);
        }

        if ($cart_item_key && $qty >= 0) {
          $response_item = apply_filters('bootscore/woocommerce/ajax-cart/update-qty/response/before-update', $response_item, $cart_item_key);
          $cart_content_before = WC()->cart->get_cart();

          // First is the stock validation filter of woocommerce, to also implement third party checks here this is also implemented
          $passed_validation_wc_standard = apply_filters('woocommerce_update_cart_validation', true, $cart_item_key, $cart_content_before[$cart_item_key], $qty);
          // To not interfere with other areas of woocommerce we also implement our own filter here. Users can decide if they want to apply the filter everywhere or just on the qty update in the mini cart
          $passed_validation_qty_update = apply_filters('bootscore/woocommerce/ajax-cart/update-qty/validate-update', true, $cart_item_key, $cart_content_before[$cart_item_key], $qty);

          // If one check fails. we return and append all the errors in the session to the notices.
          if (!($passed_validation_wc_standard && $passed_validation_qty_update)) {
            $response_item['fragments_replace'] = apply_filters('bootscore/woocommerce/ajax-cart/update-qty/fragments/replace', $response_item['fragments_replace'], 'error');
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

          if($qty == 0){
            wc_add_notice(sprintf(__('&ldquo;%s&rdquo; has been removed from your cart', 'woocommerce'), $updated_item['data']->get_name()), 'success');
          } else {
            wc_add_notice(sprintf(__("Quantity of %s updated successfully", 'bootscore'), $updated_item['data']->get_name()), 'success');
          }

          // Filter to force complete fragments refresh on qty update if some incompatibility comes up.
          $response_item['force_fragments_refresh'] = apply_filters('bootscore/woocommerce/ajax-cart/update-qty/response/force-full-refresh', false, $response_item, $cart_item_key, $qty);

          $cart_content_after = WC()->cart->get_cart();

          // Look for Items added to the cart while updating - E.g. if there is a bogo plugin active that adds a different item:
          $new_items = array_diff_key($cart_content_after, $cart_content_before);
          foreach ($new_items as $key => $item) {
            $response_item['fragments_append'][".woocommerce-mini-cart.cart_list"] .= retrieve_cart_item_html($key, $item);
            wc_add_notice( sprintf(__( '&ldquo;%s&rdquo; has been added to your cart', 'woocommerce' ), $item['data']->get_title()), 'success' );
          }

          // Look for Items removed from the cart while updating - E.g. if there is a bogo plugin active and the conditions don't apply anymore:
          $removed_items = array_diff_key($cart_content_before, $cart_content_after);
          foreach ($removed_items as $key => $item) {
            $response_item['fragments_replace'][".woocommerce-mini-cart-item[data-key=\"$key\"]"] = '';
            wc_add_notice(sprintf(__( '&ldquo;%s&rdquo; has been removed from your cart', 'woocommerce' ), $item['data']->get_title()), 'notice');
          }

          // Do something to the cart just before submission but before the session notifications are added
          $response_item = apply_filters('bootscore/woocommerce/ajax-cart/update-qty/response/after-update', $response_item, $updated_item, $cart_item_key, $cart_content_after, $cart_content_before);

          // If a complete fragments refresh is forced, don't get the messages here, because it would remove them from the session.
          if (!$response_item['force_fragments_refresh']) {
            // Add notices to the fragments used
            $response_item['fragments_replace'] = apply_filters('bootscore/woocommerce/ajax-cart/update-qty/fragments/replace', $response_item['fragments_replace'], 'success');
          }

          wp_send_json_success($response_item);
        } else {
          wc_add_notice(__('Invalid key or number', 'bootscore'), 'error');
          $response_item['fragments_replace'] = apply_filters('bootscore/woocommerce/ajax-cart/update-qty/fragments/replace', $response_item['fragments_replace'], 'error');
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
    function add_wc_messages_container()
    {
      // Extra container for easier handling and probable height animations
      echo '<div class="woocommerce-messages-container"><div class="woocommerce-messages"><div></div></div></div>'; // Inner div will be replaced by update and fragments function
    }

    add_filter('bootscore/woocommerce/ajax-cart/update-qty/validate-update', 'validate_qty_before_update', 10, 4);
    function validate_qty_before_update($passed, $cart_item_key, $cart_item, $qty)
    {
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

    add_filter('woocommerce_widget_cart_item_quantity', 'add_minicart_quantity_fields', 10, 3);
    function add_minicart_quantity_fields($html, $cart_item, $cart_item_key)
    {
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
  }
