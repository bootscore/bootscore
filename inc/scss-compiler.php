<?php

/**
 * Functions to compile scss in the theme itself
 *
 * @package Bootscore 
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


require_once "scssphp/scss.inc.php";

use ScssPhp\ScssPhp\Compiler;

/**
 * Compiles the scss to a css file to be read by the browser.
 */
function bootscore_compile_scss() {
  $disable_compiler = apply_filters('bootscore_scss_disable_compiler', (defined('BOOTSCORE_SCSS_DISABLE_COMPILER') && BOOTSCORE_SCSS_DISABLE_COMPILER));

  if ($disable_compiler) {
    return;
  }

  $compiler = new Compiler();

  if (bootscore_child_has_scss() && is_child_theme()) {
    $theme_directory = get_stylesheet_directory();
  } else {
    $theme_directory = get_template_directory();
  }

  $scss_file = $theme_directory . '/scss/main.scss';
  $css_file  = $theme_directory . '/css/main.css';

  $compiler->setImportPaths(dirname($scss_file));
  if (is_child_theme() && bootscore_child_has_scss()) {
    $compiler->addImportPath(get_template_directory() . '/scss/');
  }

  $last_modified   = bootscore_get_last_modified_scss($theme_directory);
  $stored_modified = get_theme_mod('bootscore_scss_modified_timestamp', 0);

  $is_environment_dev     = in_array(wp_get_environment_type(), array('development', 'local'), true);
  $skip_environment_check = apply_filters('bootscore_scss_skip_environment_check', (defined('BOOTSCORE_SCSS_SKIP_ENVIRONMENT_CHECK') && BOOTSCORE_SCSS_SKIP_ENVIRONMENT_CHECK));

  if ($is_environment_dev) {
    $compiler->setSourceMap(Compiler::SOURCE_MAP_FILE);
    $compiler->setSourceMapOptions([
      'sourceMapURL'      => site_url('', 'relative') . '/' . substr(str_replace(ABSPATH, '', $css_file), 0, - 3) . 'map',
      'sourceMapBasepath' => substr(str_replace('\\', '/', ABSPATH), 0, - 1),
      'sourceRoot'        => site_url('', 'relative') . '/',
    ]);
    $compiler->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::EXPANDED);
  } else {
    $compiler->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::COMPRESSED);
  }

  try {
    if ($last_modified > $stored_modified || !file_exists($css_file) || $is_environment_dev && !$skip_environment_check) {
      $compiled = $compiler->compileString(file_get_contents($scss_file));

      if (!file_exists(dirname($css_file))) {
        mkdir(dirname($css_file), 0755, true);
      }

      file_put_contents($css_file, $compiled->getCss());
      if ($is_environment_dev) {
        file_put_contents(substr($css_file, 0, - 3) . 'map', $compiled->getSourceMap());
      }

      set_theme_mod('bootscore_scss_modified_timestamp', $last_modified);
    }
  } catch (Exception $e) {
    if ($is_environment_dev) {
      wp_die('<b>bootScore SCSS Compiler - Caught exception:</b><br><br> ' . $e->getMessage());
    } else {
      wp_die('Something went wrong with the SCSS compiler.');
    }
  }
}


/**
 * Checks if the scss files and returns the last modified times added together.
 *
 * @return float Last modified times added together.
 */
function bootscore_get_last_modified_scss($theme_directory) {
  $directory           = $theme_directory . '/scss/';
  $files               = scandir($directory);
  $total_last_modified = 0;
  foreach ($files as $file) {
    if (strpos($file, '.scss') !== false || strpos($file, '.css') !== false) {
      $file_stats          = stat($directory . $file);
      $total_last_modified += $file_stats['mtime'];
    }
  }
  $total_last_modified += stat(get_template_directory() . '/scss/bootstrap/bootstrap.scss')['mtime'];

  return $total_last_modified;
}

/**
 * Check if the child theme has scss files included.
 *
 * @return boolean True when child theme has scss files.
 */
function bootscore_child_has_scss() {
  return file_exists(get_stylesheet_directory() . '/scss/main.scss');
}
