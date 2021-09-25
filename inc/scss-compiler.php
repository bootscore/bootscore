<?php

/**
 * Functions to compile scss in the theme itself
 *
 * @package Bootscore
 */

require_once "scssphp/scss.inc.php";

use ScssPhp\ScssPhp\Compiler;

/**
 * Compiles the scss to a css file to be read by the browser.
 */
function bootscore_compile_scss() {
  $compiler = new Compiler();

  $scss_file = get_stylesheet_directory() . '/css/scss/bootstrap.min.scss';
  $css_file = get_stylesheet_directory() . '/css/lib/bootstrap.min.css';

  $compiler->setImportPaths(dirname($scss_file));
  if (is_child_theme()) {
    $compiler->addImportPath(get_template_directory() . '/css/scss/');
  }

  $last_modified = bootscore_get_last_modified_scss();
  $stored_modified = get_theme_mod('bootscore_scss_modified_timestamp', 0);

  try {
    if ($last_modified > $stored_modified) {
      $compiled = $compiler->compileString(file_get_contents($scss_file));
      file_put_contents($css_file, $compiled->getCss());

      set_theme_mod('bootscore_scss_modified_timestamp', $last_modified);
    }
  } catch (Exception $e) {
    wp_die('<b>bootScore SCSS Compiler - Caught exception:</b><br><br> ' . $e->getMessage());
  }
}


/**
 * Checks if the scss files and returns the last modified times added together.
 *
 * @return float Last modified times added together.
 */
function bootscore_get_last_modified_scss() {
  $directory = get_stylesheet_directory() . '/css/scss/';
  $files = scandir($directory);
  $total_last_modified = 0;
  foreach ($files as $file) {
    if (strpos($file, '.scss') !== false || strpos($file, '.css') !== false) {
      $file_stats = stat($directory . $file);
      $total_last_modified += $file_stats['mtime'];
    }
  }
  return $total_last_modified;
}
