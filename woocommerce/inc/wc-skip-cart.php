<?php

/**
 * WooCommerce Skip Cart Page
 *
 * @package Bootscore 
 * @version 6.0.1
 */


// Exit if accessed directly
defined('ABSPATH') || exit;



/**
 * Mini cart widget buttons
 */
remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20);
add_action('woocommerce_widget_shopping_cart_buttons', 'bootscore_widget_shopping_cart_proceed_to_checkout', 20);

function bootscore_widget_shopping_cart_proceed_to_checkout() {
  echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="btn btn-primary btn-lg d-block">' . esc_html__('Checkout', 'woocommerce') . '</a>';
}


/**
 * Hide WooCommerce Mini-Cart "View Cart" Button
 */
remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10);


/**
 * Skip cart page and redirecting to checkout.
 * Redirect empty checkout to shop page.
 */
function bootscore_skip_cart_page_redirection_to_checkout() {
  // Check if we are on the cart page and redirect to the checkout page
  if (is_cart()) {
    wp_redirect(wc_get_checkout_url());
    exit;
  }

  // Check if we are on the checkout page and if the cart is empty, redirect to the shop page
  if (is_checkout() && WC()->cart->is_empty()) {
    wp_redirect(wc_get_page_permalink('shop'));
    exit;
  }
}
add_action('template_redirect', 'bootscore_skip_cart_page_redirection_to_checkout');
