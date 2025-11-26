<?php

/**
 * Block Buttons
 *
 * @package Bootscore
 * @version 6.3.1
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

/**
  * Buttons
  *
  * Add the classes btn and btn-primary or btn-outline-primary to the block buttons.
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

    /**
     * Use preg_replace_callback to process each individual button <div> in the block.
     * The regex matches:
     * 1. <div class="wp-block-button"> and captures any additional classes in $matches[1]
     * 2. The <a> tag inside the div, capturing attributes before class="" in $matches[2]
     * 3. The classes of the <a> element in $matches[3]
     * This allows us to manipulate each button individually, detect outline styles,
     * and apply Bootstrap btn classes appropriately.
     */
    $block_content = preg_replace_callback(
    '/<div class="wp-block-button\b([^"]*)">\s*<a([^>]*)class="([^"]*)"/i',
    function ($matches) {
        // Classes of the wp-block-button div.
        $div_classes = trim($matches[1]);

        // Remove any is-style-outline--<number> class from the div.
        $div_classes = preg_replace('/\bis-style-outline--\d+\b/', '', $div_classes);

        // Normalize whitespace in div classes.
        $div_classes = trim(preg_replace('/\s+/', ' ', $div_classes));

        // Attributes of <a> before class="".
        $a_before = $matches[2];

        // Classes of the <a> element.
        $a_classes = $matches[3];

        // Detect if this p-block-button div has the outline style.
        $has_outline = (strpos($div_classes, 'is-style-outline') !== false);

        // Ensure base .btn class exists.
        if (strpos($a_classes, 'btn') === false) {
            $a_classes .= ' btn';
        }

        /**
         * Determine the correct button style:
         * If the parent div has is-style-outline, use btn-outline-primary.
         * Otherwise, use btn-primary as default.
         */
        if ($has_outline) {
            $a_classes = str_replace('btn-primary', '', $a_classes);
            if (strpos($a_classes, 'btn-outline-primary') === false) {
                $a_classes .= ' btn-outline-primary';
            }
        } else {
            $a_classes = str_replace('btn-outline-primary', '', $a_classes);
            if (strpos($a_classes, 'btn-primary') === false) {
                $a_classes .= ' btn-primary';
            }
        }

        // Normalize whitespace in <a> classes.
        $a_classes = trim(preg_replace('/\s+/', ' ', $a_classes));

        // Reconstruct the div + <a> with updated classes.
        return '<div class="wp-block-button' . ($div_classes ? ' ' . $div_classes : '') . '"><a' . $a_before . 'class="' . $a_classes . '"';
        },
        $block_content
    );

    return apply_filters('bootscore/block/buttons/content', $block_content, $block);
  }
}
add_filter('render_block_core/buttons', 'bootscore_block_buttons_classes', 10, 2);