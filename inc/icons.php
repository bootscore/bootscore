<?php

/**
 * Icons
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Allowed HTML for inline SVG icons output via bootscore/icon/* filters.
 * Extends the standard 'post' allowed tags with svg + path.
 */
function bootscore_kses_allowed_svg( $tags = array() ) {

  $svg_tags = array(
    'svg' => array(
      'class'    => true,
      'xmlns'    => true,
      'viewbox'  => true, // matched case-insensitively, output keeps original casing
      'width'    => true,
      'height'   => true,
      'fill'     => true,
      'aria-hidden' => true,
      'role'     => true,
      'focusable' => true,
    ),
    'path' => array(
      'd'    => true,
      'fill' => true,
    ),
  );

  return array_merge( $tags, $svg_tags );

}