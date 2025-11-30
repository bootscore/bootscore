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
    

    
    
    // Auxiliary function for class replacement
    $replace_class = function($html, $search, $replace) {
      return preg_replace_callback('/class="([^"]+)"/', function($matches) use ($search, $replace) {
        $classes = explode(' ', $matches[1]);
        foreach ($classes as &$c) {
          if ($c === $search) {
            $c = $replace; // Replaces the exact class
          }
        }
        return 'class="' . implode(' ', $classes) . '"';
      }, $html);
    };

    // Replacement association
    $class_replacements = [
      
      // Wrapper
      'wp-block-woocommerce-product-filters' => 'wp-block-woocommerce-product-filters hide-wp-block-classes hide-wp-block-inline-styles',
      
      // Mobile stuff
      'wc-block-product-filters__open-overlay' => 'd-none',   // Open button
      'wc-block-product-filters__overlay-header' => 'd-none', // Close button
      'wc-block-product-filters__overlay' => 'test',          // Overlay
      'wc-block-product-filters__overlay-wrapper' => 'test',  // Overlay wrapper
      'wc-block-product-filters__overlay-dialog' => 'test',   // Overlay modal
      'wc-block-product-filters__overlay-content' => 'test',  // Overlay content
      'wc-block-product-filters__overlay-footer' => 'd-none', // Apply button
      

      // Chips
      'wc-block-product-filter-removable-chips__items' => 'list-unstyled d-flex flex-wrap gap-2',
      'wc-block-product-filter-removable-chips__item' => 'badge text-wrap text-start bg-primary-subtle text-primary-emphasis text-decoration-none d-flex align-items-center',
      'wc-block-product-filter-removable-chips__remove' => 'btn-close ms-1',
      'wc-block-product-filter-removable-chips__remove-icon' => 'btn-close-icon d-none',

      // Clear filters button
      'wp-block-button__link' => '',
      'btn-outline-primary' => 'btn btn-link p-0',
      
      // Price range
      //'text' => 's',
      //'min' => 'min form-control',
      

      // Checks
      'wc-block-product-filter-checkbox-list__item' => 'form-check',
      'wc-block-product-filter-checkbox-list__label' => 'form-check-label d-block cursor-pointer',
      'wc-block-product-filter-checkbox-list__input-wrapper' => '',
      'wc-block-product-filter-checkbox-list__input' => 'form-check-input',
      'wc-block-product-filter-checkbox-list__text-wrapper' => 'd-flex align-items-center',
      
      'wc-block-product-filter-checkbox-list__count' => 'badge bg-primary-subtle text-primary-emphasis ms-auto',
      
    ];

    foreach ($class_replacements as $search => $replace) {
        $block_content = $replace_class($block_content, $search, $replace);
    }
    
    // Remove parentheses inside Bootstrap-styled count spans
    $block_content = preg_replace(
      '/(<span[^>]*badge[^>]*>)[\s()]*([0-9]+)[\s()]*(<\/span>)/',
      '$1$2$3',
      $block_content
    );

    return apply_filters('bootscore/block/product/filters/content', $block_content, $block);
  }
}
add_filter('render_block_woocommerce/product-filters', 'bootscore_wc_block_widget_product_filter_classes', 10, 2);