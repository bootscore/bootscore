<?php

/**
 * WooCommerce forms
 *
 * Form fields are styled in SCSS (bootscore-woocommerce/_wc-forms.scss), except where a filter is available.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Remove Select2 (selectWoo), use native selects instead
 *
 * @link https://gist.github.com/ontiuk/72f6ac868d397678fb8b31df2b22e32a?permalink_comment_id=5320497#gistcomment-5320497
 */
function bootscore_wc_remove_select2() {
  wp_dequeue_style('select2');
  wp_deregister_style('select2');

  wp_dequeue_script('selectWoo');
  wp_deregister_script('selectWoo');
}
add_action('wp_enqueue_scripts', 'bootscore_wc_remove_select2', 100);


/**
 * Quantity input, also used in the mini cart outside .woocommerce
 */
function bootscore_wc_quantity_input_classes($classes) {
  $classes[] = 'form-control';
  return $classes;
}
add_filter('woocommerce_quantity_input_classes', 'bootscore_wc_quantity_input_classes');
