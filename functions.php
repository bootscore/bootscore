<?php

/**
 * Bootscore functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Bootscore
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


require_once('inc/columns.php');
require_once('inc/enable-html.php');
require_once('inc/enqueue.php');
require_once('inc/excerpt.php');
require_once('inc/container.php');
require_once('inc/comments.php');
require_once('inc/hooks.php');
require_once('inc/loop.php');
require_once('inc/theme-setup.php');
require_once('inc/breadcrumb.php');
require_once('inc/pagination.php');
require_once('inc/password-protected-form.php');
require_once('inc/widgets.php');
require_once('inc/deprecated.php');
require_once('inc/template-tags.php');
require_once('inc/template-functions.php');


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





/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
  require get_template_directory() . '/inc/jetpack.php';
}


































