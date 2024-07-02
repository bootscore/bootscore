<?php

/**
 * Class with functions to compile SCSS files.
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

require_once "scssphp/scss.inc.php";

use ScssPhp\ScssPhp\Compiler;

class BootscoreScssCompiler {
  private $compiler;
  private bool $should_compile = false;
  private string $scss_file;
  private string $css_file;
  private string $theme_directory;
  private bool $skip_environment_check = false;
  private array $file_mtime_check = [];
  private int $files_mtime;
  private bool $is_environment_dev;
  private string $file_id;

  public function __construct() {
    $this->compiler               = new Compiler();
    $this->theme_directory        = ($this->shouldProcessChild()) ? get_stylesheet_directory() : get_template_directory();
    $this->skip_environment_check = apply_filters('bootscore/scss/skip_environment_check', (defined('BOOTSCORE_SCSS_SKIP_ENVIRONMENT_CHECK') && BOOTSCORE_SCSS_SKIP_ENVIRONMENT_CHECK));
    $this->is_environment_dev     = in_array(wp_get_environment_type(), array('development', 'local'), true);
  }

  public function scssFile(string $scss_file, $auto_set_css_file = true) {
    $this->scss_file = $scss_file;

    if ($auto_set_css_file) {
      $this->css_file = str_replace('scss', 'css', $scss_file);
    }

    return $this;
  }

  public function cssFile(string $css_file) {
    $this->css_file = $css_file;

    return $this;
  }

  public function getScssFile() {
    return $this->theme_directory . $this->scss_file;
  }

  public function getCssFile() {
    return $this->theme_directory . $this->css_file;
  }

  public function addImportPath(string $import_path) {
    $this->compiler->addImportPath($import_path);

    return $this;
  }

  public function addModifiedCheck($file, $prefix_theme_directory = true) {
    $this->file_mtime_check[] = ($prefix_theme_directory) ? $this->theme_directory . $file : $file;

    return $this;
  }

  public function addModifiedSelf() {
    $this->addModifiedCheck($this->scss_file);

    return $this;
  }

  public function addModifiedCheckDir($dir, $prefix_theme_directory = true) {
    $dir   = ($prefix_theme_directory) ? $this->theme_directory . $dir : $dir;
    $files = glob($dir . '/*');
    foreach ($files as $file) {
      // check if file is a scss file
      if (pathinfo($file, PATHINFO_EXTENSION) !== 'scss') {
        continue;
      }
      $this->addModifiedCheck($file, false);
    }

    return $this;
  }

  public function addModifiedCheckTheme() {
    $this->addModifiedCheckDir('/assets/scss');

    return $this;
  }

  private function generateId($input, $length = 8) {
    return substr(md5($input), 0, $length);
  }

  private function shouldProcessChild() {
    return is_child_theme() && bootscore_child_has_scss();
  }

  private function addImportPaths() {
    $this->compiler->setImportPaths(dirname($this->theme_directory . $this->scss_file));

    if ($this->shouldProcessChild()) {
      $this->compiler->addImportPath(get_template_directory() . '/assets/scss/');
    }
  }

  private function setOutputStyle() {
    if ($this->is_environment_dev) {
      $source_map_url = site_url('', 'relative') . '/' . ltrim(str_replace(ABSPATH, '', $this->getCssFile()), '/');
      $source_map_url .= '.map';

      $this->compiler->setSourceMap(Compiler::SOURCE_MAP_FILE);
      $this->compiler->setSourceMapOptions([
        'sourceMapURL'      => $source_map_url,
        'sourceMapBasepath' => rtrim(str_replace('\\', '/', ABSPATH), '/'),
        'sourceRoot'        => site_url('', 'relative') . '/',
      ]);
      $this->compiler->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::EXPANDED);
    } else {
      $this->compiler->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::COMPRESSED);
    }
  }

  private function getModifiedTime() {
    $this->files_mtime = 0;
    foreach ($this->file_mtime_check as $file) {
      $this->files_mtime = max($this->files_mtime, filemtime($file));
    }
  }

  private function processModifiedCheck() {
    $this->getModifiedTime();

    $stored_modified = get_theme_mod('bootscore_scss_modified_timestamp_' . $this->file_id, 0);

    if ($this->files_mtime > $stored_modified) {
      $this->should_compile = true;
    }
  }

  private function extraChecks() {
    if ($this->is_environment_dev && !$this->skip_environment_check) {
      $this->should_compile = true;
    }

    if (!file_exists($this->getCssFile())) {
      $this->should_compile = true;
    }

    if (apply_filters('bootscore/scss/disable_compiler', (defined('BOOTSCORE_SCSS_DISABLE_COMPILER') && BOOTSCORE_SCSS_DISABLE_COMPILER))) {
      $this->should_compile = false;
    }
  }

  public function compile() {
    $this->addImportPaths();
    $this->setOutputStyle();
    $this->file_id = $this->generateId($this->scss_file);
    $this->addModifiedSelf();
    if (!empty($this->file_mtime_check)) {
      $this->processModifiedCheck();
    }
    $this->extraChecks();

    if (!$this->should_compile) {
      return;
    }

    $this->compiler = apply_filters('bootscore/scss/compiler', $this->compiler);

    try {
      $compiled = $this->compiler->compileString(file_get_contents($this->getScssFile()));

      if (!file_exists(dirname($this->getCssFile()))) {
        mkdir(dirname($this->getCssFile()), 0755, true);
      }

      file_put_contents($this->getCssFile(), $compiled->getCss());
      if ($this->is_environment_dev) {
        file_put_contents($this->getCssFile() . '.map', $compiled->getSourceMap());
      }

      if (!empty($this->file_mtime_check)) {
        set_theme_mod('bootscore_scss_modified_timestamp_' . $this->file_id, $this->files_mtime);
      }
    } catch (Exception $e) {
      if ($this->is_environment_dev) {
        wp_die('<b>Bootscore SCSS Compiler - Caught exception:</b><br><br> ' . $e->getMessage());
      } else {
        wp_die('Something went wrong with the SCSS compiler.');
      }
    }
  }
}


/**
 * Check if the child theme has scss files included.
 *
 * @return boolean True when child theme has scss files.
 */
function bootscore_child_has_scss() {
  return file_exists(get_stylesheet_directory() . '/assets/scss/main.scss');
}

function bootscore_compile_scss() {
  $scss_compiler = new BootscoreScssCompiler();
  $scss_compiler->scssFile('/assets/scss/main.scss')
                ->cssFile('/assets/css/main.css')
                ->addModifiedCheckTheme()
                ->addModifiedCheck(get_template_directory() . '/assets/scss/bootstrap/bootstrap.scss', false)
                ->compile();
}