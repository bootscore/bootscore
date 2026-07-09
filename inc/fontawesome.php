<?php

/**
 * Font Awesome
 *
 * @package Bootscore
 * @version 6.5.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/*
 * Simple shortcode for inserting Font Awesome icons on Gutenberg level
 * (instead of heaving to insert HTML code into a block on HTML editing mode)
 */
function bsfaCode($atts) {
  $atts = (array) $atts;
  $vstr = "";
  foreach ($atts as $value) {
    $vstr .= " " . sanitize_html_class($value);
  }

  return '<i class="' . esc_attr(trim($vstr)) . '"></i>';
}
add_shortcode('bsfa', 'bsfaCode');


/**
 * Add fa-width-auto class to body
 * This reduces the left/right padding on icons and makes buttons narrower.
 *
 * @link https://docs.fontawesome.com/web/style/icon-canvas
 *
 * Use filter to disable it
 * add_filter( 'bootscore/class/fa_width_auto', '__return_false' );
 */
function bootscore_fa_auto_width( $classes ) {

  if ( apply_filters( 'bootscore/class/fa_width_auto', true ) ) {
    $classes[] = 'fa-width-auto';
  }

  return $classes;
}
add_filter( 'body_class', 'bootscore_fa_auto_width' );
