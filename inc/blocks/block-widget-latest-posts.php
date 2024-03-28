<?php

/**
 * Latest Posts Block Widget
 *
 * @package Bootscore
 * @version 6.0.0
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

    $search  = array(
      'wp-block-latest-posts__list',
      '<li',
      'wp-post-image',
      '<a',
      'wp-block-latest-posts__post-author',
      'wp-block-latest-posts__post-date',
      'wp-block-latest-posts__post-excerpt',
    );
    $replace = array(
      'wp-block-latest-posts__list bs-list-group list-group',
      '<li class="list-group-item list-group-item-action"',
      'wp-post-image rounded mb-3',
      '<a class="stretched-link text-decoration-none"',
      'small text-body-secondary',
      'small text-body-secondary d-block',
      'wp-block-latest-posts__post-excerpt mb-0',
    );

    $block_content = str_replace($search, $replace, $block_content);

    return apply_filters('bootscore/block/latest-posts/content', $block_content, $block);
  }
}
add_filter('render_block_core/latest-posts', 'bootscore_block_widget_latest_posts_classes', 10, 2);