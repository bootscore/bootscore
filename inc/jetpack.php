<?php

/**
 * Jetpack Compatibility File
 *
 * @link https://jetpack.com/
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Jetpack setup function.
 *
 * Infinite Scroll is not supported, use the bs Load More plugin instead.
 *
 * See: https://jetpack.com/support/responsive-videos/
 * See: https://jetpack.com/support/content-options/
 */
function bootscore_jetpack_setup() {

  // Add theme support for Responsive Videos.
  add_theme_support('jetpack-responsive-videos');

  // Add theme support for Content Options.
  add_theme_support('jetpack-content-options', array(
    'post-details'    => array(
      'stylesheet' => 'bootscore',
      'date'       => '.posted-on',
      'categories' => '.category-badge',
      'tags'       => '.tags-badge',
      'author'     => '.byline',
      'comment'    => '.comments-link',
    ),
    'featured-images' => array(
      'archive' => true,
      'post'    => true,
      'page'    => true,
    ),
  ));
}

add_action('after_setup_theme', 'bootscore_jetpack_setup');
