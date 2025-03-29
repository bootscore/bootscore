<?php

/**
 * Block Buttons
 *
 * @package Bootscore
 * @version 6.2.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Buttons
 */
if (!function_exists('bootscore_block_buttons_classes')) {
  /**
   * Adds Bootstrap classes to block buttons.
   *
   * @param string $block_content The block content.
   * @param array  $block         The full block, including name and attributes.
   * @return string The filtered block content.
   */
  function bootscore_block_buttons_classes($block_content, $block) {
    
    $search  = array(
      //'wp-block-buttons',
      'wp-block-button__link'
    );
    $replace = array(
      //'mb-3',
      'btn btn-primary'
    );
    
    $block_content = str_replace($search, $replace, $block_content);

    return apply_filters('bootscore/block/buttons/content', $block_content, $block);
  }
}
add_filter('render_block_core/buttons', 'bootscore_block_buttons_classes', 10, 2);