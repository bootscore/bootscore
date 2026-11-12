<?php

/**
 * WooCommerce Sale Flash
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Sale badge in the loop and on the single product page
 *
 * Positioned on the card image (.card is position: relative). Keeps the WooCommerce class .onsale.
 */
function bootscore_wc_sale_flash($html, $post, $product) {
  $classes = apply_filters('bootscore/class/woocommerce/sale-flash', 'badge theme-danger position-absolute top-0 start-0 mt-5 ms-5');

  return '<span class="onsale ' . esc_attr($classes) . '">' . esc_html__('Sale!', 'woocommerce') . '</span>';
}
add_filter('woocommerce_sale_flash', 'bootscore_wc_sale_flash', 10, 3);
