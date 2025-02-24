<?php

/**
 * Block Quote
 *
 * @package Bootscore
 * @version 6.1.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Table Quote
 */
if (!function_exists('bootscore_block_quote_classes')) {
  /**
   * Adds Bootstrap classes to block quote.
   *
   * @param string $block_content The block content.
   * @param array  $block         The full block, including name and attributes.
   * @return string The filtered block content.
   */
  function bootscore_block_quote_classes($block_content, $block) {
    
    $search  = array(
      '<blockquote',
      '<cite'
    );
    $replace = array(
      '<blockquote class="blockquote"',
      '<cite class="blockquote-footer"'
    );
    
    $block_content = str_replace($search, $replace, $block_content);

    return apply_filters('bootscore/block/quote/content', $block_content, $block);
  }
}
add_filter('render_block_core/quote', 'bootscore_block_quote_classes', 10, 2);