<?php

/**
 * Functions to compile scss in the theme itself
 *
 * @package Bootscore
 */

require_once "scssphp/scss.inc.php";

use ScssPhp\ScssPhp\Compiler;

function bootscore_compile_scss() {
  $compiler = new Compiler();

  $scss_file = get_stylesheet_directory() . '/css/lib/main.scss';
  $css_file = get_stylesheet_directory() . '/css/main.css';

  $compiler->setImportPaths(dirname($scss_file));
  $compiled = $compiler->compileString(file_get_contents($scss_file));
  file_put_contents($css_file, $compiled->getCss());
  // TODO: write the compiler
}

bootscore_compile_scss();