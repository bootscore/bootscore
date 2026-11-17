<?php

/**
 * WooCommerce Pay for order page (checkout/form-pay.php)
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Pay for order button with the same classes as Place order (filter bootscore/class/woocommerce/checkout/place-order)
 */
add_filter('woocommerce_pay_order_button_html', 'bootscore_wc_order_button_html');


/**
 * Payment area like on the checkout: card classes and separator (bootscore_wc_checkout_payment_html() in wc-checkout.php)
 *
 * form-pay.php prints <div id="payment"> without class and has no hook after it,
 * so the template's output is buffered via the template part hooks.
 */
add_action('woocommerce_before_template_part', function ($template_name) {
  if ('checkout/form-pay.php' === $template_name) {
    ob_start();
  }
});

add_action('woocommerce_after_template_part', function ($template_name) {
  if ('checkout/form-pay.php' === $template_name) {
    echo bootscore_wc_checkout_payment_html(ob_get_clean()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce template output
  }
});
