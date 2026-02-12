<?php

/**
 * Bootscore functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Bootscore
 * @version 6.4.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Update Checker
 * https://github.com/YahnisElsts/plugin-update-checker
 */
require 'inc/update/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/bootscore/bootscore/',
	__FILE__,
	'bootscore'
);

// Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');


/**
 * Load required files
 */
require_once get_template_directory() . '/inc/theme-setup.php';             // Theme setup and custom theme supports
require_once get_template_directory() . '/inc/breadcrumb.php';              // Breadcrumb
require_once get_template_directory() . '/inc/columns.php';                 // Main/sidebar column width and breakpoints
require_once get_template_directory() . '/inc/comments.php';                // Comments
require_once get_template_directory() . '/inc/enable-html.php';             // Enable HTML in category and author description
require_once get_template_directory() . '/inc/enqueue.php';                 // Enqueue scripts and styles
require_once get_template_directory() . '/inc/excerpt.php';                 // Adds excerpt to pages
require_once get_template_directory() . '/inc/fontawesome.php';             // Adds shortcode for inserting Font Awesome icons
require_once get_template_directory() . '/inc/hooks.php';                   // Custom hooks
require_once get_template_directory() . '/inc/navwalker.php';               // Register the Bootstrap 5 navwalker
require_once get_template_directory() . '/inc/navmenu.php';                 // Register the nav menus
require_once get_template_directory() . '/inc/pagination.php';              // Pagination for loop and single posts
require_once get_template_directory() . '/inc/password-protected-form.php'; // Form if post or page is protected by password
require_once get_template_directory() . '/inc/template-tags.php';           // Meta information like author, date, comments, category and tags badges
require_once get_template_directory() . '/inc/template-functions.php';      // Functions which enhance the theme by hooking into WordPress
require_once get_template_directory() . '/inc/widgets.php';                 // Register widget area and disables Gutenberg in widgets
require_once get_template_directory() . '/inc/deprecated.php';              // Fallback functions being dropped in v6
require_once get_template_directory() . '/inc/tinymce-editor.php';          // Fix body margin and font-family in backend if classic editor is used

// Blocks
// Patterns
require_once get_template_directory() . '/inc/blocks/patterns.php';         // Register pattern category and script to hide wp-block classes

// Widgets
require_once get_template_directory() . '/inc/blocks/block-widget-archives.php';        // Archive block
require_once get_template_directory() . '/inc/blocks/block-widget-calendar.php';        // Calendar block
require_once get_template_directory() . '/inc/blocks/block-widget-categories.php';      // Categories block
require_once get_template_directory() . '/inc/blocks/block-widget-latest-comments.php'; // Latest posts block
require_once get_template_directory() . '/inc/blocks/block-widget-latest-posts.php';    // Latest posts block
require_once get_template_directory() . '/inc/blocks/block-widget-search.php';          // Searchform block

// Contents
require_once get_template_directory() . '/inc/blocks/block-buttons.php'; // Button block
require_once get_template_directory() . '/inc/blocks/block-code.php';    // Code block
require_once get_template_directory() . '/inc/blocks/block-quote.php';   // Quote block
require_once get_template_directory() . '/inc/blocks/block-table.php';   // Table block

// Disable unsupported blocks and patterns
if (apply_filters('bootscore/disable/unsupported/blocks', false)) {
  require_once get_template_directory() .'/inc/blocks/disable-unsupported-blocks.php';
}


/**
 * Load WooCommerce scripts if plugin is activated
 */
if (class_exists('WooCommerce')) {
  require_once get_template_directory() . '/woocommerce/wc-functions.php';
}


/**
 * Load Jetpack compatibility file
 */
if (defined('JETPACK__VERSION')) {
  require_once get_template_directory() . '/inc/jetpack.php';
}
