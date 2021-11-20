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

  $compiler->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::COMPRESSED);

  if (bootscore_child_has_scss() && is_child_theme()) {
    $theme_directory = get_stylesheet_directory();
  } else {
    $theme_directory = get_template_directory();
  }

  $scss_file = $theme_directory . '/css/scss/bootstrap.min.scss';
  $css_file = $theme_directory . '/css/lib/bootstrap.min.css';

  $compiler->setImportPaths(dirname($scss_file));
  if (is_child_theme() && bootscore_child_has_scss()) {
    $compiler->addImportPath(get_template_directory() . '/css/scss/');
  }

  $last_modified = bootscore_get_last_modified_scss($theme_directory);
  $stored_modified = get_theme_mod('bootscore_scss_modified_timestamp', 0);

  try {
    if ($last_modified > $stored_modified || !file_exists($css_file)) {
      $compiled = $compiler->compileString(file_get_contents($scss_file));

      if (!file_exists(dirname($css_file))) {
        mkdir(dirname($css_file), 0755, true);
      }

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
function bootscore_get_last_modified_scss($theme_directory) {
  $directory = $theme_directory . '/css/scss/';
  $files = scandir($directory);
  $total_last_modified = 0;
  foreach ($files as $file) {
    if (strpos($file, '.scss') !== false || strpos($file, '.css') !== false) {
      $file_stats = stat($directory . $file);
      $total_last_modified += $file_stats['mtime'];
    }
  }
  $total_last_modified += stat(get_template_directory() . '/css/scss/bootstrap/bootstrap.scss')['mtime'];
  return $total_last_modified;
}

/**
 * Check if the child theme has scss files included.
 *
 * @return boolean True when child theme has scss files.
 */
function bootscore_child_has_scss() {
  return file_exists(get_stylesheet_directory() . '/css/scss/bootstrap.min.scss');
}
