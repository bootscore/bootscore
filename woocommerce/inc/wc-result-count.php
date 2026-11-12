<?php

/**
 * WooCommerce Archive Result Count and Orderby
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Add grid
 */
add_action( 'woocommerce_before_shop_loop', 'bootscore_add_opening_row_wrapper', 15 );
function bootscore_add_opening_row_wrapper() {
  echo '<div class="row mb-7">';
}

add_action( 'woocommerce_before_shop_loop', 'bootscore_add_opening_result_count_col_wrapper', 18 );
function bootscore_add_opening_result_count_col_wrapper() {
  echo '<div class="md:col-6 lg:col-8 2xl:col-9">';
}

add_action( 'woocommerce_before_shop_loop', 'bootscore_close_result_count_col_wrapper', 22 );
function bootscore_close_result_count_col_wrapper() {
  echo '</div>';
}

add_action( 'woocommerce_before_shop_loop', 'bootscore_add_opening_orderby_col_wrapper', 29 );
function bootscore_add_opening_orderby_col_wrapper() {
  echo '<div class="md:col-6 lg:col-4 2xl:col-3">';
}

add_action( 'woocommerce_before_shop_loop', 'bootscore_close_orderby_col_wrapper', 31 );
function bootscore_close_orderby_col_wrapper() {
  echo '</div>';
}

add_action( 'woocommerce_before_shop_loop', 'bootscore_close_row_wrapper', 32 );
function bootscore_close_row_wrapper() {
  echo '</div>';
}
