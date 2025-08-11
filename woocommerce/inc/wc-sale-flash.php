<?php

/**
 * WooCommerce Sale Flash
 *
 * @package Bootscore 
 * @version 6.3.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Sale Badge in loop and single-product page
 */
add_filter('woocommerce_sale_flash', function($html, $post, $product) {
  // Use Bootstrap 5 badge class instead of WooCommerce default
  return '<span class="badge text-bg-danger position-absolute top-0 start-0 mt-3 ms-3 z-1">' . esc_html__('Sale!', 'woocommerce') . '</span>';
}, 10, 3);

