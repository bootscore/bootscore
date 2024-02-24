<?php

/**
 * Archives Block Widget
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Archive Block
 */
if (!function_exists('bootscore_block_widget_archives_classes')) {
  /**
   * Adds Bootstrap classes to archive block widget.
   *
   * @param string $block_content The block content.
   * @param array  $block         The full block, including name and attributes.
   * @return string The filtered block content.
   */
  function bootscore_block_widget_archives_classes($block_content, $block) {
    
    // Check if the block contains the 'wp-block-archives-list' class, exclude the dropdown.
    if (strpos($block_content, 'wp-block-archives-list') !== false) {
      $search  = array(
        'wp-block-archives-list',
        '<li',
        '<a',
        '(',
        ')'
      );
      $replace = array(
        'wp-block-archives-list bs-list-group list-group',
        '<li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"',
        '<a class="stretched-link text-decoration-none"',
        '<span class="badge bg-primary-subtle text-primary-emphasis">',
        '</span>'
      );

      $block_content = str_replace($search, $replace, $block_content);
    }

    return apply_filters('bootscore/block/archives/content', $block_content, $block);
  }
}
add_filter('render_block_core/archives', 'bootscore_block_widget_archives_classes', 10, 2);