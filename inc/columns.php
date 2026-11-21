<?php

/**
 * Columns
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Determines the CSS class for the main column based on the presence of a sidebar.
 *
 * @param string $string The default CSS class for the main column.
 *
 * @return string The CSS class for the main column. If a sidebar is active, it will return "lg:col-9". Otherwise, it will return the provided $string.
 */
function bootscore_main_col_class_sidebar($string) {
  // Same check as sidebar.php, so plugins like bs Widget Visibility can hide the sidebar and the main column widens with it
  if (apply_filters('bootscore/sidebar/has-widgets', is_active_sidebar('sidebar-1'), 'sidebar-1')) {
    // Sidebar is not empty
    return "lg:col-9";
  }

  return $string;
}

add_filter('bootscore/class/main/col', 'bootscore_main_col_class_sidebar');

