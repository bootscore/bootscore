<?php

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @package Bootscore 
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Disable Gutenberg blocks in widgets (WordPress 5.8)
 */
// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );


/**
 * Register widgets
 */
if (!function_exists('bootscore_widgets_init')) :

  function bootscore_widgets_init() {

    // Top Nav
    register_sidebar(array(
      'name'          => esc_html__('Top Nav', 'bootscore'),
      'id'            => 'top-nav',
      'description'   => esc_html__('Add widgets here.', 'bootscore'),
      'before_widget' => '<div class="top-nav-widget ms-1 ms-md-2">',
      'after_widget'  => '</div>',
      'before_title'  => '<div class="widget-title d-none">',
      'after_title'   => '</div>'
    ));

    // Top Nav 2
    // Adds a widget next to the Top Nav position but moves to offcanvas on <lg breakpoint
    register_sidebar(array(
      'name'          => esc_html__('Top Nav 2', 'bootscore'),
      'id'            => 'top-nav-2',
      'description'   => esc_html__('Add widgets here.', 'bootscore'),
      'before_widget' => '<div class="top-nav-widget-2 d-lg-flex align-items-lg-center mt-2 mt-lg-0 ms-lg-2">',
      'after_widget'  => '</div>',
      'before_title'  => '<div class="widget-title d-none">',
      'after_title'   => '</div>'
    ));

    // Top Nav Search
    register_sidebar(array(
      'name'          => esc_html__('Top Nav Search', 'bootscore'),
      'id'            => 'top-nav-search',
      'description'   => esc_html__('Add widgets here.', 'bootscore'),
      'before_widget' => '<div class="top-nav-search">',
      'after_widget'  => '</div>',
      'before_title'  => '<div class="widget-title d-none">',
      'after_title'   => '</div>'
    ));

    // Sidebar
    register_sidebar(array(
      'name'          => esc_html__('Sidebar', 'bootscore'),
      'id'            => 'sidebar-1',
      'description'   => esc_html__('Add widgets here.', 'bootscore'),
      'before_widget' => '<section id="%1$s" class="widget %2$s card card-body mb-4">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="widget-title card-header h5">',
      'after_title'   => '</h2>',
    ));

    // Top Footer
    register_sidebar(array(
      'name'          => esc_html__('Top Footer', 'bootscore'),
      'id'            => 'top-footer',
      'description'   => esc_html__('Add widgets here.', 'bootscore'),
      'before_widget' => '<div class="footer_widget mb-5">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>'
    ));

    // Footer 1
    register_sidebar(array(
      'name'          => esc_html__('Footer 1', 'bootscore'),
      'id'            => 'footer-1',
      'description'   => esc_html__('Add widgets here.', 'bootscore'),
      'before_widget' => '<div class="footer_widget mb-4">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2 class="widget-title h5">',
      'after_title'   => '</h2>'
    ));

    // Footer 2
    register_sidebar(array(
      'name'          => esc_html__('Footer 2', 'bootscore'),
      'id'            => 'footer-2',
      'description'   => esc_html__('Add widgets here.', 'bootscore'),
      'before_widget' => '<div class="footer_widget mb-4">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2 class="widget-title h5">',
      'after_title'   => '</h2>'
    ));

    // Footer 3
    register_sidebar(array(
      'name'          => esc_html__('Footer 3', 'bootscore'),
      'id'            => 'footer-3',
      'description'   => esc_html__('Add widgets here.', 'bootscore'),
      'before_widget' => '<div class="footer_widget mb-4">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2 class="widget-title h5">',
      'after_title'   => '</h2>'
    ));

    // Footer 4
    register_sidebar(array(
      'name'          => esc_html__('Footer 4', 'bootscore'),
      'id'            => 'footer-4',
      'description'   => esc_html__('Add widgets here.', 'bootscore'),
      'before_widget' => '<div class="footer_widget mb-4">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2 class="widget-title h5">',
      'after_title'   => '</h2>'
    ));

    // Footer Info
    register_sidebar(array(
      'name'          => esc_html__('Footer Info', 'bootscore'),
      'id'            => 'footer-info',
      'description'   => esc_html__('Add widgets here.', 'bootscore'),
      'before_widget' => '<div class="footer_widget">',
      'after_widget'  => '</div>',
      'before_title'  => '<div class="widget-title d-none">',
      'after_title'   => '</div>'
    ));

    // 404 Page
    register_sidebar(array(
      'name'          => esc_html__('404 Page', 'bootscore'),
      'id'            => '404-page',
      'description'   => esc_html__('Add widgets here.', 'bootscore'),
      'before_widget' => '<div class="mb-4">',
      'after_widget'  => '</div>',
      'before_title'  => '<h1 class="widget-title">',
      'after_title'   => '</h1>'
    ));

  }

  add_action('widgets_init', 'bootscore_widgets_init');

endif;


/**
 * Enable shortcodes in HTML-Widget
 */
add_filter('widget_text', 'do_shortcode');
