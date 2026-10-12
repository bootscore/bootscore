<?php

/**
 * Block Table
 *
 * @package Bootscore
 * @version 7.0.0
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

    $tags    = new WP_HTML_Tag_Processor($block_content);
    $striped = false;

    // <figure class="wp-block-table">
    if ($tags->next_tag(array('tag_name' => 'FIGURE', 'class_name' => 'wp-block-table'))) {
      $striped = $tags->has_class('is-style-stripes');

      $tags->remove_class('wp-block-table');
      $tags->remove_class('is-style-stripes');
      $tags->add_class('table-responsive text-nowrap'); // text-nowrap because tables inherit text-wrap from <body>
    }

    // <table>
    if ($tags->next_tag('table')) {
      $tags->add_class(trim('table ' . ($striped ? 'table-striped ' : '') . apply_filters('bootscore/class/block/table', '')));
    }

    $block_content = $tags->get_updated_html();

    return apply_filters('bootscore/block/table/content', $block_content, $block);
  }
}
add_filter('render_block_core/table', 'bootscore_block_table_classes', 10, 2);
