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
  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/checkout/customer-details-col', 'lg:col-7 xl:col-8')) . '">';
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

  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/checkout/order-review-col', 'lg:col-5 xl:col-4')) . '">';
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


/**
 * Item quantity as badge without "×" in the checkout review, the order details (order received, my account) and pay for order
 *
 * "Quantity" stays for screen readers, which read the × before. Order quantities can contain markup for refunds
 * (<del>2</del> <ins>1</ins>), so the order filter keeps the inner HTML.
 */
function bootscore_wc_quantity_badge($quantity_html) {
  $classes = apply_filters('bootscore/class/woocommerce/item-quantity', 'badge badge-subtle theme-primary');

  return ' <span class="product-quantity ' . esc_attr($classes) . '"><span class="visually-hidden">' . esc_html__('Quantity', 'woocommerce') . ': </span>' . $quantity_html . '</span>';
}

add_filter('woocommerce_checkout_cart_item_quantity', function ($html, $cart_item) {
  return bootscore_wc_quantity_badge(esc_html($cart_item['quantity']));
}, 10, 2);

add_filter('woocommerce_order_item_quantity_html', function ($html) {
  if (preg_match('/<strong class="product-quantity">\s*(?:&times;|×)(?:&nbsp;|\s)*(.*?)<\/strong>/s', $html, $match)) {
    return bootscore_wc_quantity_badge($match[1]);
  }

  return $html;
});


/**
 * Short description below the product name in the checkout review, one truncated line
 *
 * Added after the quantity badge, so the badge stays next to the name. Variations use the parent's short description.
 * Turn off with add_filter('bootscore/woocommerce/checkout/item-excerpt', '__return_false').
 */
add_filter('woocommerce_checkout_cart_item_quantity', function ($html, $cart_item) {
  if (!apply_filters('bootscore/woocommerce/checkout/item-excerpt', true) || empty($cart_item['data'])) {
    return $html;
  }

  $product = $cart_item['data'];
  $excerpt = $product->get_short_description();

  if (!$excerpt && $product->get_parent_id()) {
    $parent  = wc_get_product($product->get_parent_id());
    $excerpt = $parent ? $parent->get_short_description() : '';
  }

  $excerpt = trim(wp_strip_all_tags($excerpt));

  if (!$excerpt) {
    return $html;
  }

  return $html . '<span class="product-excerpt ' . esc_attr(apply_filters('bootscore/class/woocommerce/checkout/item-excerpt', 'd-block fs-sm fg-secondary text-truncate')) . '">' . esc_html($excerpt) . '</span>';
}, 20, 2);


/**
 * Coupon remove link: trash icon instead of [Remove], like the mini cart
 *
 * WooCommerce runs the filtered HTML through wp_kses('post'), which strips SVG.
 * A one-time wp_kses_allowed_html filter allows the icon for this output only.
 * The link keeps its class, data-coupon and aria-label, so checkout.js still removes the coupon via AJAX.
 */
add_filter('woocommerce_cart_totals_coupon_html', function ($html) {
  $html = str_replace('>' . __('[Remove]', 'woocommerce') . '</a>', '>' . bootscore_icon('trash', false) . '</a>', $html, $count);

  if ($count) {
    $allow_svg = function ($tags, $context) use (&$allow_svg) {
      if ('post' !== $context) {
        return $tags;
      }
      remove_filter('wp_kses_allowed_html', $allow_svg, 10);
      return bootscore_kses_allowed_svg($tags);
    };
    add_filter('wp_kses_allowed_html', $allow_svg, 10, 2);
  }

  return $html;
}, 99);
