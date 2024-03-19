<?php

/**
 * Deprecated
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;











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




