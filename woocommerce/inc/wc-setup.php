<?php

/**
 * WooCommerce Support
 *
 * @package Bootscore 
 * @version 5.3.3
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Woocommerce Support
 */
function bootscore_add_woocommerce_support() {
  add_theme_support('woocommerce');
}

add_action('after_setup_theme', 'bootscore_add_woocommerce_support');
// Woocommerce Templates END


/**
 * Enable Lightbox
 */
add_action('after_setup_theme', 'bootscore_wc_lightbox');

function bootscore_wc_lightbox() {
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');
}
