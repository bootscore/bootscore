<?php

/**
 * Breadcrumb
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Breadcrumb
 */
if (!function_exists('the_breadcrumb')) :
  function the_breadcrumb() {

    if (!is_home()) {
      echo '<nav aria-label="breadcrumb" class="overflow-x-auto text-nowrap mb-4 mt-2 py-2 px-3 bg-body-tertiary rounded">';
      echo '<ol class="breadcrumb flex-nowrap mb-0">';
      echo '<li class="breadcrumb-item"><a href="' . home_url() . '">' . '<i class="fa-solid fa-house"></i><span class="visually-hidden">' . __('Home', 'bootscore') . '</span>' . '</a></li>';
      // display parent category names with links
      if (is_category() || is_single()) {
        $cat_IDs = wp_get_post_categories(get_the_ID());
        foreach ($cat_IDs as $cat_ID) {
          $cat = get_category($cat_ID);
          echo '<li class="breadcrumb-item"><a href="' . get_term_link($cat->term_id) . '">' . $cat->name . '</a></li>';
        }
      }
      // display current page name
      if (is_page() || is_single()) {
        echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
      }
      echo '</ol>';
      echo '</nav>';
    }
  }

  add_filter('breadcrumbs', 'breadcrumbs');
endif;
