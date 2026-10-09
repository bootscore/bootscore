<?php

/**
 * Block Buttons
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Buttons
 *
 * Adds Bootstrap 6 button classes to the button blocks.
 * Default style → btn-solid, "Outline" style → btn-outline.
 */
if (!function_exists('bootscore_block_buttons_classes')) {
  function bootscore_block_buttons_classes($block_content, $block) {

    $tags    = new WP_HTML_Tag_Processor($block_content);
    $outline = false;

    while ($tags->next_tag()) {

      // Buttons wrapper: replace core flex layout classes with Bootstrap flex utilities
      if ($tags->has_class('wp-block-buttons-is-layout-flex')) {
        $tags->remove_class('wp-block-buttons-is-layout-flex');
        $tags->remove_class('is-layout-flex');
        $tags->add_class(apply_filters('bootscore/class/block/buttons', 'd-flex flex-wrap gap-1 mb-5'));
      }

      // Single button wrapper: remember the style, drop is-style-outline--<n>
      elseif ($tags->has_class('wp-block-button')) {
        $outline = $tags->has_class('is-style-outline');

        foreach (iterator_to_array($tags->class_list()) as $class) {
          if (preg_match('/^is-style-outline--\d+$/', $class)) {
            $tags->remove_class($class);
          }
        }
      }

      // The <a> or <button> itself
      elseif ($tags->has_class('wp-block-button__link')) {
        $tags->remove_class('wp-block-button__link');
        $tags->remove_class('wp-element-button');

        $classes = $outline
          ? apply_filters('bootscore/class/block/button/outline', 'btn-outline theme-primary')
          : apply_filters('bootscore/class/block/button', 'btn-solid theme-primary');

        $tags->add_class($classes);
      }
    }

    $block_content = $tags->get_updated_html();

    return apply_filters('bootscore/block/buttons/content', $block_content, $block);
  }
}
add_filter('render_block_core/buttons', 'bootscore_block_buttons_classes', 10, 2);
