<?php

/**
 * Required WP classes on <body>
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * word-break: break-word is required for the WP Theme Unit Test Data
 * https://dev.bootscore.me/about/page-markup-and-formatting/
 *
 * Change class:
 * add_filter( 'bootscore/class/body', function( $class ) {
 *   return 'my-custom-class';
 * });
 */
function bootscore_wp_body_class( $classes ) {

  $classes[] = apply_filters( 'bootscore/class/body', 'text-break' );

  return $classes;
}
add_filter( 'body_class', 'bootscore_wp_body_class' );
