<?php
/**
 * Bootscore Theme Update Configuration
 * Registers ONLY the theme for updates from GitHub
 *
 * @package Bootscore
 * @version 6.5.0
 */


// Exit if accessed directly.
defined('ABSPATH') || exit;


add_action('init', function() {
  global $bootscore_updater;

  // Ensure the class is loaded (fallback in case functions.php didn't already load it)
  if (!class_exists('Bootscore_Update_Checker')) {
    $class_path = get_template_directory() . '/inc/updater/class-update-checker.php';
    if (file_exists($class_path)) {
      require_once $class_path;
    }
  }

  // Initialize if needed
  if (!isset($bootscore_updater) && class_exists('Bootscore_Update_Checker')) {
    $bootscore_updater = new Bootscore_Update_Checker(12 * HOUR_IN_SECONDS);
  }

  // Bail if updater still isn't available
  if (!isset($bootscore_updater) || !class_exists('Bootscore_Update_Checker')) {
    return;
  }

  // --- REGISTER THEME FROM GITHUB PUBLIC REPO ---
  $theme = wp_get_theme('bootscore');

  $bootscore_updater->register_product(array(
    'type' => 'theme',
    'slug' => 'bootscore',
    'current_version' => $theme->get('Version') ?: '1.0.0',
    'source' => 'github',
    'github_repo' => 'bopotscore/bootscore', // TODO: confirm this is the correct org/repo
    'name' => 'Bootscore',
  ));
});
