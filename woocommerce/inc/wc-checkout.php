<?php

/**
 * WooCommerce checkout (classic shortcode)
 *
 * Customer details and order review in two columns without a template override.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Row and customer details column
 *
 * The customer details hooks only fire when the checkout has fields, so the order review opens the row itself if needed.
 */
function bootscore_wc_checkout_row_open() {
  $GLOBALS['bootscore_wc_checkout_row'] = true;
  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/checkout/row', 'row g-7')) . '">';
}

function bootscore_wc_checkout_customer_details_open() {
  bootscore_wc_checkout_row_open();
  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/checkout/customer-details-col', 'lg:col-7')) . '">';
}
add_action('woocommerce_checkout_before_customer_details', 'bootscore_wc_checkout_customer_details_open', 1);

function bootscore_wc_checkout_customer_details_close() {
  echo '</div>';
}
add_action('woocommerce_checkout_after_customer_details', 'bootscore_wc_checkout_customer_details_close', 99);


/**
 * Order review column: heading, review table and payment
 */
function bootscore_wc_checkout_order_review_open() {
  if (empty($GLOBALS['bootscore_wc_checkout_row'])) {
    bootscore_wc_checkout_row_open();
  }

  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/checkout/order-review-col', 'lg:col-5')) . '">';
}
add_action('woocommerce_checkout_before_order_review_heading', 'bootscore_wc_checkout_order_review_open', 1);

function bootscore_wc_checkout_order_review_close() {
  echo '</div>'; // Column
  echo '</div>'; // Row
  $GLOBALS['bootscore_wc_checkout_row'] = false;
}
add_action('woocommerce_checkout_after_order_review', 'bootscore_wc_checkout_order_review_close', 99);


/**
 * Place order button
 *
 * Bootstrap classes instead of .button.alt. WooCommerce's checkout.js finds the button by #place_order
 * and swaps the text from data-value and data-order_button_text, so the attributes stay.
 */
function bootscore_wc_order_button_html($html) {
  return preg_replace('/class="button alt[^"]*"/', 'class="' . esc_attr(apply_filters('bootscore/class/woocommerce/checkout/place-order', 'btn-solid theme-primary w-100')) . '"', $html, 1);
}
add_filter('woocommerce_order_button_html', 'bootscore_wc_order_button_html');
