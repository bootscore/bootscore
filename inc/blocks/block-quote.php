<?php

/**
 * Block Quote
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Quote
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

    $tags = new WP_HTML_Tag_Processor($block_content);

    while ($tags->next_tag()) {
      $tag = $tags->get_tag();

      if ('BLOCKQUOTE' === $tag) {
        $tags->remove_class('wp-block-quote');
        $tags->remove_class('is-layout-flow');
        $tags->remove_class('wp-block-quote-is-layout-flow');
        $tags->add_class('blockquote');
      } elseif ('CITE' === $tag) {
        $tags->add_class('blockquote-footer');
      }
    }

    $block_content = $tags->get_updated_html();

    return apply_filters('bootscore/block/quote/content', $block_content, $block);
  }
}
add_filter('render_block_core/quote', 'bootscore_block_quote_classes', 10, 2);
