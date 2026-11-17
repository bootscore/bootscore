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


/**
 * "Product" heading over the remove, thumbnail and name columns, so it starts at the very left
 *
 * The remove and thumbnail headings only have screen reader text. cart/cart.php has no filter for the table head,
 * so the template's output is buffered via the template part hooks and the three headings become one with colspan="3".
 */
add_action('woocommerce_before_template_part', function ($template_name) {
  if ('cart/cart.php' === $template_name) {
    ob_start();
  }
});

add_action('woocommerce_after_template_part', function ($template_name) {
  if ('cart/cart.php' === $template_name) {
    echo preg_replace(
      '/<th scope="col" class="product-remove">.*?<\/th>\s*<th scope="col" class="product-thumbnail">.*?<\/th>\s*<th scope="col" class="product-name">/s',
      '<th scope="col" class="product-name" colspan="3">',
      ob_get_clean(),
      1
    ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce template output
  }
});


/**
 * Proceed to checkout button with Bootstrap classes (pluggable, replaces cart/proceed-to-checkout-button.php)
 */
if (!function_exists('woocommerce_button_proceed_to_checkout')) {
  function woocommerce_button_proceed_to_checkout() {
    echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="checkout-button wc-forward ' . esc_attr(apply_filters('bootscore/class/woocommerce/cart/proceed-to-checkout', 'btn-solid theme-primary w-100')) . '">' . esc_html__('Proceed to checkout', 'woocommerce') . '</a>';
  }
}
