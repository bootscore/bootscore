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
