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
 * Hide the mini cart "View cart" button
 */
remove_action('woocommerce_widget_shopping_cart_buttons', 'bootscore_wc_mini_cart_button_view_cart', 10);


/**
 * Skip cart page and redirect to checkout only if the cart has items.
 */
function bootscore_skip_cart_page_redirection_to_checkout() {
  // Check if it's the cart page and if the cart has items.
  if ( is_cart() && WC()->cart->get_cart_contents_count() > 0 ) {
    wp_redirect( wc_get_checkout_url() );
    exit; // Ensure that the script stops executing after redirect.
  }
}
add_action('template_redirect', 'bootscore_skip_cart_page_redirection_to_checkout');
