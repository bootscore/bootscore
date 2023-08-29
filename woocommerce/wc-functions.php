<?php

/**
 * Woocommerce functions and definitions
 *
 * @package Bootscore
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Load required files
 */
require_once('inc/wc-breadcrumb.php');
require_once('inc/wc-forms.php');
require_once('inc/wc-loop.php');
require_once('inc/wc-mini-cart.php');
require_once('inc/wc-qty-btn.php'); 
require_once('inc/wc-redirects.php'); 
require_once('inc/wc-support.php'); 
require_once('inc/wc-deprecated.php'); 


















// Register Ajax Cart
if (!function_exists('register_ajax_cart')) :

  function register_ajax_cart() {
    require_once('ajax-cart/ajax-add-to-cart.php');
  }

  add_action('after_setup_theme', 'register_ajax_cart');

endif;
// Register Ajax Cart End


//Scripts and Styles
function wc_scripts() {
  
  // Enable fragments script (disabled in WooCommerce 7.8.0 if mini-cart widget is not in a widget position)
  // See https://developer.woocommerce.com/2023/05/24/woocommerce-7-8-beta-1-released/
  wp_enqueue_script('wc-cart-fragments');

  // Get modification time. Enqueue files with modification date to prevent browser from loading cached scripts and styles when file content changes. 
  $modificated_WooCommerceJS = date('YmdHi', filemtime(get_template_directory() . '/woocommerce/js/woocommerce.js'));

  // WooCommerce JS
  wp_enqueue_script('woocommerce-script', get_template_directory_uri() . '/woocommerce/js/woocommerce.js', array(), $modificated_WooCommerceJS, true);

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}

add_action('wp_enqueue_scripts', 'wc_scripts');
//Scripts and styles End

























