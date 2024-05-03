<?php

/**
 * WooCommerce enqueue scripts
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Enqueue scripts
 */
function bootscore_wc_scripts() {
  
  // Enable fragments script (disabled in WooCommerce 7.8.0 if mini-cart widget is not in a widget position)
  // See https://developer.woocommerce.com/2023/05/24/woocommerce-7-8-beta-1-released/
  wp_enqueue_script('wc-cart-fragments');
  
  // Localize the admin-ajax.php globally
  // See https://github.com/bootscore/bootscore/pull/711
  wp_localize_script( 'bootscore-script', 'bootscoreTheme', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

  // Get modification time. Enqueue files with modification date to prevent browser from loading cached scripts and styles when file content changes. 
  $modificated_WooCommerceJS = date('YmdHi', filemtime(get_template_directory() . '/woocommerce/js/woocommerce.js'));

  // WooCommerce JS
  wp_enqueue_script('bootscore-wc-script', get_template_directory_uri() . '/woocommerce/js/woocommerce.js', array(), $modificated_WooCommerceJS, true);

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}

add_action('wp_enqueue_scripts', 'bootscore_wc_scripts');
