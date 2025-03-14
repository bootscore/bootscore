<?php

/**
 * TinyMCE Editor
 *
 * @package Bootscore 
 * @version 6.1.1
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

/*
 * Fix TinyMCE editor body margin and font-family that is overridden by Bootstrap editor-styles
 * https://github.com/bootscore/bootscore/issues/442
 * https://github.com/bootscore/bootscore/pull/757
 * https://github.com/bootscore/bootscore/issues/934
 */
add_filter( 'tiny_mce_before_init', function( $settings ) {

  $content_style = 'body.mce-content-body { margin: 9px 10px; font-family: inherit; height: auto; color: inherit; background: inherit; background-color: inherit; background-image: none; }';

  if ( isset( $settings['content_style'] ) ) {
    $settings['content_style'] .= ' ' . $content_style;
  } else {
    $settings['content_style'] = $content_style;
  }

  return $settings;
});
