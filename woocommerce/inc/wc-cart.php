<?php

 /**
  * WooCommerce Cart Page
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