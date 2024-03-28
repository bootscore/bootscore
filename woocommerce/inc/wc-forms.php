<?php

/**
 * WooCommerce Forms
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Remove CSS and/or JS for Select2 used by WooCommerce
 * https://gist.github.com/Willem-Siebe/c6d798ccba249d5bf080.
 */
add_action('wp_enqueue_scripts', 'bootscore_dequeue_stylesandscripts_select2', 100);

function bootscore_dequeue_stylesandscripts_select2() {
  if (class_exists('woocommerce')) {
    wp_dequeue_style('selectWoo');
    wp_deregister_style('selectWoo');

    wp_dequeue_script('selectWoo');
    wp_deregister_script('selectWoo');
  }
}


/**
 * Checkout form fields
 */
function bootscore_wc_bootstrap_form_field_args($args, $key, $value) {
  $args['input_class'][] = 'form-control';
  return $args;
}
add_filter('woocommerce_form_field_args', 'bootscore_wc_bootstrap_form_field_args', 10, 3);


/**
 * Quantity input
 */
function bootscore_quantity_input_classes( $classes ) {
  $classes[] = 'form-control';
  return $classes;
}
add_filter( 'woocommerce_quantity_input_classes', 'bootscore_quantity_input_classes' );
