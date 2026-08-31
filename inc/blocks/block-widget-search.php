<?php

/**
 * Search Block Widget
 *
 * @package Bootscore
 * @version 7.0.0
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

    // Input needs trailing margin when the button sits outside the input wrapper.
    $button_position = $block['attrs']['buttonPosition'] ?? 'button-outside';
    $input_spacer    = ('button-inside' !== $button_position) ? ' ' . esc_attr(apply_filters('bootscore/class/widget/search/input/spacer', 'me-2')) : '';

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
      'wp-block-search__input form-control' . $input_spacer . ' ',
      'wp-block-search__input form-control' . $input_spacer . '"',
      'wp-block-search__btn ' . esc_attr(apply_filters('bootscore/class/widget/search/button', 'btn btn-outline-secondary')) . ' ',
      wp_kses(
        apply_filters(
          'bootscore/icon/search',
          '<svg class="bs-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" aria-hidden="true"><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>'
        ),
        bootscore_kses_allowed_svg( wp_kses_allowed_html( 'post' ) )
      )
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