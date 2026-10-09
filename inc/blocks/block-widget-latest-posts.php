<?php

/**
 * Latest Posts Block Widget
 *
 * @package Bootscore
 * @version 7.0.0
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

    $tags        = new WP_HTML_Tag_Processor($block_content);
    $in_featured = false;

    while ($tags->next_tag()) {
      $tag = $tags->get_tag();

      if ('UL' === $tag && $tags->has_class('wp-block-latest-posts__list')) {
        $tags->add_class('bs-list-group list-group');
      } elseif ('LI' === $tag) {
        $tags->add_class('list-group-item list-group-item-action');
      } elseif ('DIV' === $tag && $tags->has_class('wp-block-latest-posts__featured-image')) {
        $in_featured = true;
      } elseif ('IMG' === $tag && $in_featured) {
        $tags->add_class('rounded mb-5');
        $in_featured = false;
      } elseif ($tags->has_class('wp-block-latest-posts__post-title')) {
        $tags->add_class('stretched-link text-decoration-none fg-reset');
      } elseif ($tags->has_class('wp-block-latest-posts__post-author')) {
        $tags->add_class('small fg-secondary');
      } elseif ($tags->has_class('wp-block-latest-posts__post-date')) {
        $tags->add_class('small fg-secondary d-block');
      } elseif ($tags->has_class('wp-block-latest-posts__post-excerpt')) {
        $tags->add_class('mb-0');
      }
    }

    $block_content = $tags->get_updated_html();

    return apply_filters('bootscore/block/latest-posts/content', $block_content, $block);
  }
}
add_filter('render_block_core/latest-posts', 'bootscore_block_widget_latest_posts_classes', 10, 2);
