<?php

/**
 * Deprecated
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


// Remove in v6
// Internet Explorer Warning Alert
if (!function_exists('bootscore_ie_alert')) :
  /**
   * Deprecated - functionality is removed already - Code will be removed in a future release.
   * Replaced with a js solution to prevent page caching
   *
   * (Displays an alert if page is browsed by Internet Explorer)
   *
   * function stays to not break child themes with the function bootscore_ie_alert() immediately
   */
  function bootscore_ie_alert() {
  }
endif;





/**
 * Enable shortcodes in HTML-Widget
 * Not needed for Gutenberg widgets https://github.com/bootscore/bootscore/pull/660
 */
add_filter('widget_text', 'do_shortcode');


/*
 * Check if the old functions which were used for simple classes are used in the child theme
 * If so, we transform them to use the new filter hooks
 */
if (function_exists('bootscore_main_col_class')) {
  add_filter('bootscore/class/col/main', 'bootscore_main_col_class', 100);
}

if (function_exists('bootscore_sidebar_col_class')) {
  add_filter('bootscore/class/col/sidebar', 'bootscore_sidebar_col_class', 100);
}

if (function_exists('bootscore_sidebar_toggler_class')) {
  add_filter('bootscore/sidebar/toggler_class', 'bootscore_sidebar_toggler_class', 100);
}

if (function_exists('bootscore_sidebar_offcanvas_class')) {
  add_filter('bootscore_sidebar_offcanvas_class', 'bootscore_sidebar_offcanvas_class', 100);
}

if (function_exists('bootscore_container_class')) {
  add_filter('bootscore/class/container', 'bootscore_container_class', 100);
}




/**
 * Hook after #primary
 */
function bs_after_primary() {
  do_action('bs_after_primary');
}
