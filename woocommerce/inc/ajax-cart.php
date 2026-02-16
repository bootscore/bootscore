<?php

  /**
   * WooCommerce AJAX cart
   *
   * @package Bootscore
   * @version 6.4.0
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
  $is_enabled_ajax_cart = get_option('woocommerce_enable_ajax_add_to_cart', 'no');

  if ('no' !== $is_enabled_ajax_cart) {
    /**
     * JS for AJAX Add to Cart handling
     * https://aceplugins.com/ajax-add-to-cart-button-on-the-product-page-woocommerce/
     */

    /**
     * Handles the add to cart button adaptions in the loop
     * 1. Adds a btn-loader element to the add to cart button if the item is in stock
     * 2. Adds a filter to remove the "Add to cart" button if the item is out of stock
     * 3. Adds a badge with the woocommecerce out of stock message that is removeable via filter
     */
    function bootscore_add_btn_loader_to_loop($html, $product, $args) {

      $return_html = '';

      if ( $product->is_in_stock() && $product->supports('ajax_add_to_cart') && $product->is_purchasable() ) {
        // Implement btn-loader element directly on the loop items without js
        // Helps to decrease computing on client devices on page load and makes it cacheable
        $replacement = '$1<div class="btn-loader"><span class="spinner-border spinner-border-sm"></span></div>';
        $return_html .= preg_replace('/(<a href=\"[^?]*\?add-to-cart=[^>]+>)/', $replacement, $html);
      } else {
        // If the product is not purchasable, we return the original HTML without the btn-loader
        $return_html .= $html;
      }

      // Check if product is in stock
      if ( !$product->is_in_stock() &&
           apply_filters('bootscore/woocommerce/loop/show-out-of-stock-badge', true, $product) ) {

        $badge_html =
          '<p class="' . apply_filters('bootscore/class/woocommerce/loop/out-of-stock-badge', 'badge bg-secondary text-wrap mt-2 mb-0')  . '">' .
          esc_html(
            apply_filters('bootscore/woocommerce/loop/out-of-stock-badge-text',
              apply_filters('woocommerce_out_of_stock_message', __('This product is currently out of stock and unavailable.', 'woocommerce'))
              , $product )
          ) .
          '</p>';

        // If the product is out of stock, we add the option to return without the "read more" button
        if ( apply_filters('bootscore/woocommerce/loop/out-of-stock-remove-read-more', false) ) {
          return str_replace('mt-2', 'mt-auto', $badge_html);
        }

        $return_html .= $badge_html;
      }

      return $return_html;
    }

    add_filter('woocommerce_loop_add_to_cart_link', 'bootscore_add_btn_loader_to_loop', 12, 3);

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
     * Add fragments for notices using toasts
     */
    function bootscore_ajax_add_to_cart_add_fragments($fragments) {
      $all_notices   = WC()->session->get('wc_notices', []);
      $notice_types  = apply_filters('woocommerce_notice_types', ['error', 'success', 'notice']);

      ob_start();
      echo '<div class="toast-container ' . esc_attr(apply_filters('bootscore/class/woocommerce/toast-container', 'position-static w-100 bg-body-tertiary overflow-hidden px-3 py-0')) . '">';

      $atLeastOneNotice = false;
      foreach ($notice_types as $notice_type) {
        if (wc_notice_count($notice_type) > 0) {
          // Shadow and bg is hidden because that would produce a faulty looking appearance. Solvable with a rewrite of notice.php or adding special notice files for toasts.
          echo '<div class="toast bg-transparent shadow-none w-100 border-0 mb-0 ' . ($atLeastOneNotice ? 'mt-2' : 'mt-3') . '" role="alert" aria-live="assertive" aria-atomic="true">';
          echo '<div class="toast-body p-0">';
          wc_get_template("notices/{$notice_type}.php", [
            'notices' => array_filter($all_notices[$notice_type] ?? []),
          ]);
          echo '</div>';
          // Issue that that would only be visible on the "first" notice of each type as they are all shown in 1 toast. therefore hidden for now.
          // The only solution I can think of would be to rewrite the notice.php files.
          //echo '<button type="button" class="btn-close m-auto position-absolute end-0 top-0 p-2" data-bs-dismiss="toast" aria-label="Close"></button>';

          echo '</div>';

          $atLeastOneNotice = true;
        }
      }

      echo '</div>';

      $notices_html = '<div class="woocommerce-messages">' . ob_get_clean() . '</div>';
      $notices_html = preg_replace('/class="woocommerce-(message|info|error)"/', 'class="woocommerce-$1 m-0 mb-2"', $notices_html);

      if ($atLeastOneNotice) {
        // Add a hr element inside the last toast if at least one notice is present
        $notices_html = preg_replace('/(<\/div>\s*){3}$/', '<hr class="mb-0">$1$1$1', $notices_html);
      }

      $fragments['notices_html'] = $notices_html;
      $fragments['.woocommerce-messages'] = $notices_html;

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
    function bootscore_qty_update() {
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

          if($qty == 0){
            // set_quantity to 0 does also call the remove_cart_item function after some validation. but as that validation
            // is not needed and some plugins maybe don't check for 0 qty (godaddy analytics) we use remove_cart_item directly
            WC()->cart->remove_cart_item($cart_item_key);
          } else {
            WC()->cart->set_quantity($cart_item_key, $qty);
          }

          $items = WC()->cart->get_cart();

          // WPML seems to update the cart only after the quantity update.
          // Fix should also work for other plugins that update the cart after the quantity change.
          // If the original $cart_item_key is not in the cart after the quantity change, we return an error and refresh the cart fragments completely
          if ($qty != 0 && !isset($items[$cart_item_key])) {
            $response_item['force_fragments_refresh'] = true;
            wp_send_json_error($response_item);
          }

          $updated_item = [];
          if($qty != 0){
            $updated_item = $items[$cart_item_key];
            $response_item['fragments_replace'][".woocommerce-mini-cart-item[data-key=\"$cart_item_key\"]"] = retrieve_cart_item_html($cart_item_key, $updated_item);
            wc_add_notice(sprintf(__("Quantity of %s updated successfully", 'bootscore'), $updated_item['data']->get_name()), 'success');
          }

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
          $response_item['fragments_replace']['.cart-content-count'] = '<span class="cart-content-count ' . esc_attr(apply_filters('bootscore/class/header/cart/badge', 'position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-danger')) .'">' . WC()->cart->get_cart_contents_count() . '</span>';

		  // Even if the filter wouldn't be checked here it wouldn't change the output, BUT we save some calculations here at least.
		  if ( apply_filters( 'bootscore/woocommerce/header/show_cart_total', true ) ) {
			$response_item['fragments_replace']['.cart-toggler .woocommerce-Price-amount.amount'] = wp_kses_post(WC()->cart->get_cart_subtotal());
		  }

          // Filter to force complete fragments refresh on qty update if some incompatibility comes up.
          $response_item['force_fragments_refresh'] = apply_filters('bootscore/woocommerce/ajax-cart/update-qty/response/force-full-refresh', false, $response_item, $cart_item_key, $qty);

          $cart_content_after = WC()->cart->get_cart();

          // If the cart is empty, we need to force a full refresh.
          if( WC()->cart->get_cart_contents_count() == 0) $response_item['force_fragments_refresh'] = true;

          // Look for Items added to the cart while updating - E.g. if there is a bogo plugin active that adds a different item:
          $new_items = array_diff_key($cart_content_after, $cart_content_before);
          if( !empty( $new_items ) ) { $response_item['fragments_append'][".woocommerce-mini-cart.cart_list"] = ''; }
          foreach ($new_items as $key => $item) {
            $response_item['fragments_append'][".woocommerce-mini-cart.cart_list"] .= retrieve_cart_item_html($key, $item);
            wc_add_notice( sprintf(__( '&ldquo;%s&rdquo; has been added to your cart', 'woocommerce' ), $item['data']->get_title()), 'success' );
          }

          // Look for Items removed from the cart while updating - E.g. if there is a bogo plugin active and the conditions don't apply anymore:
          $removed_items = array_diff_key($cart_content_before, $cart_content_after);
          foreach ($removed_items as $key => $item) {
            $response_item['fragments_replace'][".woocommerce-mini-cart-item[data-key=\"$key\"]"] = '';
            $notice_type = 'notice';
            if($key == $cart_item_key){
              $notice_type = 'success';
            }
            wc_add_notice(sprintf(__( '&ldquo;%s&rdquo; has been removed from your cart', 'woocommerce' ), $item['data']->get_title()), $notice_type);
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

    function retrieve_cart_item_html($cart_item_key, $cart_item): string {
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

    add_action('bootscore_before_mini_cart_footer', 'add_wc_messages_container');
    function add_wc_messages_container() {
      // Extra container for easier handling and probable height animations
      echo '<div class="woocommerce-messages-container"><div class="woocommerce-messages"><div></div></div></div>'; // Inner div will be replaced by update and fragments function
    }

    add_filter('bootscore/woocommerce/ajax-cart/update-qty/validate-update', 'validate_qty_before_update', 10, 4);
    function validate_qty_before_update($passed, $cart_item_key, $cart_item, $qty) {
      // Decline the Ajax request if the product is not purchasable.
      $product = $cart_item['data'];
      if ($product) {
        $max_quantity = $product->get_max_purchase_quantity();
        // If quantity is unlimited the function will return -1
        // If the qty update function is used to remove an item this validation shouldn't be done.
        if ($qty > 0 && $max_quantity != -1 && $qty > $max_quantity) {
          wc_add_notice(sprintf(__('We only have %1$d of (%2$s) in stock.', 'bootscore'), $max_quantity, $product->get_name()), 'error');
          return false;
        }
      }
      return $passed;
    }

    add_filter('woocommerce_widget_cart_item_quantity', 'add_minicart_quantity_fields', 10, 3);
    function add_minicart_quantity_fields($html, $cart_item, $cart_item_key) {
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
