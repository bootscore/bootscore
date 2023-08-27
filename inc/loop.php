<?php

/**
 * Loop
 *
 * @package Bootscore 
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Amount of posts/products in category
 */
if (!function_exists('wpsites_query')) :

  function wpsites_query($query) {
    if ($query->is_archive() && $query->is_main_query() && !is_admin()) {
      $query->set('posts_per_page', 24);
    }
  }

  add_action('pre_get_posts', 'wpsites_query');

endif;
