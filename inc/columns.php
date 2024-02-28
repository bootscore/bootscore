<?php

/**
 * Columns
 *
 * @package Bootscore
 * @version 5.3.3
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Determines the CSS class for the main column based on the presence of a sidebar.
 *
 * @param string $string The default CSS class for the main column.
 *
 * @return string The CSS class for the main column. If a sidebar is active, it will return "col-md-8 col-lg-9". Otherwise, it will return the provided $string.
 */
function bootscore_main_col_class_sidebar($string) {
  if (is_active_sidebar('sidebar-1')) {
    // Sidebar is not empty
    return "col-md-8 col-lg-9";
  }

  return $string;
}

add_filter('bootscore/main/col_class', 'bootscore_main_col_class_sidebar');

