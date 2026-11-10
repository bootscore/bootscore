<?php

  /**
   * WooCommerce AJAX cart
   *
   * @package Bootscore
   * @version 7.0.0
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
		$return_html .= preg_replace('/(<a\b[^>]*\bhref="[^"]*add-to-cart=[^"]*"[^>]*>)/', $replacement, $html);
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

    
    /**
     * Stop redirecting after stock error
     */
    add_filter('woocommerce_cart_redirect_after_error', '__return_false');

    /**
     * Mini cart quantity update
     *
     * Responds with the WooCommerce fragments (whole mini cart, header badge and notices),
     * the same as WooCommerce returns after add to cart or remove from cart.
     */
    add_action('wp_ajax_bootscore_qty_update', 'bootscore_qty_update');
    add_action('wp_ajax_nopriv_bootscore_qty_update', 'bootscore_qty_update');

    function bootscore_qty_update() {
      if (!isset($_POST['number'])) {
        return;
      }

      $cart_item_key = isset($_POST['key']) ? sanitize_text_field(wp_unslash($_POST['key'])) : '';
      $qty           = (int) wp_unslash($_POST['number']);
      $nonce         = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

      $response = array(
        'fragments'               => array(),
        'cart_hash'               => '',
        'force_fragments_refresh' => false,
      );

      // Nonce
      if (!wp_verify_nonce($nonce, 'bootscore_update_cart')) {
        wc_add_notice(__('Nonce verification failed', 'bootscore'), 'error');
        $response['fragments'] = bootscore_ajax_add_to_cart_add_fragments(array());
        wp_send_json_error($response);
      }

      $cart_content_before = WC()->cart->get_cart();

      // Key and quantity
      if (!$cart_item_key || !isset($cart_content_before[$cart_item_key]) || $qty < 0) {
        wc_add_notice(__('Invalid key or number', 'bootscore'), 'error');
        $response['fragments'] = bootscore_ajax_add_to_cart_add_fragments(array());
        wp_send_json_error($response);
      }

      $response = apply_filters('bootscore/woocommerce/ajax-cart/update-qty/response/before-update', $response, $cart_item_key);

      $cart_item = $cart_content_before[$cart_item_key];

      // WooCommerce stock validation, also used by third-party plugins
      $passed_validation_wc_standard = apply_filters('woocommerce_update_cart_validation', true, $cart_item_key, $cart_item, $qty);

      // WooCommerce 11.2+ Store API equivalent of the filter above, used by the Cart block. Extensions targeting the Cart block may only hook this one.
      // Only a WP_Error rejects the quantity, any other return value (including false) means accepted. Notices are not read by WC, so we add the error message ourselves.
      // Skipped if the legacy filter already failed (prevents duplicate messages from extensions hooking both) and on removal (qty 0), as removal is not a quantity change in the Store API.
      $passed_validation_store_api = true;
      if ($passed_validation_wc_standard && $qty > 0) {
        $store_api_validation = apply_filters('woocommerce_store_api_cart_item_quantity_validation', true, $qty, $cart_item['data'], $cart_item);
        if (is_wp_error($store_api_validation)) {
          wc_add_notice($store_api_validation->get_error_message(), 'error');
          $passed_validation_store_api = false;
        }
      }

      // Own filter, applies only to the quantity update in the mini cart
      $passed_validation_qty_update = apply_filters('bootscore/woocommerce/ajax-cart/update-qty/validate-update', true, $cart_item_key, $cart_item, $qty);

      // If one check fails, return the errors from the session as notices
      if (!($passed_validation_wc_standard && $passed_validation_store_api && $passed_validation_qty_update)) {
        $response['fragments'] = bootscore_ajax_add_to_cart_add_fragments(array());
        wp_send_json_error($response);
      }

      // Qty 0 removes the item. remove_cart_item() directly, because some plugins don't expect set_quantity(0).
      if (0 === $qty) {
        WC()->cart->remove_cart_item($cart_item_key);
      } else {
        WC()->cart->set_quantity($cart_item_key, $qty);
      }

      $cart_content_after = WC()->cart->get_cart();

      // Some plugins (e.g. WPML) replace the cart item after the update, so the key is gone. Refresh all fragments.
      if (0 !== $qty && !isset($cart_content_after[$cart_item_key])) {
        $response['force_fragments_refresh'] = true;
        wp_send_json_error($response);
      }

      $updated_item = 0 !== $qty ? $cart_content_after[$cart_item_key] : array();

      if ($updated_item) {
        /* translators: %s: product name */
        wc_add_notice(sprintf(__('Quantity of %s updated successfully', 'bootscore'), $updated_item['data']->get_name()), 'success');
      }

      // Items added during the update, e.g. by a BOGO plugin
      foreach (array_diff_key($cart_content_after, $cart_content_before) as $item) {
        /* translators: %s: product name */
        wc_add_notice(sprintf(__('&ldquo;%s&rdquo; has been added to your cart', 'woocommerce'), $item['data']->get_title()), 'success');
      }

      // Items removed during the update, including the item itself on qty 0
      foreach (array_diff_key($cart_content_before, $cart_content_after) as $key => $item) {
        /* translators: %s: product name */
        wc_add_notice(sprintf(__('&ldquo;%s&rdquo; has been removed from your cart', 'woocommerce'), $item['data']->get_title()), $key === $cart_item_key ? 'success' : 'notice');
      }

      $response = apply_filters('bootscore/woocommerce/ajax-cart/update-qty/response/after-update', $response, $updated_item, $cart_item_key, $cart_content_after, $cart_content_before);

      // Force a complete refresh via filter, or if the cart is empty
      $response['force_fragments_refresh'] = apply_filters('bootscore/woocommerce/ajax-cart/update-qty/response/force-full-refresh', $response['force_fragments_refresh'], $response, $cart_item_key, $qty) || WC()->cart->is_empty();

      // On a forced refresh, keep the notices in the session, the refresh shows them
      if (!$response['force_fragments_refresh']) {
        ob_start();
        woocommerce_mini_cart();
        $mini_cart = ob_get_clean();

        // Same fragments as WC_AJAX::get_refreshed_fragments(), incl. header badge and notices from woocommerce_add_to_cart_fragments
        $response['fragments'] = apply_filters('woocommerce_add_to_cart_fragments', array(
          'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
        ));
        $response['cart_hash'] = WC()->cart->get_cart_hash();
      }

      wp_send_json_success($response);
    }

    /**
     * Notices container in the cart drawer, outside the fragments
     */
    function bootscore_ajax_cart_messages_container() {
      echo '<div class="woocommerce-messages-container"><div class="woocommerce-messages"><div></div></div></div>'; // Inner div is replaced by the notices fragment
    }
    add_action('bootscore_before_drawer_cart', 'bootscore_ajax_cart_messages_container');

    /**
     * Nonce for the quantity update, once per mini cart
     */
    function bootscore_ajax_cart_nonce() {
      wp_nonce_field('bootscore_update_cart', 'bootscore_update_cart_nonce', false);
    }
    add_action('woocommerce_before_mini_cart', 'bootscore_ajax_cart_nonce');

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
  