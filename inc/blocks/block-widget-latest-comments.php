<?php

/**
 * Latest Comments Block Widget
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Latest Comments Block
 */
if (!function_exists('bootscore_block_widget_latest_comments_classes')) {
  /**
   * Adds Bootstrap classes to latest comments block widget.
   *
   * @param string $block_content The block content.
   * @param array  $block         The full block, including name and attributes.
   * @return string The filtered block content.
   */
  function bootscore_block_widget_latest_comments_classes($block_content, $block) {

    $tags = new WP_HTML_Tag_Processor($block_content);

    while ($tags->next_tag()) {
      $tag = $tags->get_tag();

      if ('OL' === $tag && $tags->has_class('wp-block-latest-comments')) {
        $tags->add_class('bs-list-group list-group');
      } elseif ($tags->has_class('wp-block-latest-comments__comment')) {
        $tags->remove_class('wp-block-latest-comments__comment');
        $tags->add_class('list-group-item list-group-item-action fg-secondary d-flex align-items-start');
      } elseif ($tags->has_class('wp-block-latest-comments__comment-avatar')) {
        $tags->remove_class('wp-block-latest-comments__comment-avatar');
        $tags->add_class('avatar-img me-3');
      } elseif ($tags->has_class('wp-block-latest-comments__comment-meta')) {
        $tags->remove_class('wp-block-latest-comments__comment-meta');
        $tags->add_class('lh-md');
      } elseif ($tags->has_class('wp-block-latest-comments__comment-author')) {
        $tags->remove_class('wp-block-latest-comments__comment-author');
        $tags->add_class('text-decoration-none fg-secondary position-relative z-2');
      } elseif ($tags->has_class('wp-block-latest-comments__comment-link')) {
        $tags->remove_class('wp-block-latest-comments__comment-link');
        $tags->add_class('stretched-link text-decoration-none fg-body d-block');
      } elseif ($tags->has_class('wp-block-latest-comments__comment-date')) {
        $tags->remove_class('wp-block-latest-comments__comment-date');
        $tags->add_class('small');
      } elseif ($tags->has_class('wp-block-latest-comments__comment-excerpt')) {
        $tags->remove_class('wp-block-latest-comments__comment-excerpt');
      } elseif ('P' === $tag) {
        $tags->add_class('mb-0 fg-body');
      }
    }

    $block_content = $tags->get_updated_html();

    return apply_filters('bootscore/block/latest-comments/content', $block_content, $block);
  }
}
add_filter('render_block_core/latest-comments', 'bootscore_block_widget_latest_comments_classes', 10, 2);
