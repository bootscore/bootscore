<?php

/**
 * WooCommerce mini cart
 *
 * The mini cart uses the WooCommerce template (cart/mini-cart.php), no override.
 * Extra item content is added with filters, the layout is done in SCSS (_wc-mini-cart.scss).
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Mini cart Header
 */
if (!function_exists('bootscore_mini_cart')) :
  function bootscore_mini_cart($fragments) {

    ob_start();
    $count = WC()->cart->cart_contents_count; ?>
    <span class="cart-content">
      <?php if ($count > 0) { ?>
        <?php if(apply_filters('bootscore/woocommerce/header/show_cart_total', true)): ?>
          <span class="cart-content-count <?= esc_attr(apply_filters('bootscore/class/header/cart/badge', 'position-absolute top-0 start-100 translate-middle badge theme-danger')); ?>"><?= esc_html($count); ?></span><span class="cart-total <?= esc_attr(apply_filters('bootscore/class/header/cart/total', 'd-none md:d-inline')); ?>"><?= wp_kses_post(WC()->cart->get_cart_subtotal()); ?></span>
        <?php else: ?>
          <span class="cart-content-count <?= esc_attr(apply_filters('bootscore/class/header/cart/badge', 'position-absolute top-0 start-100 translate-middle badge theme-danger')); ?>"><?= esc_html($count); ?></span>
        <?php endif; ?>
      <?php } ?>
    </span>

    <?php
    $fragments['span.cart-content'] = ob_get_clean();

    return $fragments;
  }

  add_filter('woocommerce_add_to_cart_fragments', 'bootscore_mini_cart');

endif;


/**
 * Mini cart list classes and footer
 *
 * woocommerce_mini_cart() is pluggable and loaded by WooCommerce after the theme (after_setup_theme, priority 11).
 * The cart fragments call it without arguments, so the list classes are set here.
 *
 * WooCommerce has no hook around total and buttons. The footer wraps everything from the total
 * up to the end of woocommerce_widget_shopping_cart_after_buttons, incl. content from other plugins.
 */
if (!function_exists('woocommerce_mini_cart')) :
  function woocommerce_mini_cart($args = array()) {
    $args = wp_parse_args($args, array(
      'list_class' => apply_filters('bootscore/class/woocommerce/mini-cart/list', 'list-group list-group-flush'),
    ));

    ob_start();
    wc_get_template('cart/mini-cart.php', $args);
    $html = ob_get_clean();

    $marker = '<!-- bootscore-mini-cart-footer-end -->';
    $footer = '<div class="woocommerce-mini-cart__footer ' . esc_attr(apply_filters('bootscore/class/woocommerce/mini-cart/footer', 'drawer-footer flex-column align-items-stretch')) . '">';

    // Wrap only if both ends are found, otherwise keep the markup as it is
    $html = preg_replace('/(<p class="woocommerce-mini-cart__total[\s\S]*?)' . preg_quote($marker, '/') . '/', $footer . '$1</div>', $html, 1);

    echo str_replace($marker, '', $html); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
  }
endif;

// End marker for the footer, after all other after_buttons content
function bootscore_wc_mini_cart_footer_end() {
  echo '<!-- bootscore-mini-cart-footer-end -->';
}
add_action('woocommerce_widget_shopping_cart_after_buttons', 'bootscore_wc_mini_cart_footer_end', PHP_INT_MAX);


/**
 * Mini cart item classes
 */
function bootscore_wc_mini_cart_item_class($class, $cart_item, $cart_item_key) {
  return $class . ' ' . apply_filters('bootscore/class/woocommerce/mini-cart/item', 'list-group-item py-5');
}
add_filter('woocommerce_mini_cart_item_class', 'bootscore_wc_mini_cart_item_class', 10, 3);


/**
 * Mini cart item image and title in their own tags
 *
 * Both filters are also used on the cart and checkout pages, so they only change the output
 * between woocommerce_before_mini_cart_contents and woocommerce_mini_cart_contents (the item loop).
 * Image and title stay inside the WooCommerce product link.
 */
function bootscore_wc_mini_cart_loop_start() {
  $GLOBALS['bootscore_wc_in_mini_cart'] = true;
}
add_action('woocommerce_before_mini_cart_contents', 'bootscore_wc_mini_cart_loop_start');

function bootscore_wc_mini_cart_loop_end() {
  $GLOBALS['bootscore_wc_in_mini_cart'] = false;
}
add_action('woocommerce_mini_cart_contents', 'bootscore_wc_mini_cart_loop_end');

function bootscore_wc_mini_cart_item_thumbnail($thumbnail, $cart_item, $cart_item_key) {
  if (empty($GLOBALS['bootscore_wc_in_mini_cart'])) {
    return $thumbnail;
  }

  // Add classes to the <img> (product image or placeholder)
  $img_class = apply_filters('bootscore/class/woocommerce/mini-cart/image', 'img-fluid rounded align-text-top');
  $thumbnail = preg_replace('/<img([^>]*?)class="/', '<img$1class="' . esc_attr($img_class) . ' ', $thumbnail, 1);

  return '<span class="mini-cart-item-image">' . $thumbnail . '</span>';
}
add_filter('woocommerce_cart_item_thumbnail', 'bootscore_wc_mini_cart_item_thumbnail', 10, 3);

function bootscore_wc_mini_cart_item_name($name, $cart_item, $cart_item_key) {
  if (empty($GLOBALS['bootscore_wc_in_mini_cart'])) {
    return $name;
  }

  return '<span class="mini-cart-item-title ' . esc_attr(apply_filters('bootscore/class/woocommerce/mini-cart/title', 'fw-semibold')) . '">' . $name . '</span>';
}
add_filter('woocommerce_cart_item_name', 'bootscore_wc_mini_cart_item_name', 10, 3);


/**
 * Mini cart buttons
 *
 * Replace the WooCommerce buttons (class .button) with Bootstrap buttons.
 * Change classes via filter, 2nd argument is the button: 'view-cart' or 'checkout'.
 */
remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10);
add_action('woocommerce_widget_shopping_cart_buttons', 'bootscore_wc_mini_cart_button_view_cart', 10);

function bootscore_wc_mini_cart_button_view_cart() {
  echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="wc-forward ' . esc_attr(apply_filters('bootscore/class/woocommerce/mini-cart/button', 'btn-outline theme-primary', 'view-cart')) . '">' . esc_html__('View cart', 'woocommerce') . '</a>';
}

remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20);
add_action('woocommerce_widget_shopping_cart_buttons', 'bootscore_wc_mini_cart_button_checkout', 20);

function bootscore_wc_mini_cart_button_checkout() {
  echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="checkout wc-forward ' . esc_attr(apply_filters('bootscore/class/woocommerce/mini-cart/button', 'btn-solid theme-primary', 'checkout')) . '">' . esc_html__('Checkout', 'woocommerce') . '</a>';
}


/**
 * Remove link: trash icon instead of &times;
 */
function bootscore_wc_cart_item_remove_link($link, $cart_item_key) {
  return str_replace('>&times;</a>', '>' . bootscore_icon('trash', false) . '</a>', $link);
}
add_filter('woocommerce_cart_item_remove_link', 'bootscore_wc_cart_item_remove_link', 10, 2);


/**
 * Mini cart item: excerpt and stock badge before the quantity, line subtotal after it
 *
 * Priority 20, after the quantity input from wc-ajax-cart.php (priority 10), which replaces the default output.
 *
 * Disable via filter:
 * add_filter('bootscore/woocommerce/mini-cart/show_excerpt', '__return_false');
 * add_filter('bootscore/woocommerce/mini-cart/show_stock', '__return_false');
 */
function bootscore_wc_mini_cart_item_details($html, $cart_item, $cart_item_key) {
  $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
  $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

  $before = '';

  // Excerpt
  if (apply_filters('bootscore/woocommerce/mini-cart/show_excerpt', true)) {
    $excerpt = wp_strip_all_tags(get_the_excerpt($product_id));
    if ($excerpt) {
      $before .= '<p class="mini-cart-item-excerpt ' . esc_attr(apply_filters('bootscore/class/woocommerce/mini-cart/excerpt', 'fs-sm fg-secondary text-truncate mb-0')) . '">' . esc_html($excerpt) . '</p>';
    }
  }

  // Stock badge, sold individually or low stock (threshold from WooCommerce > Settings > Products > Inventory)
  if (apply_filters('bootscore/woocommerce/mini-cart/show_stock', true)) {
    $stock_quantity = $_product->get_stock_quantity();
    $stock_message  = '';

    if ($_product->is_sold_individually()) {
      $stock_message = __('Sold individually', 'woocommerce');
    } elseif ($stock_quantity > 0 && $stock_quantity <= wc_get_low_stock_amount($_product)) {
      /* translators: %s: stock amount */
      $stock_message = sprintf(__('Only %s left in stock', 'woocommerce'), $stock_quantity);
    }

    if ($stock_message) {
      $before .= '<span class="mini-cart-item-stock ' . esc_attr(apply_filters('bootscore/class/woocommerce/mini-cart/stock', 'badge theme-danger')) . '">' . esc_html($stock_message) . '</span>';
    }
  }

  // Line subtotal
  $subtotal = apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
  $after    = '<span class="mini-cart-item-subtotal ' . esc_attr(apply_filters('bootscore/class/woocommerce/mini-cart/subtotal', 'fw-semibold')) . '">' . $subtotal . '</span>';

  return $before . $html . $after;
}
add_filter('woocommerce_widget_cart_item_quantity', 'bootscore_wc_mini_cart_item_details', 20, 3);
