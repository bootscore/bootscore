<?php

/**
 * Experimantal: Disable unsupported blocks and patterns - Whitelist
 *
 * @package Bootscore
 * @version 6.2.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Disable unsupported blocks
 */
add_filter('allowed_block_types_all', function( $allowed_blocks, $editor_context ) {

  $supported_blocks = [
    // Text
    'core/paragraph',
    'core/heading',
    'core/list',
    'core/list-item',
    'core/quote',
    'core/code',
    'core/preformatted',
    'core/table',
    'core/freeform',

    // Media
    'core/image',
    'core/gallery',
    'core/audio',
    'core/video',

    // Design
    'core/button',
    'core/buttons',
    'core/group',
    'core/separator',
    'core/spacer',

    // Widgets
    'core/archives',
    'core/calendar',
    'core/categories',
    'core/html',
    'core/latest-comments',
    'core/latest-posts',
    'core/search',
    'core/shortcode',

    // Embeds
    'core/embed',

    // WooCommerce
    'woocommerce/product-categories',
    'woocommerce/classic-shortcode',
    'woocommerce/cart',
    'woocommerce/checkout',
  ];

  // Post editor
  if ( ! empty( $editor_context->post ) ) {
    return $supported_blocks;
  }

  // Block-based Widgets screen
  if ( is_admin() && get_current_screen() && get_current_screen()->id === 'widgets' ) {
    return $supported_blocks;
  }

  return $allowed_blocks;
}, 10, 2);









// Disable all WP core patterns
add_action('init', function() {
    remove_theme_support('core-block-patterns');
},  9  );

if ( is_admin() ) {
    remove_theme_support('core-block-patterns');
}
