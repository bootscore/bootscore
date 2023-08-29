<?php

/**
 * Woocommerce deprecated scripts
 *
 * @package Bootscore
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Remove cross-sells at cart
 */
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
