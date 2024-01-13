<?php

/**
 * WooCommerce Categories Block Widget
 *
 * @package Bootscore
 * @version 5.4.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Categories List Block
 */
if (!function_exists('bootscore_wc_block_widget_categories_classes')) {

  function bootscore_wc_block_widget_categories_classes($block_content, $block) {
    // Check if the block contains the 'wc-block-product-categories is-list' class.
    if (strpos($block_content, 'wc-block-product-categories-list') !== false) {
      $search  = array(
        'is-list',
        '<ul class="wc-block-product-categories-list',
        '<li class="',
        '<a',
        'wc-block-product-categories-list-item-count'
      );
      $replace = array(
        'is-list mb-0',
        '<ul class="wc-block-product-categories-list bs-list-group list-group',
        '<li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center ',
        '<a class="stretched-link text-decoration-none"',
        'badge bg-primary-subtle text-primary-emphasis'
      );

      $block_content = str_replace($search, $replace, $block_content);
    }

    return $block_content;
  }
}
add_filter('render_block_woocommerce/product-categories', 'bootscore_wc_block_widget_categories_classes', 10, 2);
