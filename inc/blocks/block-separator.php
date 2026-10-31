<?php

/**
 * Block Separator
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Separator
 */
if (!function_exists('bootscore_block_separator_classes')) {
  /**
   * Removes the core class from the separator block, so Bootstrap's <hr> styles apply.
   *
   * @param string $block_content The block content.
   * @param array  $block         The full block, including name and attributes.
   * @return string The filtered block content.
   */
  function bootscore_block_separator_classes($block_content, $block) {

    $tags = new WP_HTML_Tag_Processor($block_content);

    if ($tags->next_tag(array('tag_name' => 'HR', 'class_name' => 'wp-block-separator'))) {
      $tags->remove_class('wp-block-separator');
    }

    $block_content = $tags->get_updated_html();

    return apply_filters('bootscore/block/separator/content', $block_content, $block);
  }
}
add_filter('render_block_core/separator', 'bootscore_block_separator_classes', 10, 2);
