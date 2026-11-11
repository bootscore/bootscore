<?php

/**
 * WooCommerce quantity buttons
 *
 * Minus and plus buttons around the quantity input on single product, cart and mini cart.
 * The input is removed if the quantity can't be changed.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Remove the quantity input if the quantity can't be changed
 *
 * Sold individually, only 1 left in stock, out of stock (item in the cart) or min = max.
 * The input stays a readonly number input with class .qty-fixed, so forms still send it and the
 * screen reader label still points to a labelable element (a hidden input is not labelable).
 * The wrapper is hidden in SCSS (_wc-forms.scss). No buttons are added.
 * Variable products are handled in woocommerce.js, the variation is only known in the browser.
 */
function bootscore_wc_quantity_input_args($args, $product = null) {
  $min     = isset($args['min_value']) && is_numeric($args['min_value']) ? (float) $args['min_value'] : 0;
  $max     = isset($args['max_value']) && is_numeric($args['max_value']) ? (float) $args['max_value'] : -1;
  $value   = isset($args['input_value']) && is_numeric($args['input_value']) ? (float) $args['input_value'] : 1;
  $limited = $max >= 0;

  $fixed = ($product instanceof WC_Product && $product->is_sold_individually()) || ($limited && $max <= max(1, $min));

  // Button states: minus disabled at the minimum or 1 (use the remove link to remove an item), plus at the maximum
  $GLOBALS['bootscore_wc_quantity'] = array(
    'fixed' => $fixed,
    'minus' => $value > max(1, $min),
    'plus'  => !$limited || $value < $max,
  );

  if ($fixed) {
    $args['classes']   = array_merge((array) ($args['classes'] ?? array()), array('qty-fixed'));
    $args['readonly']  = true;
  }

  return $args;
}
add_filter('woocommerce_quantity_input_args', 'bootscore_wc_quantity_input_args', 10, 2);

// WooCommerce uses a hidden input if min = max, keep it a number input
function bootscore_wc_quantity_input_type($type) {
  return empty($GLOBALS['bootscore_wc_quantity']['fixed']) ? $type : 'number';
}
add_filter('woocommerce_quantity_input_type', 'bootscore_wc_quantity_input_type');


/**
 * Quantity button
 *
 * Change classes via filter, 2nd argument is the button: 'minus' or 'plus'.
 */
function bootscore_wc_quantity_button($type) {
  $enabled = $GLOBALS['bootscore_wc_quantity'][$type] ?? true;
  $label   = 'minus' === $type ? __('Decrease quantity', 'bootscore') : __('Increase quantity', 'bootscore');

  return sprintf(
    '<button type="button" class="%1$s %2$s" data-type="%1$s" aria-label="%3$s"%4$s>%5$s</button>',
    esc_attr($type),
    esc_attr(apply_filters('bootscore/class/woocommerce/quantity/button', 'btn-outline theme-secondary input-group-btn', $type)),
    esc_attr($label),
    $enabled ? '' : ' disabled',
    'minus' === $type ? '&minus;' : '+'
  );
}


/**
 * Input group around buttons and input
 */
function bootscore_wc_quantity_minus_button() {
  if (!empty($GLOBALS['bootscore_wc_quantity']['fixed'])) {
    return;
  }

  echo '<div class="input-group ' . esc_attr(apply_filters('bootscore/class/woocommerce/quantity/input-group', '')) . '">' . bootscore_wc_quantity_button('minus'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action('woocommerce_before_quantity_input_field', 'bootscore_wc_quantity_minus_button');

function bootscore_wc_quantity_plus_button() {
  if (!empty($GLOBALS['bootscore_wc_quantity']['fixed'])) {
    return;
  }

  echo bootscore_wc_quantity_button('plus') . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action('woocommerce_after_quantity_input_field', 'bootscore_wc_quantity_plus_button');
