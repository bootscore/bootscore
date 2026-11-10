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
