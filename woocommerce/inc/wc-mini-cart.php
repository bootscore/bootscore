<?php

/**
 * WooCommerce Mini Cart
 *
 * @package Bootscore 
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Mini cart Header
 */
if (!function_exists('bootscore_mini_cart')) :
  function bootscore_mini_cart($fragments) {

    ob_start();
    $count = WC()->cart->cart_contents_count; ?>
    <span class="cart-content">
      <?php if ($count > 0) { ?>
        <span class="cart-content-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= esc_html($count); ?></span><span class="cart-total ms-1 d-none d-md-inline"><?= WC()->cart->get_cart_subtotal(); ?></span>
      <?php } ?>
    </span>

    <?php
    $fragments['span.cart-content'] = ob_get_clean();

    return $fragments;
  }

  add_filter('woocommerce_add_to_cart_fragments', 'bootscore_mini_cart');

endif;


/**
 * Mini cart widget buttons
 */
remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10);
remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20);
add_action('woocommerce_widget_shopping_cart_buttons', 'bootscore_widget_shopping_cart_button_view_cart', 10);
add_action('woocommerce_widget_shopping_cart_buttons', 'bootscore_widget_shopping_cart_proceed_to_checkout', 20);

function bootscore_widget_shopping_cart_button_view_cart() {
  echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="btn btn-secondary d-block mb-2">' . esc_html__('View cart', 'woocommerce') . '</a>';
}

function bootscore_widget_shopping_cart_proceed_to_checkout() {
  echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="btn btn-primary d-block">' . esc_html__('Checkout', 'woocommerce') . '</a>';
}
