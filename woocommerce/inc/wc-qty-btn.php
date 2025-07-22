<?php

  /**
   * WooCommerce Quantity Buttons
   *
   * @package Bootscore
   * @version 6.2.0
   */

// Exit if accessed directly
  defined('ABSPATH') || exit;

  /**
   * This snippet can be used to force the quantity input to display in cases where
   * the input value cannot be changed (which is when the product is set to be sold
   * individually, or when the min and max values are identical).
   * See https://github.com/woocommerce/woocommerce/pull/36460
   * See https://github.com/bootscore/bootscore/pull/543/commits/57574c1fdd4ad10d296df70e51cf08b801fccb27
   */
  add_filter('woocommerce_quantity_input_args', function (array $args) {
    add_filter('woocommerce_quantity_input_type', 'change_quantity_input_type');

    // Both Buttons are enabled
    if( $args['max_value'] == 1 || $args['min_value'] == $args['max_value'] ) {
      add_action('woocommerce_before_quantity_input_field', 'bs_quantity_minus_button_disabled');
      add_action('woocommerce_after_quantity_input_field', 'bs_quantity_plus_button_disabled');

      remove_action('woocommerce_before_quantity_input_field', 'bs_quantity_minus_button');
      remove_action('woocommerce_after_quantity_input_field', 'bs_quantity_plus_button');
      $args['readonly'] = true;
    } elseif ($args['input_value'] == $args['min_value'] || $args['input_value'] == 1) {
      add_action('woocommerce_before_quantity_input_field', 'bs_quantity_minus_button_disabled');
      add_action('woocommerce_after_quantity_input_field', 'bs_quantity_plus_button');

      remove_action('woocommerce_before_quantity_input_field', 'bs_quantity_minus_button');
      remove_action('woocommerce_after_quantity_input_field', 'bs_quantity_plus_button_disabled');
    } elseif ($args['input_value'] == $args['max_value']) {
      add_action('woocommerce_before_quantity_input_field', 'bs_quantity_minus_button');
      add_action('woocommerce_after_quantity_input_field', 'bs_quantity_plus_button_disabled');

      remove_action('woocommerce_before_quantity_input_field', 'bs_quantity_minus_button_disabled');
      remove_action('woocommerce_after_quantity_input_field', 'bs_quantity_plus_button');
    } else {
      add_action('woocommerce_before_quantity_input_field', 'bs_quantity_minus_button');
      add_action('woocommerce_after_quantity_input_field', 'bs_quantity_plus_button');

      remove_action('woocommerce_before_quantity_input_field', 'bs_quantity_minus_button_disabled');
      remove_action('woocommerce_after_quantity_input_field', 'bs_quantity_plus_button_disabled');
    }

    return $args;
  });

  function change_quantity_input_type() {
    return 'number';
  }

  function bs_quantity_minus_button() {
    echo '<div class="input-group"><button type="button" class="minus input-group-text" data-type="minus">-</button>';
  }

  function bs_quantity_minus_button_disabled() {
    echo '<div class="input-group"><button type="button" class="minus input-group-text disabled" disabled="disabled" data-type="minus">-</button>';
  }

  function bs_quantity_plus_button() {
    echo '<button type="button" class="plus input-group-text" data-type="plus">+</button></div>';
  }

  function bs_quantity_plus_button_disabled() {
    echo '<button type="button" class="plus input-group-text disabled" disabled="disabled" data-type="plus">+</button></div>';
  }
