<?php

/**
 * Patterns
 *
 * @package Bootscore
 * @version 6.2.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/*
 * Register pattern category
 */ 
function bootscore_pattern_category() {
  register_block_pattern_category(
    'bootscore',
    array( 'label' => __( 'Bootscore', 'bootscore' ) )
  );
}
add_action('init', 'bootscore_pattern_category');


/**
 * Remove specific classes conditionally
 * - wp-block-group
 * - is-layout-flow
 * - wp-block-group-is-layout-flow
 * - wp-block-heading
 * - wp-block-list
 * - wp-block-image
 * - is-layout-constrained
 * - -is-layout-constrained
 * if `hide-wp-block-classes` class is set to a parent group.
 */
function bootscore_remove_block_classes($block_content, $block) {
  // Check if the block is one of the target block types
  if (in_array($block['blockName'], ['core/group', 'core/heading', 'core/list', 'core/image'], true)) {
    // Specify the outer class to look for
    $required_outer_class = 'hide-wp-block-classes';

    // Check if the required class exists in the outer <div>
    if (strpos($block_content, $required_outer_class) !== false) {
      // Remove the unwanted classes
      $block_content = preg_replace(
        '/\bwp-block-group\b|\bis-layout-flow\b|\bwp-block-group-is-layout-flow\b|\bwp-block-heading\b|\bwp-block-list\b|\bwp-block-image\b|\bis-layout-constrained\b|\b\-is-layout-constrained\b/',
        '',
        $block_content
      );

      // Clean up any remaining `class` attribute
      $block_content = preg_replace_callback(
        '/class="([^"]*)"/',
        function ($matches) {
          // Split the class names, remove empty or invalid ones
          $classes = array_filter(array_map('trim', explode(' ', $matches[1])), function ($class) {
              return $class !== '-' && $class !== '';
          });

          // Rebuild the `class` attribute or return an empty string if no classes remain
          return !empty($classes) ? 'class="' . implode(' ', $classes) . '"' : '';
        },
        $block_content
      );

      // Remove any leftover empty `class=""` attributes
      $block_content = preg_replace('/\sclass=""/', '', $block_content);
    }
  }
  return $block_content;
}
add_filter('render_block', 'bootscore_remove_block_classes', 10, 2);