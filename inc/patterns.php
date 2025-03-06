<?php

/**
 * Patterns
 *
 * @package Bootscore
 * @version 6.1.1
 */


// Exit if accessed directly
defined('ABSPATH') || exit;



/*
 * Register pattern category
 */ 
function bootscore_pattern_category() {
  register_block_pattern_category(
    'bootscore',
    array( 'label' => __( 'Bootscore', 'bootscore' ) )
  );
}
add_action('init', 'bootscore_pattern_category');