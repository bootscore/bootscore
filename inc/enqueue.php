<?php

/**
 * Enqueue styles & scripts
 *
 * @package Bootscore 
 * @version 5.3.4
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


define('DIST_DIR', 'dist');
define('DIST_URI', get_template_directory_uri() . '/' . DIST_DIR);
define('DIST_PATH', get_template_directory() . '/' . DIST_DIR);

// default server address, port, and entry point can be customized in vite.config.js
//TODO: suggest using .env vars & load it by /vlucas/phpdotenv
define('VITE_ENV', 'development');
define('VITE_SERVER', 'http://localhost:5173'); 
define('VITE_ENTRY_POINT', '/assets/js/theme.js');

/**
 * Enqueue scripts and styles
 */
function bootscore_scripts()
{

  // Get modification time. Enqueue files with modification date to prevent browser from loading cached scripts and styles when file content changes.
  $manifestFilePath = DIST_PATH . '/.vite/manifest.json';
  $manifest = json_decode(file_get_contents($manifestFilePath), true);
  $distEntry = $manifest['assets/js/theme.js'];
  $cssDistFile = ($distEntry && is_array($distEntry)) && DIST_URI . '/' . $distEntry['css'][0];
  $modificated_styleCss = (file_exists($cssDistFile)) && date('YmdHi', filemtime($cssDistFile));
  $modificated_styleCss = date('YmdHi', filemtime(get_stylesheet_directory() . '/style.css'));
  $modificated_bootscoreCss = (file_exists($cssDistFile)) ? date('YmdHi', filemtime($cssDistFile)) : 1;
 
  // bootScore
  if (defined('VITE_ENV') && VITE_ENV === 'development') {
    function vite_head_module_hook()
    {
      echo '<script type="module" crossorigin src="' . VITE_SERVER . '/@vite/client"></script>';
      echo '<script type="module" crossorigin src="' . VITE_SERVER . VITE_ENTRY_POINT . '"></script>';
    }
    add_action('wp_head', 'vite_head_module_hook');
  } 
 
  if (is_array($manifest) && is_array($distEntry) && VITE_ENV === 'production') {
    // CSS
    wp_enqueue_style('main', DIST_URI . '/' . $distEntry['css'][0], array(), $modificated_bootscoreCss);
    wp_enqueue_style('bootscore-style', get_stylesheet_uri(), array(), $modificated_styleCss);

    // JS
    wp_enqueue_script('main', DIST_URI . '/' . $distEntry['file'], array());
  }

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}

add_action('wp_enqueue_scripts', 'bootscore_scripts');


/**
 * Preload Font Awesome
 */
add_filter('style_loader_tag', 'bootscore_fa_preload');

function bootscore_fa_preload($tag)
{

  $tag = preg_replace("/id='fontawesome-css'/", "id='fontawesome-css' onload=\"if(media!='all')media='all'\"", $tag);

  return $tag;
}
