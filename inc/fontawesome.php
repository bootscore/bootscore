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
    $vstr = $vstr . " " . sanitize_html_class($value);
  }

  return '<i class="' . esc_attr(trim($vstr)) . '"></i>';
}

;
add_shortcode('bsfa', 'bsfaCode');
