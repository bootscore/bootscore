<?php

/**
 * WooCommerce enqueue scripts
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Enqueue scripts
 */
function bootscore_wc_scripts() {

  // Cart fragments, not loaded by WooCommerce if the mini-cart widget is not in a widget area
  // https://developer.woocommerce.com/2023/05/24/woocommerce-7-8-beta-1-released/
  wp_enqueue_script('wc-cart-fragments');

  // admin-ajax.php URL for ajax cart and ajax login
  // https://github.com/bootscore/bootscore/pull/711
  wp_localize_script('bootscore', 'bootscoreTheme', ['ajaxurl' => admin_url('admin-ajax.php')]);

  // WooCommerce
  wp_enqueue_script('bootscore-woocommerce', get_template_directory_uri() . '/woocommerce/js/woocommerce.js', ['jquery'], date('YmdHi', filemtime(__DIR__ . '/../js/woocommerce.js')), true);

  // Ajax login
  if (apply_filters('bootscore/wc_ajax_login', true)) {
    wp_enqueue_script('bootscore-ajax-login', get_template_directory_uri() . '/woocommerce/js/ajax-login.js', ['jquery', 'bootscore'], date('YmdHi', filemtime(__DIR__ . '/../js/ajax-login.js')), true);
    wp_set_script_translations('bootscore-ajax-login', 'bootscore', get_template_directory() . '/languages');
  }

  // Ajax cart, same condition as wc-ajax-cart.php in wc-functions.php
  if (apply_filters('bootscore/load_ajax_cart', true) && 'yes' === get_option('woocommerce_enable_ajax_add_to_cart')) {
    wp_enqueue_script('bootscore-ajax-cart', get_template_directory_uri() . '/woocommerce/js/ajax-cart.js', ['wc-add-to-cart', 'bootscore'], date('YmdHi', filemtime(__DIR__ . '/../js/ajax-cart.js')), true);
  }
}
add_action('wp_enqueue_scripts', 'bootscore_wc_scripts');
