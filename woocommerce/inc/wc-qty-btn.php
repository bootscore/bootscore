<?php

  /**
   * WooCommerce Quantity Buttons
   *
   * @package Bootscore
   * @version 6.3.1
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
    // Ensure a visible number input (prevents Woo from switching to "hidden" when min==max or sold individually)
    add_filter('woocommerce_quantity_input_type', 'change_quantity_input_type');

    // --- Optional base merge: provide sane defaults early (non-invasive; only fills missing keys) ---
    $args = wp_parse_args($args ?? [], [
      'min_value'   => 1,
      'max_value'   => 0, // 0 means "no explicit maximum"
      'input_value' => 1,
    ]);

    // Normalize Woo's "-1" as "no explicit maximum"
    if ((int) $args['max_value'] === -1) {
      $args['max_value'] = 0;
    }

    // --- Safeguards / normalization ---
    $min = ($args['min_value'] !== '' && $args['min_value'] !== null) ? (int) $args['min_value'] : 1;
    $max = ($args['max_value'] !== '' && $args['max_value'] !== null) ? (int) $args['max_value'] : 0; // 0 => unlimited
    $val = ($args['input_value'] !== '' && $args['input_value'] !== null) ? (int) $args['input_value'] : $min;

    // Write back normalized values so downstream templates/hooks see consistent args
    $args['min_value']   = $min;
    $args['max_value']   = $max;
    $args['input_value'] = $val;

    // --- Button state logic ---
    // Case 1: quantity is effectively immutable (sold individually or min==max)
    if ($max === 1 || ($min > 0 && $max > 0 && $min === $max)) {
      add_action('woocommerce_before_quantity_input_field', 'bs_quantity_minus_button_disabled');
      add_action('woocommerce_after_quantity_input_field', 'bs_quantity_plus_button_disabled');

      remove_action('woocommerce_before_quantity_input_field', 'bs_quantity_minus_button');
      remove_action('woocommerce_after_quantity_input_field', 'bs_quantity_plus_button');

      $args['readonly'] = true;

    // Case 2: at lower bound (disable minus)
    } elseif ($val === $min || $val === 1) {
      add_action('woocommerce_before_quantity_input_field', 'bs_quantity_minus_button_disabled');
      add_action('woocommerce_after_quantity_input_field', 'bs_quantity_plus_button');

      remove_action('woocommerce_before_quantity_input_field', 'bs_quantity_minus_button');
      remove_action('woocommerce_after_quantity_input_field', 'bs_quantity_plus_button_disabled');

    // Case 3: at upper bound (disable plus) — only if a real maximum exists
    } elseif ($max > 0 && $val === $max) {
      add_action('woocommerce_before_quantity_input_field', 'bs_quantity_minus_button');
      add_action('woocommerce_after_quantity_input_field', 'bs_quantity_plus_button_disabled');

      remove_action('woocommerce_before_quantity_input_field', 'bs_quantity_minus_button_disabled');
      remove_action('woocommerce_after_quantity_input_field', 'bs_quantity_plus_button');

    // Case 4: free between min and max
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
