<?php

 /**
  * WooCommerce Enabled Cart Page
  *
  * @package Bootscore 
  * @version 6.0.0
  */


 // Exit if accessed directly
 defined('ABSPATH') || exit;


/**
 * Cross sell products column class
 */
function bootscore_cart_cross_sell_products($class) {
  if ( is_cart() ) {
    return 'col-6';
  }
  return $class;
}
add_filter('bootscore/class/woocommerce/col', 'bootscore_cart_cross_sell_products', 10, 2);


/**
  * Add class to WooCommerce Mini-Cart "View Cart" and Checkout" buttons
  */
 remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10);
 add_action('woocommerce_widget_shopping_cart_buttons', 'bootscore_widget_shopping_cart_button_view_cart', 10);

 function bootscore_widget_shopping_cart_button_view_cart() {
   echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="btn btn-secondary d-block mb-2">' . esc_html__('View cart', 'woocommerce') . '</a>';
 }

 remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20);
 add_action('woocommerce_widget_shopping_cart_buttons', 'bootscore_widget_shopping_cart_proceed_to_checkout', 20);

 function bootscore_widget_shopping_cart_proceed_to_checkout() {
   echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="btn btn-primary d-block">' . esc_html__('Checkout', 'woocommerce') . '</a>';
 }