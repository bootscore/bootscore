<?php

/**
 * Enqueue styles & scripts
 *
 * @package Bootscore 
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Enqueue scripts and styles
 */
function bootscore_scripts() {

  // Get modification time. Enqueue files with modification date to prevent browser from loading cached scripts and styles when file content changes.
  $modificated_bootscoreCss   = (file_exists(get_template_directory() . '/assets/css/bootscore.min.css')) ? date('YmdHi', filemtime(get_template_directory() . '/assets/css/bootscore.min.css')) : 1;
  $modificated_styleCss       = date('YmdHi', filemtime(get_stylesheet_directory() . '/style.css'));
  $modificated_bootscoreJs    = date('YmdHi', filemtime(get_template_directory() . '/assets/js/bootscore.min.js'));

  // Bootscore CSS
  wp_enqueue_style('bootscore-main', get_template_directory_uri() . '/assets/css/bootscore.min.css', array(), $modificated_bootscoreCss);

  // Style CSS
  wp_enqueue_style('bootscore-style', get_stylesheet_uri(), array(), $modificated_styleCss);

  // Bootscore JS
  wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootscore.min.js', array(), $modificated_bootscoreJs, true);

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}

add_action('wp_enqueue_scripts', 'bootscore_scripts');


/**
 * Register editor styles.
 */
function bootscore_add_editor_styles() {
  // Add support for editor styles and bootscore.min.css for the editor
  add_theme_support('editor-styles');
  add_editor_style('assets/css/bootscore.min.css');
}
add_action('after_setup_theme', 'bootscore_add_editor_styles');


/**
 * Enqueue styles for block editor and Pattern Library.
 */
function bootscore_enqueue_editor_and_pattern_library_styles($hook_suffix) {
  $screen = get_current_screen();
  
  // Enqueue editor.css only in the block editor
  if ($screen && $screen->is_block_editor) {
    wp_enqueue_style('editor-style', get_stylesheet_directory_uri() . '/assets/css/editor.css');
  }

  // Enqueue bootscore.min.css only in the Pattern Library
  if ('appearance_page_edit-wp-patterns' === $hook_suffix) {
    wp_enqueue_style('bootscore-pattern-library-styles', get_stylesheet_directory_uri() . '/assets/css/bootscore.min.css');
  }
}
add_action('admin_enqueue_scripts', 'bootscore_enqueue_editor_and_pattern_library_styles');
