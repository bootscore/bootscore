<?php

/**
 * WooCommerce Forms
 *
 * @package Bootscore
 * @version 6.0.4
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Remove CSS and/or JS for Select2
 * https://gist.github.com/ontiuk/72f6ac868d397678fb8b31df2b22e32a?permalink_comment_id=5320497#gistcomment-5320497
 */
function bootscore_dequeue_styles_and_scripts_select2() {
  // Dequeue and deregister Select2 and related scripts/styles
  wp_dequeue_style('select2');
  wp_deregister_style('select2');

  wp_dequeue_script('selectWoo');
  wp_deregister_script('selectWoo');

  // Dequeue and deregister the country select script. Needed for blockified classic checkout.
  // Bug with stripe checkout payment. See:
  // Issue: https://github.com/bootscore/bootscore/issues/918
  // PR: https://github.com/bootscore/bootscore/pull/919
  // Revert: https://github.com/bootscore/bootscore/pull/926
  //wp_dequeue_script('wc-country-select');
  //wp_deregister_script('wc-country-select');
}
add_action('wp_enqueue_scripts', 'bootscore_dequeue_styles_and_scripts_select2', 100);


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
