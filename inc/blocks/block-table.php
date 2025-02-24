<?php

/**
 * Block Table
 *
 * @package Bootscore
 * @version 6.1.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Table Block
 */
if (!function_exists('bootscore_block_table_classes')) {
  /**
   * Adds Bootstrap classes to block table.
   *
   * @param string $block_content The block content.
   * @param array  $block         The full block, including name and attributes.
   * @return string The filtered block content.
   */
  function bootscore_block_table_classes($block_content, $block) {
    
    $search  = array(
      'wp-block-table',
      '<table'
    );
    $replace = array(
      'table-responsive',
      '<table class="table ' . apply_filters('bootscore/class/block/table', '') . '"'
    );
    
    $block_content = str_replace($search, $replace, $block_content);

    return apply_filters('bootscore/block/table/content', $block_content, $block);
  }
}
add_filter('render_block_core/table', 'bootscore_block_table_classes', 10, 2);