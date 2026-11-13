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

  // admin-ajax.php URL for ajax cart and ajax login, icons for the password toggle and notices built in JS
  // https://github.com/bootscore/bootscore/pull/711
  $thumb_cols = absint(apply_filters('woocommerce_product_thumbnails_columns', 4));

  wp_localize_script('bootscore', 'bootscoreTheme', [
    'ajaxurl' => admin_url('admin-ajax.php'),
    'icons'   => [
      'eye'      => bootscore_icon('eye', false),
      'eyeSlash' => bootscore_icon('eye-slash', false),
      'search'   => bootscore_icon('search', false),
      'star'     => bootscore_icon('star', false),
      'notice'   => [
        'success' => bootscore_wc_notice_icon('success'),
        'error'   => bootscore_wc_notice_icon('error'),
        'notice'  => bootscore_wc_notice_icon('notice'),
      ],
    ],
    // Classes for markup built by WooCommerce's single-product.js, set in woocommerce.js
    'classes' => [
      'galleryTrigger' => apply_filters('bootscore/class/woocommerce/single-product/gallery-trigger', 'btn-solid btn-sm btn-icon position-absolute top-0 end-0 z-2 mt-5 me-5'),
      'starRating'     => apply_filters('bootscore/class/woocommerce/star-rating', 'd-inline-flex gap-1 fg-body'),
      // Columns from woocommerce_product_thumbnails_columns, 2 more on large screens so the thumbnails stay small.
      // mt-0 replaces the negative row margin, so the space above the first row is the gutter, same as between the thumbnails
      'galleryThumbs'  => apply_filters('bootscore/class/woocommerce/single-product/gallery/thumbs', 'list-unstyled row row-cols-' . $thumb_cols . ' lg:row-cols-' . ($thumb_cols + 2) . ' g-3 mt-0'),
      'galleryThumb'   => apply_filters('bootscore/class/woocommerce/single-product/gallery/thumb', 'rounded w-100'),
    ],
  ]);

  // WooCommerce, after WooCommerce's own woocommerce.js (password toggle)
  // and on product pages after single-product.js, which builds the tabs, gallery trigger and review stars on DOM ready
  $deps = ['jquery', 'woocommerce', 'bootscore'];
  if (is_product()) {
    $deps[] = 'wc-single-product';
  }
  wp_enqueue_script('bootscore-woocommerce', get_template_directory_uri() . '/woocommerce/js/woocommerce.js', $deps, date('YmdHi', filemtime(__DIR__ . '/../js/woocommerce.js')), true);

  // Ajax login
  if (apply_filters('bootscore/wc_ajax_login', true)) {
    wp_enqueue_script('bootscore-ajax-login', get_template_directory_uri() . '/woocommerce/js/ajax-login.js', ['jquery', 'bootscore', 'bootscore-woocommerce'], date('YmdHi', filemtime(__DIR__ . '/../js/ajax-login.js')), true);
    wp_set_script_translations('bootscore-ajax-login', 'bootscore', get_template_directory() . '/languages');
  }

  // Ajax cart, same condition as wc-ajax-cart.php in wc-functions.php
  if (apply_filters('bootscore/load_ajax_cart', true) && 'yes' === get_option('woocommerce_enable_ajax_add_to_cart')) {
    wp_enqueue_script('bootscore-ajax-cart', get_template_directory_uri() . '/woocommerce/js/ajax-cart.js', ['wc-add-to-cart', 'bootscore'], date('YmdHi', filemtime(__DIR__ . '/../js/ajax-cart.js')), true);
  }
}
add_action('wp_enqueue_scripts', 'bootscore_wc_scripts');
