<?php

/**
 * Block Code
 *
 * @package Bootscore
 * @version 6.2.2
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Code
 */
if (!function_exists('bootscore_block_code_classes')) {
  /**
   * Adds Bootstrap classes to block code.
   *
   * @param string $block_content The block content.
   * @param array  $block         The full block, including name and attributes.
   * @return string The filtered block content.
   */
  function bootscore_block_code_classes($block_content, $block) {
    
    $search  = array(
      'wp-block-code'
    );
    $replace = array(
      'border rounded bg-body-tertiary p-3'
    );
    
    $block_content = str_replace($search, $replace, $block_content);

    return apply_filters('bootscore/block/code/content', $block_content, $block);
  }
}
add_filter('render_block_core/code', 'bootscore_block_code_classes', 10, 2);