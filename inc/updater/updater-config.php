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


// Initialize the updater (no URL needed for GitHub-only setup)
global $bootscore_updater;

if (!isset($bootscore_updater)) {
  $bootscore_updater = new Bootscore_Update_Checker('', 12 * HOUR_IN_SECONDS);
}

// --- REGISTER THEME FROM GITHUB PUBLIC REPO ---
$theme = wp_get_theme('bootscore');

$bootscore_updater->register_product(array(
  'type' => 'theme',
  'slug' => 'bootscore',
  'current_version' => $theme->get('Version') ?? '1.0.0',
  'file' => 'update-theme-public-repo',
  'source' => 'github',
  'github_repo' => 'bopotscore/bootscore',
  'name' => 'Bootscore',
));
