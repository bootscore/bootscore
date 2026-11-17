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
 * Product name in the review table wrapped in a span, truncated by default, so the quantity badge stays visible.
 * Only for the review table rows (not the mini cart on the checkout page), via its before/after cart contents hooks.
 */
function bootscore_wc_checkout_item_name($name) {
  return '<span class="product-title ' . esc_attr(apply_filters('bootscore/class/woocommerce/checkout/item-name', 'text-truncate')) . '">' . $name . '</span>';
}

add_action('woocommerce_review_order_before_cart_contents', function () {
  add_filter('woocommerce_cart_item_name', 'bootscore_wc_checkout_item_name', 99);
});

add_action('woocommerce_review_order_after_cart_contents', function () {
  remove_filter('woocommerce_cart_item_name', 'bootscore_wc_checkout_item_name', 99);
});


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
 * Excerpt below the product name in the checkout review, one truncated line
 *
 * Added after the quantity badge, so the badge stays next to the name.
 * Turn off with add_filter('bootscore/woocommerce/checkout/item-excerpt', '__return_false').
 */
add_filter('woocommerce_checkout_cart_item_quantity', function ($html, $cart_item) {
  if (!apply_filters('bootscore/woocommerce/checkout/item-excerpt', true) || empty($cart_item['data'])) {
    return $html;
  }

  // Like the mini cart and cart: the product's excerpt (short description, or the start of the description).
  // Variations use the parent's (product_id is the parent)
  $excerpt = trim(wp_strip_all_tags(get_the_excerpt($cart_item['product_id'] ?? $cart_item['data']->get_id())));

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


/**
 * Order notes textarea with 4 rows instead of 2
 *
 * Change or remove in a child theme with a later woocommerce_checkout_fields filter.
 */
add_filter('woocommerce_checkout_fields', function ($fields) {
  if (isset($fields['order']['order_comments']) && empty($fields['order']['order_comments']['custom_attributes']['rows'])) {
    $fields['order']['order_comments']['custom_attributes']['rows'] = 4;
  }
  return $fields;
});


/**
 * Payment area: card classes and a separator between the payment methods and the terms
 *
 * checkout/payment.php has a fixed class attribute and no hook between the methods and the terms,
 * so both are added to the output: on page load (woocommerce_checkout_payment() runs on
 * woocommerce_checkout_order_review at priority 20) and in the fragment WooCommerce sends with every checkout update.
 * The separator is only added when the methods list is shown (cart needs payment).
 * Also used for the pay for order page (wc-order-pay.php).
 * Return an empty string from bootscore/class/woocommerce/checkout/payment-separator to remove it.
 */
function bootscore_wc_checkout_payment_html($html) {
  $classes = trim(apply_filters('bootscore/class/woocommerce/checkout/payment', 'card card-body'));

  // Checkout: <div id="payment" class="woocommerce-checkout-payment">, pay for order page: <div id="payment">
  if ($classes) {
    $html = preg_replace_callback('/<div id="payment"(?: class="([^"]*)")?>/', function ($m) use ($classes) {
      return '<div id="payment" class="' . esc_attr(trim(($m[1] ?? '') . ' ' . $classes)) . '">';
    }, $html, 1);
  }

  $separator = trim(apply_filters('bootscore/class/woocommerce/checkout/payment-separator', 'my-3'));

  if ($separator) {
    $html = preg_replace('/(<\/ul>\s*)(<div class="form-row(?: place-order)?">)/', '$1<hr class="' . esc_attr($separator) . '">$2', $html, 1);
  }

  return $html;
}

add_action('woocommerce_checkout_order_review', function () {
  ob_start();
}, 19);

add_action('woocommerce_checkout_order_review', function () {
  echo bootscore_wc_checkout_payment_html(ob_get_clean()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce template output
}, 21);

add_filter('woocommerce_update_order_review_fragments', function ($fragments) {
  if (isset($fragments['.woocommerce-checkout-payment'])) {
    $fragments['.woocommerce-checkout-payment'] = bootscore_wc_checkout_payment_html($fragments['.woocommerce-checkout-payment']);
  }
  return $fragments;
});
