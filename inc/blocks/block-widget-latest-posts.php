<?php

/**
 * Latest Posts Block Widget
 *
 * @package Bootscore
 * @version 5.4.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Latest Posts Block
 */
if (!function_exists('bootscore_block_widget_latest_posts_classes')) {
  /**
   * Adds Bootstrap classes to latest post block widget.
   *
   * @param string $block_content The block content.
   * @param array  $block         The full block, including name and attributes.
   * @return string The filtered block content.
   */
  function bootscore_block_widget_latest_posts_classes($block_content, $block) {
    // Check if the block contains the 'wp-block-latest-posts' class.
    if (strpos($block_content, 'wp-block-latest-posts') !== false) {
      $search  = array(
        'wp-block-latest-posts__list',
        '<li',
        '<a',
        'wp-block-latest-posts__post-author',
        'wp-block-latest-posts__post-date',
      );
      $replace = array(
        'wp-block-latest-posts__list bs-list-group list-group',
        '<li class="list-group-item list-group-item-action"',
        '<a class="stretched-link text-decoration-none"',
        'small text-body-secondary',
        'small text-body-secondary d-block',
      );

      $block_content = str_replace($search, $replace, $block_content);
    }

    return $block_content;
  }
}
add_filter('render_block_core/latest-posts', 'bootscore_block_widget_latest_posts_classes', 10, 2);