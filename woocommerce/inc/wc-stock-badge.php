<?php

/**
 * WooCommerce Stock Badge
 *
 * @package Bootscore 
 * @version 6.3.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Stock Badge in single-product page
 */
add_filter('woocommerce_get_stock_html', function($html, $product) {
  $availability = $product->get_availability();
  $class = $availability['class'];
  $message = $availability['availability'];

  // Define badge class based on stock status
  if ($class === 'in-stock') {
    $badge_class = 'stock-badge badge text-bg-success mb-3';
  } else {
    $badge_class = 'stock-badge badge text-bg-danger mb-3';
  }

  return '<span class="' . esc_attr($badge_class) . '">' . esc_html($message) . '</span>';
}, 10, 2);
