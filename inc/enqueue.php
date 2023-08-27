<?php

/**
 * Enqueue styles & scripts
 *
 * @package Bootscore 
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Enqueue scripts and styles
 */
function bootscore_scripts() {

  // Get modification time. Enqueue files with modification date to prevent browser from loading cached scripts and styles when file content changes.
  $modificated_bootscoreCss   = (file_exists(get_template_directory() . '/css/main.css')) ? date('YmdHi', filemtime(get_template_directory() . '/css/main.css')) : 1;
  $modificated_styleCss       = date('YmdHi', filemtime(get_stylesheet_directory() . '/style.css'));
  $modificated_fontawesomeCss = date('YmdHi', filemtime(get_template_directory() . '/fontawesome/css/all.min.css'));
  $modificated_bootstrapJs    = date('YmdHi', filemtime(get_template_directory() . '/js/lib/bootstrap.bundle.min.js'));
  $modificated_themeJs        = date('YmdHi', filemtime(get_template_directory() . '/js/theme.js'));

  // bootScore
  require_once 'scss-compiler.php';
  bootscore_compile_scss();
  wp_enqueue_style('main', get_template_directory_uri() . '/css/main.css', array(), $modificated_bootscoreCss);

  // Style CSS
  wp_enqueue_style('bootscore-style', get_stylesheet_uri(), array(), $modificated_styleCss);

  // Fontawesome
  wp_enqueue_style('fontawesome', get_template_directory_uri() . '/fontawesome/css/all.min.css', array(), $modificated_fontawesomeCss);

  // Bootstrap JS
  wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/lib/bootstrap.bundle.min.js', array(), $modificated_bootstrapJs, true);

  // Theme JS
  wp_enqueue_script('bootscore-script', get_template_directory_uri() . '/js/theme.js', array('jquery'), $modificated_themeJs, true);

  // IE Warning
  wp_localize_script('bootscore-script', 'bootscore', array(
    'ie_title'                 => __('Internet Explorer detected', 'bootscore'),
    'ie_limited_functionality' => __('This website will offer limited functionality in this browser.', 'bootscore'),
    'ie_modern_browsers_1'     => __('Please use a modern and secure web browser like', 'bootscore'),
    'ie_modern_browsers_2'     => __(' <a href="https://www.mozilla.org/firefox/" target="_blank">Mozilla Firefox</a>, <a href="https://www.google.com/chrome/" target="_blank">Google Chrome</a>, <a href="https://www.opera.com/" target="_blank">Opera</a> ', 'bootscore'),
    'ie_modern_browsers_3'     => __('or', 'bootscore'),
    'ie_modern_browsers_4'     => __(' <a href="https://www.microsoft.com/edge" target="_blank">Microsoft Edge</a> ', 'bootscore'),
    'ie_modern_browsers_5'     => __('to display this site correctly.', 'bootscore'),
  ));

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}

add_action('wp_enqueue_scripts', 'bootscore_scripts');


/**
 * Preload Font Awesome
 */
add_filter('style_loader_tag', 'bootscore_fa_preload');

function bootscore_fa_preload($tag) {

  $tag = preg_replace("/id='fontawesome-css'/", "id='fontawesome-css' onload=\"if(media!='all')media='all'\"", $tag);

  return $tag;
}
