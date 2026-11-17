<?php

/**
 * WooCommerce Order received (thank-you page)
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * "Thank you. Your order has been received." with icon and text wrapper like the other notices (wc-notices.php)
 */
add_filter('woocommerce_thankyou_order_received_text', function ($text) {
  return bootscore_wc_notice_markup($text, 'success');
}, 99);


/**
 * Failed order text with icon and text wrapper like the other notices
 *
 * WooCommerce (since 11.2) prints it with wp_kses_post(), which strips SVG. wp_kses() resolves the 'post' context
 * again for each tag, so SVG is allowed from here until the text is printed: the filter is removed again
 * on the payment method's thank-you hook, which runs right after it (and on woocommerce_thankyou as fallback).
 */
function bootscore_wc_allow_svg_in_post($tags, $context) {
  return 'post' === $context ? bootscore_kses_allowed_svg($tags) : $tags;
}

function bootscore_wc_disallow_svg_in_post() {
  remove_filter('wp_kses_allowed_html', 'bootscore_wc_allow_svg_in_post', 10);
}

add_filter('woocommerce_thankyou_order_failed_text', function ($text, $order = null) {
  if (!is_string($text)) {
    return $text;
  }

  add_filter('wp_kses_allowed_html', 'bootscore_wc_allow_svg_in_post', 10, 2);

  if ($order instanceof WC_Order) {
    add_action('woocommerce_thankyou_' . $order->get_payment_method(), 'bootscore_wc_disallow_svg_in_post', 0);
  }
  add_action('woocommerce_thankyou', 'bootscore_wc_disallow_svg_in_post', 0);

  return bootscore_wc_notice_markup($text, 'error');
}, 99, 2);
