<?php

/**
 * WooCommerce quantity buttons
 *
 * Minus and plus buttons around the quantity input on single product, cart and mini cart.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Always show the quantity input
 *
 * WooCommerce hides the input if the value can't be changed (sold individually, or min = max).
 *
 * @link https://github.com/woocommerce/woocommerce/pull/36460
 * @link https://github.com/bootscore/bootscore/pull/543
 */
function bootscore_wc_quantity_input_type() {
  return 'number';
}
add_filter('woocommerce_quantity_input_type', 'bootscore_wc_quantity_input_type');


/**
 * Button states for the quantity input that is rendered next
 *
 * - min = max or max = 1: both buttons disabled, input readonly
 * - value = min or value = 1: minus disabled (use the remove link to remove an item)
 * - value = max: plus disabled
 */
function bootscore_wc_quantity_input_args($args) {
  $GLOBALS['bootscore_wc_quantity_buttons'] = array(
    'minus' => true,
    'plus'  => true,
  );

  if (!isset($args['max_value'], $args['min_value'], $args['input_value'])) {
    return $args;
  }

  if ($args['max_value'] == 1 || $args['min_value'] == $args['max_value']) {
    $GLOBALS['bootscore_wc_quantity_buttons'] = array('minus' => false, 'plus' => false);
    $args['readonly'] = true;
  } elseif ($args['input_value'] == $args['min_value'] || $args['input_value'] == 1) {
    $GLOBALS['bootscore_wc_quantity_buttons']['minus'] = false;
  } elseif ($args['input_value'] == $args['max_value']) {
    $GLOBALS['bootscore_wc_quantity_buttons']['plus'] = false;
  }

  return $args;
}
add_filter('woocommerce_quantity_input_args', 'bootscore_wc_quantity_input_args');


/**
 * Quantity button
 *
 * Change classes via filter, 2nd argument is the button: 'minus' or 'plus'.
 */
function bootscore_wc_quantity_button($type) {
  $enabled = $GLOBALS['bootscore_wc_quantity_buttons'][$type] ?? true;
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
  echo '<div class="input-group ' . esc_attr(apply_filters('bootscore/class/woocommerce/quantity/input-group', '')) . '">' . bootscore_wc_quantity_button('minus'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action('woocommerce_before_quantity_input_field', 'bootscore_wc_quantity_minus_button');

function bootscore_wc_quantity_plus_button() {
  echo bootscore_wc_quantity_button('plus') . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action('woocommerce_after_quantity_input_field', 'bootscore_wc_quantity_plus_button');
