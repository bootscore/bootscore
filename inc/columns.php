<?php

/**
 * Coolumns
 *
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


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
