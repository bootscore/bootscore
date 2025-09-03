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



// Restore WooCommerce content wrappers properly
add_action('woocommerce_before_main_content', function() {
    echo '<div class="woocommerce-wrapper container pt-3 pb-5">';
}, 5);

add_action('woocommerce_after_main_content', function() {
    echo '</div>'; // close container
}, 50);

// Add product wrapper with .product class so WC JS can find gallery
add_action('woocommerce_before_single_product', function() {
    echo '<div id="product-' . get_the_ID() . '" class="product">';
}, 5);

add_action('woocommerce_after_single_product', function() {
    echo '</div>'; // close .product
}, 50);