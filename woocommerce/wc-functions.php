<?php

/**
 * WooCommerce functions and definitions
 *
 * @package Bootscore
 * @version 6.4.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Load required files
 */
require_once get_template_directory() . '/woocommerce/inc/wc-breadcrumb.php';
require_once get_template_directory() . '/woocommerce/inc/wc-enqueue.php';
require_once get_template_directory() . '/woocommerce/inc/wc-forms.php';
require_once get_template_directory() . '/woocommerce/inc/wc-loop.php';
require_once get_template_directory() . '/woocommerce/inc/wc-mini-cart.php';
require_once get_template_directory() . '/woocommerce/inc/wc-qty-btn.php'; 
require_once get_template_directory() . '/woocommerce/inc/wc-redirects.php'; 
require_once get_template_directory() . '/woocommerce/inc/wc-result-count.php';
require_once get_template_directory() . '/woocommerce/inc/wc-sale-flash.php'; 
require_once get_template_directory() . '/woocommerce/inc/wc-stock-badge.php'; 
require_once get_template_directory() . '/woocommerce/inc/wc-setup.php'; 
require_once get_template_directory() . '/woocommerce/inc/wc-single-product-reviews.php';
require_once get_template_directory() . '/woocommerce/inc/wc-tabs.php';
require_once get_template_directory() . '/woocommerce/inc/wc-deprecated.php'; 

// Blocks
require_once get_template_directory() . '/woocommerce/inc/blocks/wc-block-widget-categories.php'; 


/**
 * Register Ajax Cart
 *
 * Enabled/Disabled based on the setting in backend under WooCommerce > Settings > Products > Enable AJAX add to cart buttons on archives.
 * Disable file via filter 
 * add_filter('bootscore/load_ajax_cart', '__return_false');
 */
function bootscore_register_ajax_cart() {
  if (apply_filters('bootscore/load_ajax_cart', true)) {
    $ajax_cart_en = 'yes' === get_option('woocommerce_enable_ajax_add_to_cart');
    if ($ajax_cart_en) {
      require_once get_template_directory() . '/woocommerce/inc/ajax-cart.php';
    }
  }
}
add_action('after_setup_theme', 'bootscore_register_ajax_cart');


/**
  * Skip cart page
  *
  * Disable cart page, "View Cart" button in mini-cart and redirect cart page to checkout
  *
  * Enable default cart page and buttons via filter
  * add_filter('bootscore/skip_cart', '__return_false');
  */
function bootscore_register_cart_file() {
  // Check the filter first
  $skip_cart_filter = apply_filters('bootscore/skip_cart', true);
  // Check the AJAX cart option
  $ajax_cart_en = 'yes' === get_option('woocommerce_enable_ajax_add_to_cart');

  if ($skip_cart_filter && $ajax_cart_en) {
    require_once get_template_directory() . '/woocommerce/inc/wc-skip-cart.php';
  } else {
    require_once get_template_directory() . '/woocommerce/inc/wc-cart.php';
  }
}
add_action('after_setup_theme', 'bootscore_register_cart_file');


/**
 * Creates an ajax login for woocommerce in the offcanvas user account.
 *
 * The implementation has 2 main goals
 * 1. Better user experience. Login happens immediately and the user can directly access his account
 * 2. Removes one of the last parts of the template that makes full page caching possible even for logged in users.
 *
 * Optout of ajax login: add_filter('bootscore/wc_ajax_login', '__return_false');
 */

function bootscore_register_ajax_login_file() {
  if (apply_filters('bootscore/wc_ajax_login', true)) {
    require_once get_template_directory() . '/woocommerce/inc/wc-ajax-login.php';
  }
}
add_action('after_setup_theme', 'bootscore_register_ajax_login_file');