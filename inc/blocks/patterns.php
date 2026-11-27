<?php

/**
 * Patterns
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/*
 * Register pattern category
 *
 * The patterns in /patterns are built with the wrapper block (inc/blocks/block-wrap.php),
 * which outputs only the tag, classes and attributes that are set.
 */
function bootscore_pattern_category() {
  register_block_pattern_category(
    'bootscore',
    array( 'label' => __( 'Bootscore', 'bootscore' ) )
  );
}
add_action('init', 'bootscore_pattern_category');


/*
 * Inserted patterns are editable right away
 *
 * Since WordPress 7.0 the editor locks an inserted pattern to content-only editing: text and images can be changed,
 * the structure only after "Detach". Bootscore's patterns are starting points made to be changed, so the lock is off.
 * Turn it back on, for example for clients: add_filter('bootscore/block/patterns/content-only', '__return_true');
 */
function bootscore_patterns_editable($settings) {
  if (!apply_filters('bootscore/block/patterns/content-only', false)) {
    $settings['disableContentOnlyForUnsyncedPatterns'] = true;
  }

  return $settings;
}
add_filter('block_editor_settings_all', 'bootscore_patterns_editable');
