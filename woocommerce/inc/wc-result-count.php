<?php

/**
 * WooCommerce Archive Result Count ans Orderby
 *
 * @package Bootscore
 * @version 6.0.5
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Add columns
 */
add_action( 'woocommerce_before_shop_loop', 'add_opening_row_wrapper', 15 );
function add_opening_row_wrapper() {
  echo '<div class="row mb-4">';
}

add_action( 'woocommerce_before_shop_loop', 'add_opening_result_count_col_wrapper', 18 );
function add_opening_result_count_col_wrapper() {
  echo '<div class="col-md-6 col-lg-8 col-xxl-9">';
}

add_action( 'woocommerce_before_shop_loop', 'close_result_count_col_wrapper', 22 );
function close_result_count_col_wrapper() {
  echo '</div>';
}

add_action( 'woocommerce_before_shop_loop', 'add_opening_orderby_col_wrapper', 29 );
function add_opening_orderby_col_wrapper() {
  echo '<div class="col-md-6 col-lg-4 col-xxl-3">';
}

add_action( 'woocommerce_before_shop_loop', 'close_orderby_col_wrapper', 31 );
function close_orderby_col_wrapper() {
  echo '</div>';
}

add_action( 'woocommerce_before_shop_loop', 'close_row_wrapper', 32 );
function close_row_wrapper() {
  echo '</div>';
}