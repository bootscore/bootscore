<?php

/**
 * WooCommerce Support
 *
 * @package Bootscore 
 * @version 6.3.1
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * WooCommerce Support + Product Gallery Features
 */
function bootscore_add_woocommerce_support() {
  // Declare general WooCommerce support
  add_theme_support('woocommerce');

  // Declare product gallery support (needed since WC 10.2)
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'bootscore_add_woocommerce_support');
