<?php

/**
 * Pagination
 *
 * @package Bootscore
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Pagination Categories
 */
if (!function_exists('bootscore_pagination')) :

  function bootscore_pagination($pages = '', $range = 2) {
    $showitems = ($range * 2) + 1;
    global $paged;
    // default page to one if not provided
    if (empty($paged)) $paged = 1;
    if ($pages == '') {
      global $wp_query;
      $pages = $wp_query->max_num_pages;

      if (!$pages) {
        $pages = 1;
      }
    }

    if (1 != $pages) {
      echo '<nav aria-label="Page navigation" role="navigation">';
      echo '<span class="sr-only">' . esc_html__('Page navigation', 'bootscore') . '</span>';
      echo '<ul class="pagination justify-content-center mb-4">';

      if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
        echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link(1) . '" aria-label="' . esc_html__('First Page', 'bootscore') . '">&laquo;</a></li>';
      }

      if ($paged > 1 && $showitems < $pages) {
        echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($paged - 1) . '" aria-label="' . esc_html__('Previous Page', 'bootscore') . '">&lsaquo;</a></li>';
      }

      for ($i = 1; $i <= $pages; $i ++) {
        if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
          echo ($paged == $i) ? '<li class="page-item active"><span class="page-link"><span class="sr-only">' . __('Current Page', 'bootscore') . ' </span>' . $i . '</span></li>' : '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($i) . '"><span class="sr-only">' . __('Page', 'bootscore') . ' </span>' . $i . '</a></li>';
        }
      }

      if ($paged < $pages && $showitems < $pages) {
        echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link(($paged === 0 ? 1 : $paged) + 1) . '" aria-label="' . esc_html__('Next Page', 'bootscore') . '">&rsaquo;</a></li>';
      }

      if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) {
        echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($pages) . '" aria-label="' . esc_html__('Last Page', 'bootscore') . '">&raquo;</a></li>';
      }

      echo '</ul>';
      echo '</nav>';
      // Uncomment this if you want to show [Page 2 of 30]
      // echo '<div class="pagination-info mb-5 text-center">[ <span class="text-muted">' . __('Page', 'bootscore') . '</span> '.$paged.' <span class="text-muted">' . __('of', 'bootscore') . '</span> '.$pages.' ]</div>';
    }
  }

endif;


/**
 * Pagination Single Posts
 */
add_filter('next_post_link', 'post_link_attributes');
add_filter('previous_post_link', 'post_link_attributes');

function post_link_attributes($output) {
  $code = 'class="page-link"';

  return str_replace('<a href=', '<a ' . $code . ' href=', $output);
}
