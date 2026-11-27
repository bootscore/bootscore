<?php
/**
 * Plugin strings
 *
 * All Bootscore plugins use the theme's text domain 'bootscore', so their translations live in this folder.
 * This file is never loaded. It only lists the plugin strings, so translation tools (Loco, WP-CLI make-pot)
 * pick them up when they scan the theme. Keep it in sync when a plugin adds or changes a string.
 *
 * Strings in templates the plugins output from the theme (template-parts/dark-mode, template-parts/preloader)
 * are found there and not listed here.
 *
 * @package Bootscore
 * @version 7.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

return;

// bs Isotope
__('All', 'bootscore');
__('Search', 'bootscore');
__('Nothing found. Please try a different search term.', 'bootscore');
__('bs Isotope requires the <a href="%s" target="_blank">bs Loop</a> plugin to be installed and activated.', 'bootscore');

// bs Load More
__('Load more', 'bootscore');
__('No more items', 'bootscore');
__('Security check failed.', 'bootscore');
__('Invalid query data.', 'bootscore');

// bs Swiper (WooCommerce headings use WooCommerce's own translations)
__('bs Swiper', 'bootscore');
__('Swiper carousel pattern', 'bootscore');
__('Related posts', 'bootscore');
__('bs Swiper requires the <a href="%s" target="_blank">bs Loop</a> plugin to be installed and activated.', 'bootscore');

// bs TOC
__('No headings found.', 'bootscore');
__('Contents', 'bootscore');
__('Toggle table of contents', 'bootscore');
__('Toggle TOC navigation', 'bootscore');
__('Close', 'bootscore');

// bs Widget Visibility (editor panel)
__('Widget Visibility', 'bootscore');
__('Visibility rule', 'bootscore');
__('Always show', 'bootscore');
__('Show only when…', 'bootscore');
__('Hide when…', 'bootscore');
__('Conditions', 'bootscore');
__('URLs', 'bootscore');
__('Widget is visible when ANY entry matches.', 'bootscore');
__('Widget is hidden when ANY entry matches.', 'bootscore');
__('Wildcards supported.', 'bootscore');
__('One condition per line, e.g.:', 'bootscore');
__('One path per line, e.g.:', 'bootscore');
__('Add at least one condition or URL, or the widget will always be hidden.', 'bootscore');
__('Add at least one condition or URL, or the widget will always be shown.', 'bootscore');

// Plugins outside the v7 release that still use the theme's text domain
// bs Share Buttons
__('Look what I found: ', 'bootscore');
// bs Cookie Banner
__('Cookies help us deliver our services. By using our services, you agree to our use of cookies.', 'bootscore');
__('/privacy-policy', 'bootscore');
__('More Information', 'bootscore');
__('Accept', 'bootscore');
