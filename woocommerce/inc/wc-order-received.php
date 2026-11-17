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
 * WooCommerce prints it with wp_kses_post(), which strips SVG.
 * A one-time wp_kses_allowed_html filter allows the icon for this output only.
 */
add_filter('woocommerce_thankyou_order_failed_text', function ($text) {
  if (!is_string($text)) {
    return $text;
  }

  $allow_svg = function ($tags, $context) use (&$allow_svg) {
    if ('post' !== $context) {
      return $tags;
    }
    remove_filter('wp_kses_allowed_html', $allow_svg, 10);
    return bootscore_kses_allowed_svg($tags);
  };
  add_filter('wp_kses_allowed_html', $allow_svg, 10, 2);

  return bootscore_wc_notice_markup($text, 'error');
}, 99);
