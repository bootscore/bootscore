<?php

/**
 * WooCommerce setup
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Theme support
 */
function bootscore_wc_theme_support() {
  add_theme_support('woocommerce');

  // Product gallery features
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'bootscore_wc_theme_support');


/**
 * Disable WooCommerce core CSS
 *
 * woocommerce-general, woocommerce-layout and woocommerce-smallscreen load without a cascade layer
 * and would override all Bootstrap layers, including utilities. Bootscore styles WooCommerce itself.
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');


/**
 * Template context for WooCommerce templates
 *
 * Shop, product archives and single products are rendered by WooCommerce templates,
 * which do not set a context. Cart, checkout and account use page.php ('page').
 */
function bootscore_wc_template_context() {
  if (is_woocommerce()) {
    $GLOBALS['bootscore_template_context'] = 'woocommerce';
  }
}
add_action('template_redirect', 'bootscore_wc_template_context');


/**
 * Content wrappers
 *
 * Replace WooCommerce wrappers with the page.php structure.
 * The sidebar is loaded in the wrapper end, so the WooCommerce sidebar is removed.
 *
 * @link https://developer.woocommerce.com/docs/theming/theme-development/classic-theme-developer-handbook/#using-hooks
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

function bootscore_wc_wrapper_start() {
  ?>
  <div id="content" class="site-content <?= esc_attr(apply_filters('bootscore/class/container', 'container', 'woocommerce')); ?> <?= esc_attr(apply_filters('bootscore/class/content/spacer', 'pt-7 pb-12', 'woocommerce')); ?>">
    <div id="primary" class="content-area">

      <?php do_action('bootscore_after_primary_open', 'woocommerce'); ?>

      <div class="row">
        <div class="<?= esc_attr(apply_filters('bootscore/class/main/col', 'col')); ?>">

          <main id="main" class="site-main">
  <?php
}
add_action('woocommerce_before_main_content', 'bootscore_wc_wrapper_start', 10);

function bootscore_wc_wrapper_end() {
  ?>
          </main>

        </div>
        <?php get_sidebar(); ?>
      </div>

    </div>
  </div>
  <?php
}
add_action('woocommerce_after_main_content', 'bootscore_wc_wrapper_end', 10);
