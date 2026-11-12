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
 * Result count and catalog ordering in one flex row
 *
 * WooCommerce outputs both on woocommerce_before_shop_loop (result count 20, ordering 30).
 * The wrapper opens before (15) and closes after them (32). On small screens they wrap.
 */
function bootscore_wc_before_shop_loop_open() {
  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/loop/before', 'd-flex flex-wrap align-items-baseline justify-content-between gap-5 mb-7')) . '">';
}
add_action('woocommerce_before_shop_loop', 'bootscore_wc_before_shop_loop_open', 15);

function bootscore_wc_before_shop_loop_close() {
  echo '</div>';
}
add_action('woocommerce_before_shop_loop', 'bootscore_wc_before_shop_loop_close', 32);
