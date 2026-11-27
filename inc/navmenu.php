<?php

/**
 * Nav menus
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Register the nav menus
 */
if (!function_exists('bootscore_register_navmenu')) :
  function bootscore_register_navmenu() {
    // Register Menus
    register_nav_menu('main-menu', __('Main menu', 'bootscore'));
    register_nav_menu('footer-menu', __('Footer menu', 'bootscore'));
  }
endif;
add_action('after_setup_theme', 'bootscore_register_navmenu');


/**
 * Enable shortcodes in navigation menu titles
 */
add_filter( 'wp_nav_menu_objects', function ( $items ) {
  foreach ( $items as &$item ) {
    $item->title = do_shortcode( $item->title );
  }
  return $items;
});
