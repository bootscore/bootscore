<?php
/*
 * (c) 2023 Karsten Reincke
 *  SPDX-License-Identifier: MIT
 */
function insert_bsfa($atts){
  $atts = (array) $atts;
  $vstr = "";
  foreach ( $atts as $value ) {
  $vstr = $vstr . " $value";
  }
  return '<i class="' . $vstr . '"></i>';
 }
add_shortcode('bsfa', 'insert_bsfa');