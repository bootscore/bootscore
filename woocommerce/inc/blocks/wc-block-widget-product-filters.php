<?php

/**
 * WooCommerce Product Filters Block Widget
 *
 * @package Bootscore
 * @version 6.4.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Product Filters Block
 */
if (!function_exists('bootscore_wc_block_widget_product_filter_classes')) {
  /**
   * Adds Bootstrap classes to WC product filter block
   *
   * @param string $block_content The block content.
   * @param array  $block         The full block, including name and attributes.
   * @return string The filtered block content.
   */
  function bootscore_wc_block_widget_product_filter_classes($block_content, $block) {
    
    $search  = array(
      
      // Remove Moblie overlay
      'wc-block-product-filters__open-overlay',
      'wc-block-product-filters__overlay',
      'wc-block-product-filters__overlay-wrapper',
      'wc-block-product-filters__overlay-dialog',
      
      // Chips
      'wc-block-product-filter-removable-chips__items',
      'wc-block-product-filter-removable-chips__item',
      'wc-block-product-filter-removable-chips__remove',
      'btn-close-icon',
      
      // Clear filters button
      'class="btn btn-outline-primary has-text-align-center wp-element-button"',

      // Range
      // form-control seems not working
      //'wp-block-woocommerce-product-filter-price',
      //'min',
      //'max',
      
      // Checks
      'wc-block-product-filter-checkbox-list__item',
      'wc-block-product-filter-checkbox-list__label',
      'wc-block-product-filter-checkbox-list__input',
      'wc-block-product-filter-checkbox-list__text-wrapper'
    );
    $replace = array(
      
      // Remove Moblie overlay
      '',
      '',
      '',
      '',

      // Chips
      'list-unstyled d-flex flex-wrap gap-2',
      'badge bg-light-subtle border border-light-subtle text-light-emphasis rounded-pill text-decoration-none d-flex align-items-center',
      'btn-close ms-1',
      'btn-close-icon d-none',
      
      // Clear filters button
      'class="btn btn-sm btn-outline-danger w-100" data-wp-on--click="actions.removeAllActiveFilters" ',

      // Range
      // form-control seems not working
      //'wp-block-woocommerce-product-filter-price',
      //'min form-control',
      //'max form-control',
      
      // Checks
      'form-check',
      'form-check-label',
      'form-check-input',
      ''
    );
    
    $block_content = str_replace($search, $replace, $block_content);

    return apply_filters('bootscore/block/product/filters/content', $block_content, $block);
  }
}
add_filter('render_block_woocommerce/product-filters', 'bootscore_wc_block_widget_product_filter_classes', 10, 2);
