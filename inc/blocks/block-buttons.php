<?php

/**
 * Block Buttons
 *
 * @package Bootscore
 * @version 6.2.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

/**
  * Buttons
  *
  * Only btn-primary and btn-outline-primary is supported
  */
if (!function_exists('bootscore_block_buttons_classes')) {
  function bootscore_block_buttons_classes($block_content, $block) {
    // Process only core/buttons blocks
    if ($block['blockName'] !== 'core/buttons') {
        return $block_content;
    }

    // Replace wp-block-buttons-is-layout-flex with gap-1 mb-3
    $block_content = str_replace(
        'wp-block-buttons-is-layout-flex',
        'gap-1 mb-3',
        $block_content
    );

    // Detect if any wrapper contains is-style-outline
    $has_outline = (strpos($block_content, 'is-style-outline') !== false);

    /**
     * Add btn classes only to <a> elements
     *
     * We avoid modifying wrapper divs, keeping the logic limited to links.
     * Existing classes are preserved when possible and only corrected as needed.
     */
    $block_content = preg_replace_callback(
        '/<a([^>]*)class="([^"]*)"/i',
        function ($matches) use ($has_outline) {

            $before  = $matches[1]; // Attributes before class=""
            $classes = $matches[2]; // Existing classes inside class=""

            // Ensure base .btn class exists
            if (strpos($classes, 'btn') === false) {
                $classes .= ' btn';
            }

            /**
             * Determine button style.
             *
             * If any parent wrapper declares is-style-outline, we switch links
             * to btn-outline-primary. Otherwise, default to btn-primary.
             */
            if ($has_outline) {
                // Outline mode: remove btn-primary, ensure btn-outline-primary
                $classes = str_replace('btn-primary', '', $classes);
                if (strpos($classes, 'btn-outline-primary') === false) {
                    $classes .= ' btn-outline-primary';
                }
            } else {
                // Default mode: remove btn-outline-primary, ensure btn-primary
                $classes = str_replace('btn-outline-primary', '', $classes);
                if (strpos($classes, 'btn-primary') === false) {
                    $classes .= ' btn-primary';
                }
            }

            // Normalize whitespace inside class=""
            $classes = trim(preg_replace('/\s+/', ' ', $classes));

            return '<a' . $before . 'class="' . $classes . '"';
        },
        $block_content
    );

    return apply_filters('bootscore/block/buttons/content', $block_content, $block);
  }
}
add_filter('render_block_core/buttons', 'bootscore_block_buttons_classes', 10, 2);