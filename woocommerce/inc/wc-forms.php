<?php

/**
 * WooCommerce Forms
 *
 * @package Bootscore
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Remove CSS and/or JS for Select2 used by WooCommerce
 * https://gist.github.com/Willem-Siebe/c6d798ccba249d5bf080.
 */
add_action('wp_enqueue_scripts', 'bootscore_dequeue_stylesandscripts_select2', 100);

function bootscore_dequeue_stylesandscripts_select2() {
  if (class_exists('woocommerce')) {
    wp_dequeue_style('selectWoo');
    wp_deregister_style('selectWoo');

    wp_dequeue_script('selectWoo');
    wp_deregister_script('selectWoo');
  }
}
