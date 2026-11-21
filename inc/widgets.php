<?php

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @package Bootscore 
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Register widgets
 */
if (!function_exists('bootscore_widgets_init')) :

  function bootscore_widgets_init() {
    
    // Top Bar
    register_sidebar(array(
      'name'          => esc_html__('Top Bar', 'bootscore'),
      'id'            => 'top-bar',
      'description'   => esc_html__('Above the header, full width.', 'bootscore'),
      'before_widget' => '<div id="%1$s" class="widget %2$s top-bar-widget">',
      'after_widget'  => '</div>',
      'before_title'  => '<div class="widget-title d-none">',
      'after_title'   => '</div>'
    ));  

    // Top Nav
    register_sidebar(array(
      'name'          => esc_html__('Top Nav', 'bootscore'),
      'id'            => 'top-nav',
      'description'   => esc_html__('In the header, next to the search, account and cart buttons.', 'bootscore'),
      'before_widget' => '<div id="%1$s" class="widget %2$s top-nav-widget ' . esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 md:ms-2', 'top-nav-widget')) . '">',
      'after_widget'  => '</div>',
      'before_title'  => '<div class="widget-title d-none">',
      'after_title'   => '</div>'
    ));

    // Top Nav 2
    // Adds a widget next to the Top Nav position but moves to offcanvas on <lg breakpoint
    register_sidebar(array(
      'name'          => esc_html__('Top Nav 2', 'bootscore'),
      'id'            => 'top-nav-2',
      'description'   => esc_html__('In the header, after the main menu. Inside the menu drawer on small screens.', 'bootscore'),
      'before_widget' => '<div id="%1$s" class="widget %2$s top-nav-widget-2 ' . esc_attr(apply_filters('bootscore/class/header/top-nav-widget-2', 'lg:d-flex lg:align-items-center mt-2 lg:mt-0 lg:ms-3')) . '">',
      'after_widget'  => '</div>',
      'before_title'  => '<div class="widget-title d-none">',
      'after_title'   => '</div>'
    ));

    // Top Nav Search
    register_sidebar(array(
      'name'          => esc_html__('Top Nav Search', 'bootscore'),
      'id'            => 'top-nav-search',
      'description'   => esc_html__('Search in the header. Opens from a search button on small screens.', 'bootscore'),
      'before_widget' => '<div id="%1$s" class="widget %2$s top-nav-search">',
      'after_widget'  => '</div>',
      'before_title'  => '<div class="widget-title d-none">',
      'after_title'   => '</div>'
    ));

    // Sidebar
    register_sidebar(array(
      'name'          => esc_html__('Sidebar', 'bootscore'),
      'id'            => 'sidebar-1',
      'description'   => esc_html__('Next to the content. A drawer on small screens. Leave empty to hide the sidebar.', 'bootscore'),
      'before_widget' => '<section id="%1$s" class="widget">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="widget-title h5">',
      'after_title'   => '</h2>',
    ));

    // Footer Top
    register_sidebar(array(
      'name'          => esc_html__('Footer Top', 'bootscore'),
      'id'            => 'footer-top',
      'description'   => esc_html__('Above the footer columns, full width.', 'bootscore'),
      'before_widget' => '<div id="%1$s" class="widget %2$s footer_widget">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>'
    ));

    // Footer 1
    register_sidebar(array(
      'name'          => esc_html__('Footer 1', 'bootscore'),
      'id'            => 'footer-1',
      'description'   => esc_html__('First footer column.', 'bootscore'),
      'before_widget' => '<div id="%1$s" class="widget %2$s footer_widget ' . esc_attr(apply_filters('bootscore/class/footer/col/spacer', 'mb-3', 'footer-1')) . '">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2 class="widget-title h5">',
      'after_title'   => '</h2>'
    ));

    // Footer 2
    register_sidebar(array(
      'name'          => esc_html__('Footer 2', 'bootscore'),
      'id'            => 'footer-2',
      'description'   => esc_html__('Second footer column.', 'bootscore'),
      'before_widget' => '<div id="%1$s" class="widget %2$s footer_widget ' . esc_attr(apply_filters('bootscore/class/footer/col/spacer', 'mb-3', 'footer-2')) . '">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2 class="widget-title h5">',
      'after_title'   => '</h2>'
    ));

    // Footer 3
    register_sidebar(array(
      'name'          => esc_html__('Footer 3', 'bootscore'),
      'id'            => 'footer-3',
      'description'   => esc_html__('Third footer column.', 'bootscore'),
      'before_widget' => '<div id="%1$s" class="widget %2$s footer_widget ' . esc_attr(apply_filters('bootscore/class/footer/col/spacer', 'mb-3', 'footer-3')) . '">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2 class="widget-title h5">',
      'after_title'   => '</h2>'
    ));

    // Footer 4
    register_sidebar(array(
      'name'          => esc_html__('Footer 4', 'bootscore'),
      'id'            => 'footer-4',
      'description'   => esc_html__('Fourth footer column.', 'bootscore'),
      'before_widget' => '<div id="%1$s" class="widget %2$s footer_widget ' . esc_attr(apply_filters('bootscore/class/footer/col/spacer', 'mb-3', 'footer-4')) . '">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2 class="widget-title h5">',
      'after_title'   => '</h2>'
    ));

    // Footer Info
    register_sidebar(array(
      'name'          => esc_html__('Footer Info', 'bootscore'),
      'id'            => 'footer-info',
      'description'   => esc_html__('Bottom footer bar, above the copyright.', 'bootscore'),
      'before_widget' => '<div id="%1$s" class="widget %2$s footer_widget">',
      'after_widget'  => '</div>',
      'before_title'  => '<div class="widget-title d-none">',
      'after_title'   => '</div>'
    ));

    // 404 Page
    register_sidebar(array(
      'name'          => esc_html__('404 Page', 'bootscore'),
      'id'            => '404-page',
      'description'   => esc_html__('On the 404 page. Replaces the "Page not found" alert.', 'bootscore'),
      'before_widget' => '<div id="%1$s" class="widget %2$s mb-4">',
      'after_widget'  => '</div>',
      'before_title'  => '<h2 class="widget-title h5">',
      'after_title'   => '</h2>'
    ));

  }

  add_action('widgets_init', 'bootscore_widgets_init');

endif;
