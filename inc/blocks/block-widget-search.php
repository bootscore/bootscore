<?php

/**
 * Search Block Widget
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Search Block
 */
if (!function_exists('bootscore_block_widget_search_classes')) {
  /**
   * Adds Bootstrap classes to search block widget.
   *
   * @param string $block_content The block content.
   * @param array  $block         The full block, including name and attributes.
   * @return string The filtered block content.
   */
  function bootscore_block_widget_search_classes($block_content, $block) {

    $search  = array(
      '<form ',
      'wp-block-search__input ',
      'wp-block-search__input"',
      'wp-block-search__button ',
      '<svg class="search-icon" viewBox="0 0 24 24" width="24" height="24">
					<path d="M13 5c-3.3 0-6 2.7-6 6 0 1.4.5 2.7 1.3 3.7l-3.8 3.8 1.1 1.1 3.8-3.8c1 .8 2.3 1.3 3.7 1.3 3.3 0 6-2.7 6-6S16.3 5 13 5zm0 10.5c-2.5 0-4.5-2-4.5-4.5s2-4.5 4.5-4.5 4.5 2 4.5 4.5-2 4.5-4.5 4.5z"></path>
				</svg>'
    );
    $replace = array(
      '<form novalidate="novalidate" ',
      'wp-block-search__input form-control ',
      'wp-block-search__input form-control"',
      'wp-block-search__button btn btn-outline-secondary ',
      '<i class="fa-solid fa-magnifying-glass"></i>'
    );

    if (isset($block['attrs']['buttonPosition']) && 'button-inside' === $block['attrs']['buttonPosition']) {
      $search[]  = 'wp-block-search__inside-wrapper';
      $replace[] = 'wp-block-search input-group';
    }
    
    $block_content = str_replace($search, $replace, $block_content);

    return apply_filters('bootscore/block/search/content', $block_content, $block);
  }
}
add_filter('render_block_core/search', 'bootscore_block_widget_search_classes', 10, 2);