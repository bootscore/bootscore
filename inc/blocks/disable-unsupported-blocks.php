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


/**
 * Disable all WP core patterns
 */
add_action('init', function() {
  remove_theme_support('core-block-patterns');
},  9  );

if ( is_admin() ) {
  remove_theme_support('core-block-patterns');
}




/**
 * Disable all WP block styles
 */
/*
function prefix_remove_core_block_styles() {
	global $wp_styles;

	foreach ( $wp_styles->queue as $key => $handle ) {
		if ( strpos( $handle, 'wp-block-' ) === 0 ) {
			wp_dequeue_style( $handle );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'prefix_remove_core_block_styles' );



add_action(
	'wp_default_styles',
	function( $styles ) {

		// Create an array with the two handles wp-block-library and
		 // wp-block-library-theme
		$handles = [ 'wp-block-library', 'wp-block-library-theme' ];

		foreach ( $handles as $handle ) {
			// Search and compare with the list of registered style handles:
			$style = $styles->query( $handle, 'registered' );
			if ( ! $style ) {
				continue;
			}
			// Remove the style
			$styles->remove( $handle );
			// Remove path and dependencies
			$styles->add( $handle, false, [] );
		}
	},
	PHP_INT_MAX
);

remove_filter( 'render_block', 'wp_render_layout_support_flag', 10, 2 );
*/


// This line is preferably be added to your theme's functions.php file
// with other add_theme_support() function calls.
//add_theme_support( 'disable-layout-styles' );

// These two lines will probably not be necessary eventually
//remove_filter( 'render_block', 'wp_render_layout_support_flag', 10, 2 );
//remove_filter( 'render_block', 'gutenberg_render_layout_support_flag', 10, 2 );


