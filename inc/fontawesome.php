<?php

/**
 * Font Awesome
 *
 * @package Bootscore
 * @version 6.0.0
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
    $vstr = $vstr . " $value";
  }

  return '<i class="' . $vstr . '"></i>';
}

;
add_shortcode('bsfa', 'bsfaCode');
