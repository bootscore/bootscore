<?php

/**
 * WooCommerce Stock Badge
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Stock badge on the single product page
 *
 * Change classes via filter, 2nd argument is the WooCommerce availability class
 * ('in-stock', 'available-on-backorder', 'out-of-stock').
 */
function bootscore_wc_stock_badge($html, $product) {
  $availability = $product->get_availability();
  $class        = $availability['class'];
  $message      = $availability['availability'];

  if ('' === $message) {
    return $html;
  }

  $themes = array(
    'in-stock'               => 'theme-success',
    'available-on-backorder' => 'theme-warning',
  );

  $classes = apply_filters('bootscore/class/woocommerce/stock-badge', 'badge ' . ($themes[$class] ?? 'theme-danger') . ' mb-5', $class);

  return '<p class="stock ' . esc_attr($class) . '"><span class="' . esc_attr($classes) . '">' . esc_html($message) . '</span></p>';
}
add_filter('woocommerce_get_stock_html', 'bootscore_wc_stock_badge', 10, 2);
