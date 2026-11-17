<?php

/**
 * WooCommerce Cart page
 *
 * Loaded when the cart page is enabled (bootscore/skip_cart false or AJAX add to cart off), see wc-functions.php.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Columns: cart table | cart totals, cross-sells full width below
 *
 * The row opens after the notices (woocommerce_output_all_notices at priority 10),
 * the totals column at woocommerce_before_cart_collaterals, both close at woocommerce_after_cart.
 */
add_action('woocommerce_before_cart', function () {
  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/cart/row', 'row g-7')) . '">';
  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/cart/table-col', 'lg:col-7 xl:col-8')) . '">';
}, 20);

add_action('woocommerce_before_cart_collaterals', function () {
  echo '</div>';
  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/cart/totals-col', 'lg:col-5 xl:col-4')) . '">';
}, 99);

add_action('woocommerce_after_cart', function () {
  echo '</div>';
  echo '</div>';
}, 1);


/**
 * Cross-sells below the columns instead of next to the totals
 */
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display', 20);
