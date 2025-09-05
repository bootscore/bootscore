<?php

/**
 * WooCommerce Support
 *
 * @package Bootscore 
 * @version 7.0.0
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


/*
 * Use page.php instead woocommerce.php
 *
 * @link https://github.com/bootscore/bootscore/pull/1073
 * @link https://developer.woocommerce.com/docs/theming/theme-development/classic-theme-developer-handbook/#using-hooks
 */
// Remove default WooCommerce wrappers
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

// Remove default WooCommerce breadcrumb
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// Add Bootscore-compatible wrappers
add_action('woocommerce_before_main_content', 'bootscore_woocommerce_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'bootscore_woocommerce_wrapper_end', 10);

function bootscore_woocommerce_wrapper_start() {
  echo '<div id="content" class="site-content ' . apply_filters('bootscore/class/container', 'container', 'woocommerce') . ' ' . apply_filters('bootscore/class/content/spacer', 'pt-3 pb-5', 'woocommerce') . '">';
  echo '<div id="primary" class="content-area">';
  echo '<main id="main" class="site-main">';
  do_action('bootscore_after_primary_open', 'woocommerce');
  woocommerce_breadcrumb();
  echo '<div class="row">';
  echo '<div class="' . apply_filters('bootscore/class/main/col', 'col') . '">';
}

function bootscore_woocommerce_wrapper_end() {
  echo '</div>'; // .col
  get_sidebar();
  echo '</div>'; // .row
  echo '</main><!-- #main -->';
  echo '</div><!-- #primary -->';
  echo '</div><!-- #content -->';
}
