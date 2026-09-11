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
 * Enqueue editor styles.
 */
function bootscore_add_editor_styles() {
  // Add support for editor styles and bootscore.min.css for the editor
  add_theme_support('editor-styles');
  add_editor_style('assets/css/bootscore.min.css');
}
add_action('after_setup_theme', 'bootscore_add_editor_styles');


/**
 * Enqueue only the :root CSS custom properties (colors) from bootscore.min.css
 * on admin pages, so theme.json's var(--bs-*) references resolve for swatches
 * and color pickers rendered outside the block editor's content iframe.
 */
function bootscore_enqueue_editor_color_vars() {
  $screen = get_current_screen();

  if ( ! $screen || ! $screen->is_block_editor() ) {
    return;
  }

  $css_path = get_stylesheet_directory() . '/assets/css/bootscore.min.css';

  if ( ! file_exists( $css_path ) ) {
    return;
  }

  $css = file_get_contents( $css_path );

  preg_match_all( '/([^\{\}]+)\{([^\{\}]*)\}/', $css, $rules, PREG_SET_ORDER );

  $inline_css = '';

  foreach ( $rules as $rule ) {
    if ( strpos( $rule[1], ':root' ) !== false ) {
      $inline_css .= $rule[0] . ' ';
    }
  }

  if ( '' !== $inline_css ) {
    wp_register_style( 'bootscore-editor-color-vars', false );
    wp_enqueue_style( 'bootscore-editor-color-vars' );
    wp_add_inline_style( 'bootscore-editor-color-vars', $inline_css );
  }
}
add_action( 'admin_enqueue_scripts', 'bootscore_enqueue_editor_color_vars' );
