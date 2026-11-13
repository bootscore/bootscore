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
 * Stock badge on the single product page and in the grouped product list
 *
 * Change classes via filter, 2nd argument is the WooCommerce availability class
 * ('in-stock', 'available-on-backorder', 'out-of-stock'), 3rd the context ('single' or 'grouped').
 * In the grouped list the badge sits below the price in a table cell.
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

  $context = empty($GLOBALS['bootscore_wc_in_grouped_list']) ? 'single' : 'grouped';
  $spacing = 'grouped' === $context ? 'mt-2' : 'mb-5';
  $classes = apply_filters('bootscore/class/woocommerce/stock-badge', 'badge ' . ($themes[$class] ?? 'theme-danger') . ' ' . $spacing, $class, $context);

  return '<p class="stock ' . esc_attr($class) . ('grouped' === $context ? ' mb-0' : '') . '"><span class="' . esc_attr($classes) . '">' . esc_html($message) . '</span></p>';
}
add_filter('woocommerce_get_stock_html', 'bootscore_wc_stock_badge', 10, 2);
