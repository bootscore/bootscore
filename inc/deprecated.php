<?php

/**
 * Deprecated
 *
 * @package Bootscore 
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


// Remove in v6
// Internet Explorer Warning Alert
if (!function_exists('bootscore_ie_alert')) :
  /**
   * Deprecated - functionality is removed already - Code will be removed in a future release.
   * Replaced with a js solution to prevent page caching
   *
   * (Displays an alert if page is browsed by Internet Explorer)
   *
   * function stays to not break child themes with the function bootscore_ie_alert() immediately
   */
  function bootscore_ie_alert() {
  }
endif;


// If we want to publish in WordPress theme repository, we have to delete this because shortcode is plugin area
/*
 * Simple short code for inserting font awesome icons on Gutenberg leveli
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
