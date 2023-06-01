<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Bootscore
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function bootscore_body_classes($classes) {
  // Adds a class of hfeed to non-singular pages.
  if (!is_singular()) {
    $classes[] = 'hfeed';
  }

  // Adds a class of no-sidebar when there is no sidebar present.
  if (!is_active_sidebar('sidebar-1')) {
    $classes[] = 'no-sidebar';
  }

  return $classes;
}

add_filter('body_class', 'bootscore_body_classes');


/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function bootscore_pingback_header() {
  if (is_singular() && pings_open()) {
    printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
  }
}

add_action('wp_head', 'bootscore_pingback_header');


/**
 * Allow modifying the default bootstrap container class
 * @return string
 */
if (!function_exists('bootscore_container_class')) {
  function bootscore_container_class() {
    return "container";
  }
}


/**
 * Make main content col dynamic if sidebar widgets exists
 * @return string
 */
if (!function_exists('bootscore_main_col_class')) {
  function bootscore_main_col_class() {
    if (!is_active_sidebar('sidebar-1')) {
      // Sidebar is empty
      return "col";
    } else {
      // Sidebar has widgets
      return "col-md-8 col-lg-9";
    }
  }
}
