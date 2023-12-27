<?php

/**
 * Latest Comments Block Widget
 *
 * @package Bootscore
 * @version 5.4.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Latest Posts Block
 */
if (!function_exists('bootscore_block_widget_latest_commentss_classes')) {
  /**
   * Adds Bootstrap classes to latest post block widget.
   *
   * @param string $block_content The block content.
   * @param array  $block         The full block, including name and attributes.
   * @return string The filtered block content.
   */
  function bootscore_block_widget_latest_commentss_classes($block_content, $block) {
    // Check if the block contains the 'wp-block-latest-comments' class.
    if (strpos($block_content, 'wp-block-latest-comments') !== false) {
      $search  = array(
        'wp-block-latest-comments',
        //'<li class="',
        '<li class="wp-block-latest-comments bs-list-group list-group__comment">',
        '<a class="wp-block-latest-comments bs-list-group list-group__comment-author',
        '<a class="wp-block-latest-comments bs-list-group list-group__comment-link',
        //'<footer class="',
        //'wp-post-image',
        //'<a',
        //'wp-block-latest-posts__post-author',
        //'wp-block-latest-posts__post-date',
      );
      $replace = array(
        'wp-block-latest-comments bs-list-group list-group',
        //'<li class="list-group-item list-group-item-action',
        '<li class="list-group-item list-group-item-action text-body-secondary">',
        '<a class="text-decoration-none text-body-secondary',
        '<a class="stretched-link text-decoration-none d-block',
        //'<footer class="test',
        //'wp-post-image rounded',
        //'<a class="stretched-link text-decoration-none"',
        //'small text-body-secondary',
        //'small text-body-secondary d-block',
      );

      $block_content = str_replace($search, $replace, $block_content);
    }

    return $block_content;
  }
}
add_filter('render_block_core/latest-comments', 'bootscore_block_widget_latest_commentss_classes', 10, 2);