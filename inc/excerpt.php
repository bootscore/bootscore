<?php

/**
 * Excerpt
 *
 * @package Bootscore
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Excerpt to pages
 */
add_post_type_support('page', 'excerpt');
