<?php

/**
 * Bootscore functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


require_once('inc/enqueue.php');
require_once('inc/excerpt.php');
require_once('inc/container.php');
require_once('inc/hooks.php');
require_once('inc/loop.php');
require_once('inc/theme-setup.php');
require_once('inc/breadcrumb.php');
require_once('inc/pagination.php');
require_once('inc/widgets.php');
require_once('inc/deprecated.php');









// Enable WooCommerce scripts if plugin is installed
if (class_exists('WooCommerce')) {
  require get_template_directory() . '/woocommerce/wc-functions.php';
}


// Register Bootstrap 5 Nav Walker
if (!function_exists('register_navwalker')) :
  function register_navwalker() {
    require_once('inc/class-bootstrap-5-navwalker.php');
    // Register Menus
    register_nav_menu('main-menu', 'Main menu');
    register_nav_menu('footer-menu', 'Footer menu');
  }
endif;
add_action('after_setup_theme', 'register_navwalker');
// Register Bootstrap 5 Nav Walker END


// Register Comment List
if (!function_exists('register_comment_list')) :
  function register_comment_list() {
    // Register Comment List
    require_once('inc/comment-list.php');
  }
endif;
add_action('after_setup_theme', 'register_comment_list');
// Register Comment List END














/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';


/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
  require get_template_directory() . '/inc/jetpack.php';
}
















// Password protected form
if (!function_exists('bootscore_pw_form')) :
  function bootscore_pw_form() {
    $output = '
        <form action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post" class="input-group pw_form">' . "\n"
              . '<input name="post_password" type="password" size="" class="form-control" placeholder="' . __('Password', 'bootscore') . '"/>' . "\n"
              . '<input type="submit" class="btn btn-outline-primary input-group-text" name="Submit" value="' . __('Submit', 'bootscore') . '" />' . "\n"
              . '</form>' . "\n";

    return $output;
  }

  add_filter("the_password_form", "bootscore_pw_form");
endif;
// Password protected form END


// Allow HTML in term (category, tag) descriptions
foreach (array('pre_term_description') as $filter) {
  remove_filter($filter, 'wp_filter_kses');
  if (!current_user_can('unfiltered_html')) {
    add_filter($filter, 'wp_filter_post_kses');
  }
}

foreach (array('term_description') as $filter) {
  remove_filter($filter, 'wp_kses_data');
}
// Allow HTML in term (category, tag) descriptions END


// Allow HTML in author bio
remove_filter('pre_user_description', 'wp_filter_kses');
add_filter('pre_user_description', 'wp_filter_post_kses');
// Allow HTML in author bio END





