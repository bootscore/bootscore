<?php

/**
 * WooCommerce notices
 *
 * Adds an icon to every notice and wraps the text, so the notice works as a flex alert:
 * icon | text | buttons. Styles in _wc-alerts.scss.
 *
 * Covers notices added with wc_add_notice() and printed with wc_print_notice().
 * Notices built in JS (ajax-login.js) use the same markup via bootscoreTheme.icons.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Icon per notice type
 *
 * Change icons via filter, e.g.:
 * add_filter('bootscore/woocommerce/notice/icon', function ($icon, $type) {
 *   return 'error' === $type ? 'triangle-exclamation' : $icon;
 * }, 10, 2);
 */
function bootscore_wc_notice_icon($type) {
  $icons = array(
    'success' => 'circle-check',
    'error'   => 'circle-exclamation',
    'notice'  => 'circle-info',
  );

  $icon = apply_filters('bootscore/woocommerce/notice/icon', $icons[$type] ?? 'circle-info', $type);

  return $icon ? bootscore_icon($icon, false) : '';
}


/**
 * Notice markup: icon, text in a span, buttons after the text
 *
 * The text gets its own wrapper, otherwise links and <strong> inside the text become separate flex items.
 * Buttons (e.g. Continue shopping, Browse products) stay direct children, so they can be placed at the end.
 */
function bootscore_wc_notice_markup($message, $type) {
  if (!is_string($message) || '' === trim($message) || false !== strpos($message, 'wc-notice-text')) {
    return $message;
  }

  // Move buttons out of the text
  $buttons = '';
  $message = preg_replace_callback(
    '/\s*<a\s[^>]*class="[^"]*\bbutton\b[^"]*"[^>]*>.*?<\/a>\s*/s',
    function ($matches) use (&$buttons) {
      $buttons .= trim($matches[0]);
      return ' ';
    },
    $message
  );

  return bootscore_wc_notice_icon($type) . '<span class="wc-notice-text">' . trim($message) . '</span>' . $buttons;
}

// Priority 20, after other filters changed the text, e.g. removing "View cart" (wc-mini-cart.php)
add_filter('woocommerce_add_success', function ($message) {
  return bootscore_wc_notice_markup($message, 'success');
}, 20);

add_filter('woocommerce_add_error', function ($message) {
  return bootscore_wc_notice_markup($message, 'error');
}, 20);

add_filter('woocommerce_add_notice', function ($message) {
  return bootscore_wc_notice_markup($message, 'notice');
}, 20);


/**
 * Allow the SVG icon in notices
 *
 * WooCommerce prints notices with wc_kses_notice(), which removes <svg> by default.
 */
function bootscore_wc_kses_notice_allowed_tags($allowed_tags) {
  return bootscore_kses_allowed_svg($allowed_tags);
}
add_filter('woocommerce_kses_notice_allowed_tags', 'bootscore_wc_kses_notice_allowed_tags');
