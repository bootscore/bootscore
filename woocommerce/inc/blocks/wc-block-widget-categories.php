<?php

/**
 * WooCommerce Categories Block Widget
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Categories List Block
 */
if (!function_exists('bootscore_wc_block_widget_categories_classes')) {

  function bootscore_wc_block_widget_categories_classes($block_content, $block) {
    // Check if the block contains the 'wc-block-product-categories-list' class.
    if (strpos($block_content, 'wc-block-product-categories-list') !== false) {
      $search  = array(
        'is-list',
        '<ul class="wc-block-product-categories-list',
        '<li class="',
        '<a',
        'wc-block-product-categories-list-item__name',
        'wc-block-product-categories-list-item-count',
        // Has image
        'wc-block-product-categories-list--has-images',
        'wc-block-product-categories-list-item__image--placeholder',
        'wc-block-product-categories-list-item__image',
        'wp-post-image',
        'attachment-woocommerce_thumbnail'
      );
      $replace = array(
        'is-list mb-0',
        '<ul class="wc-block-product-categories-list bs-list-group list-group',
        '<li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center ',
        '<a class="stretched-link text-decoration-none"',
        'wc-block-product-categories-list-item__name align-middle',
        'badge bg-primary-subtle text-primary-emphasis',
        // Has image
        'has-image',
        '',
        'wc-cat-img d-inline-block me-2',
        'wp-post-image border rounded',
        'attachment-woocommerce_thumbnail border rounded'
      );

      $block_content = str_replace($search, $replace, $block_content);
    }

    return $block_content;
  }
}
add_filter('render_block_woocommerce/product-categories', 'bootscore_wc_block_widget_categories_classes', 10, 2);


/**
 * Categories Select Block
 */
if (!function_exists('bootscore_wc_block_widget_categories_select_classes')) {

  function bootscore_wc_block_widget_categories_select_classes($block_content, $block) {
    // Check if the block contains the 'wc-block-product-categories is-dropdown' class.
    if (strpos($block_content, 'is-dropdown') !== false) {
      $search  = array(
        'is-dropdown', 
        'wc-block-product-categories__dropdown',
        '<select',
        // Button
        'wc-block-product-categories__button'
      );
      $replace = array(
        'is-dropdown input-group mb-0',
        'wc-block-product-categories__dropdown flex-grow-1',
        '<select class="form-select" ',
        // Button
        'wc-block-product-categories__button btn btn-outline-secondary'
      );

      $block_content = str_replace($search, $replace, $block_content);
    }

    return $block_content;
  }
}
add_filter('render_block_woocommerce/product-categories', 'bootscore_wc_block_widget_categories_select_classes', 10, 2);
