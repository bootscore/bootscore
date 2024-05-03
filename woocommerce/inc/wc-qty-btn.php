<?php

/**
 * WooCommerce Quantity Buttons
 *
 * @package Bootscore 
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Add -/+ buttons to quantity-input.php
 */
add_action('woocommerce_before_quantity_input_field', 'bs_quantity_minus_button');

function bs_quantity_minus_button() {
  echo '<div class="input-group"><button type="button" class="minus input-group-text" data-type="minus">-</button>';
}

add_action('woocommerce_after_quantity_input_field', 'bs_quantity_plus_button');

function bs_quantity_plus_button() {
  echo '<button type="button" class="plus input-group-text" data-type="plus">+</button></div>';
}

add_action('wp_header', 'bs_quantity_plus_minus');


/**
 * This snippet can be used to force the quantity input to display in cases where
 * the input value cannot be changed (which is when the product is set to be sold
 * individually, or when the min and max values are identical).
 * See https://github.com/woocommerce/woocommerce/pull/36460
 * See https://github.com/bootscore/bootscore/pull/543/commits/57574c1fdd4ad10d296df70e51cf08b801fccb27
 */
add_filter('woocommerce_quantity_input_args', function (array $args) {

  if ($args['max_value'] < 1 || $args['min_value'] !== $args['max_value']) {
    remove_filter('woocommerce_quantity_input_type', 'change_quantity_input_type');
    return $args;
  }
  add_filter('woocommerce_quantity_input_type', 'change_quantity_input_type');
  $args['readonly'] = true;
  return $args;
});

function change_quantity_input_type() {
  return 'number';
}
