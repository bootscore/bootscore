<?php

/**
 * Categories Block Widget
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Categories Block
 */
if (!function_exists('bootscore_block_widget_categories_classes')) {
  /**
   * Adds Bootstrap classes to categories block widget.
   *
   * @param string $block_content The block content.
   * @param array  $block         The full block, including name and attributes.
   * @return string The filtered block content.
   */
  function bootscore_block_widget_categories_classes($block_content, $block) {
    
    // Check if the block contains the 'wp-block-categories-list' class, exclude the dropdown.
    if (strpos($block_content, 'wp-block-categories-list') !== false) {
      $search  = array(
        'wp-block-categories-list',
        'cat-item',
        'current-cat',
        '<a',
        '(',
        ')'
      );
      $replace = array(
        'wp-block-categories-list bs-list-group list-group',
        'cat-item list-group-item list-group-item-action d-flex justify-content-between align-items-center',
        'current-cat active',
        '<a class="stretched-link text-decoration-none"',
        '<span class="badge bg-primary-subtle text-primary-emphasis">',
        '</span>'
      );

      $block_content = str_replace($search, $replace, $block_content);
    }

    return apply_filters('bootscore/block/categories/content', $block_content, $block);
  }
}
add_filter('render_block_core/categories', 'bootscore_block_widget_categories_classes', 10, 2);