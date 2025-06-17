<?php

/**
 * WooCommerce enqueue scripts
 *
 * @package Bootscore
 * @version 6.2.0
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
  $modified_WooCommerceJS = date('YmdHi', filemtime(get_template_directory() . '/woocommerce/js/woocommerce.js'));

  // WooCommerce JS
  wp_enqueue_script('bootscore-wc-script', get_template_directory_uri() . '/woocommerce/js/woocommerce.js', array(), $modified_WooCommerceJS, true);

  if (apply_filters('bootscore/wc_ajax_login', true)) {
    $modified_ajaxLogin_JS = date('YmdHi', filemtime(get_template_directory() . '/woocommerce/js/ajax-login.js'));

    wp_enqueue_script('bootscore-ajax-login-script', get_template_directory_uri() . '/woocommerce/js/ajax-login.js', array(), $modified_ajaxLogin_JS, true);
    wp_set_script_translations('bootscore-ajax-login-script', 'bootscore', get_template_directory() . '/languages');
  }

  if ('no' !== get_option('woocommerce_enable_ajax_add_to_cart', 'no')) {

    $modified_ajaxCart_JS = date('YmdHi', filemtime(get_template_directory() . '/woocommerce/js/ajax-cart.js'));

    wp_enqueue_script('bootscore-ajax-cart-script', get_template_directory_uri() . '/woocommerce/js/ajax-cart.js', array('wc-add-to-cart'), $modified_ajaxCart_JS, true);
    //wp_set_script_translations('bootscore-ajax-cart-script', 'bootscore', get_template_directory() . '/languages');
  }

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}

add_action('wp_enqueue_scripts', 'bootscore_wc_scripts');
