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
    if ($block['blockName'] !== 'core/buttons') {
      return $block_content;
    }

    // Convert all wp-block-button__link classes to btn-primary by default
    $block_content = str_replace('wp-block-button__link', 'btn btn-primary', $block_content);

    // Use regex to find buttons inside div.wp-block-button.is-style-outline and replace btn-primary with btn-outline-primary
    $block_content = preg_replace_callback(
      '/<div class="wp-block-button([^"]*is-style-outline[^"]*)">.*?<a class="([^"]*?)btn btn-primary([^"]*?)"/s',
      function ($matches) {
        return '<div class="wp-block-button' . $matches[1] . '"><a class="' . $matches[2] . 'btn btn-outline-primary' . $matches[3] . '"';
      },
      $block_content
    );

    return apply_filters('bootscore/block/buttons/content', $block_content, $block);
  }
}
add_filter('render_block_core/buttons', 'bootscore_block_buttons_classes', 10, 2);