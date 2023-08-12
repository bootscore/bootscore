<?php

/**
 * Bootscore functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Bootscore
 */


// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;




require_once('inc/theme-setup.php');
require_once('inc/breadcrumb.php');
require_once('inc/pagination.php');
require_once('inc/widgets.php');








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















//Enqueue scripts and styles
function bootscore_scripts() {

  // Get modification time. Enqueue files with modification date to prevent browser from loading cached scripts and styles when file content changes.
  $modificated_bootscoreCss   = (file_exists(get_template_directory() . '/css/main.css')) ? date('YmdHi', filemtime(get_template_directory() . '/css/main.css')) : 1;
  $modificated_styleCss       = date('YmdHi', filemtime(get_stylesheet_directory() . '/style.css'));
  $modificated_fontawesomeCss = date('YmdHi', filemtime(get_template_directory() . '/fontawesome/css/all.min.css'));
  $modificated_bootstrapJs    = date('YmdHi', filemtime(get_template_directory() . '/js/lib/bootstrap.bundle.min.js'));
  $modificated_themeJs        = date('YmdHi', filemtime(get_template_directory() . '/js/theme.js'));

  // bootScore
  require_once 'inc/scss-compiler.php';
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
  // IE Warning End

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}

add_action('wp_enqueue_scripts', 'bootscore_scripts');
//Enqueue scripts and styles END


// Preload Font Awesome
add_filter('style_loader_tag', 'bootscore_fa_preload');

function bootscore_fa_preload($tag) {

  $tag = preg_replace("/id='fontawesome-css'/", "id='fontawesome-css' online=\"if(media!='all')media='all'\"", $tag);

  return $tag;
}

// Preload Font Awesome END


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


// Amount of posts/products in category
if (!function_exists('wpsites_query')) :

  function wpsites_query($query) {
    if ($query->is_archive() && $query->is_main_query() && !is_admin()) {
      $query->set('posts_per_page', 24);
    }
  }

  add_action('pre_get_posts', 'wpsites_query');

endif;
// Amount of posts/products in category END




// Excerpt to pages
add_post_type_support('page', 'excerpt');
// Excerpt to pages END





// Comment Button
if (!function_exists('bootscore_comment_button')) :
  function bootscore_comment_button($args) {
    $args['class_submit'] = 'btn btn-outline-primary'; // since WP 4.1

    return $args;
  }

  add_filter('comment_form_defaults', 'bootscore_comment_button');
endif;
// Comment Button END


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


// Hook after #primary
function bs_after_primary() {
  do_action('bs_after_primary');
}

// Hook after #primary END


// Open links in comments in new tab
if (!function_exists('bs_comment_links_in_new_tab')) :
  function bs_comment_links_in_new_tab($text) {
    return str_replace('<a', '<a target="_blank" rel=”nofollow”', $text);
  }

  add_filter('comment_text', 'bs_comment_links_in_new_tab');
endif;
// Open links in comments in new tab END


// Disable Gutenberg blocks in widgets (WordPress 5.8)
// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter('gutenberg_use_widgets_block_editor', '__return_false');
// Disables the block editor from managing widgets.
add_filter('use_widgets_block_editor', '__return_false');
// Disable Gutenberg blocks in widgets (WordPress 5.8) END
//


/*
 * Simple short code for inserting font awesome icons on Gutenberg leveli
 * (instead of heaving to insert HTML code into a block on HTML editing mode)
 */
function bsfaCode($atts) {
  $atts = (array) $atts;
  $vstr = "";
  foreach ($atts as $value) {
    $vstr = $vstr . " $value";
  }

  return '<i class="' . $vstr . '"></i>';
}

;
add_shortcode('bsfa', 'bsfaCode');
