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

    $inside = 'button-inside' === ($block['attrs']['buttonPosition'] ?? 'button-outside');

    $tags = new WP_HTML_Tag_Processor($block_content);

    // Form: no browser validation bubble on empty search
    if ($tags->next_tag('form')) {
      $tags->set_attribute('novalidate', true);
    }

    while ($tags->next_tag()) {

      if ($tags->has_class('wp-block-search__label')) {
        $tags->remove_class('wp-block-search__label');
      }

      // Button inside: wrapper becomes an input group
      elseif ($inside && $tags->has_class('wp-block-search__inside-wrapper')) {
        $tags->remove_class('wp-block-search__inside-wrapper');
        $tags->add_class('input-group');
      }

      elseif ($tags->has_class('wp-block-search__input')) {
        $tags->remove_class('wp-block-search__input');
        $tags->add_class('form-control');

        // Input needs trailing margin when the button sits outside the input wrapper
        if (!$inside) {
          $tags->add_class(apply_filters('bootscore/class/widget/search/input/spacer', 'me-3'));
        }
      }

      elseif ($tags->has_class('wp-block-search__button')) {
        $tags->remove_class('wp-block-search__button');
        $tags->remove_class('wp-element-button');
        $tags->add_class('wp-block-search__btn ' . apply_filters('bootscore/class/widget/search/button', 'btn-outline theme-secondary'));

        if ($inside) {
          $tags->add_class('input-group-btn');
        }
      }
    }

    $block_content = $tags->get_updated_html();

    // Replace core search icon, independent of its whitespace
    $block_content = preg_replace_callback(
      '#<svg class="search-icon".*?</svg>#s',
      function () {
        return bootscore_icon('search', false);
      },
      $block_content,
      1
    );

    return apply_filters('bootscore/block/search/content', $block_content, $block);
  }
}
add_filter('render_block_core/search', 'bootscore_block_widget_search_classes', 10, 2);
