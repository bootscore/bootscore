<?php

/**
 * Pagination
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Loop pagination
 */
if (!function_exists('bootscore_pagination_render')) :

  function bootscore_pagination_render($pages = '', $range = 2) {
    $showitems = ($range * 2) + 1;
    global $paged;
    if (empty($paged)) $paged = 1;
    if ($pages == '') {
      global $wp_query;
      $pages = $wp_query->max_num_pages;
      if (!$pages) {
        $pages = 1;
      }
    }

    if (1 != $pages) {
      echo '<nav aria-label="Page navigation">';
      echo '<span class="visually-hidden">' . esc_html__('Page navigation', 'bootscore') . '</span>';
      echo '<ul class="pagination justify-content-center mb-4">';

      if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
        echo '<li class="page-item"><a class="page-link" href="' . esc_url(get_pagenum_link(1)) . '" aria-label="' . esc_attr__('First Page', 'bootscore') . '">&laquo;</a></li>';
      }

      if ($paged > 1 && $showitems < $pages) {
        echo '<li class="page-item"><a class="page-link" href="' . esc_url(get_pagenum_link($paged - 1)) . '" aria-label="' . esc_attr__('Previous Page', 'bootscore') . '">&lsaquo;</a></li>';
      }

      for ($i = 1; $i <= $pages; $i++) {
        if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
          echo ($paged == $i)
            ? '<li class="page-item active"><span class="page-link"><span class="visually-hidden">' . esc_html__('Current Page', 'bootscore') . ' </span>' . esc_html($i) . '</span></li>'
            : '<li class="page-item"><a class="page-link" href="' . esc_url(get_pagenum_link($i)) . '"><span class="visually-hidden">' . esc_html__('Page', 'bootscore') . ' </span>' . esc_html($i) . '</a></li>';
        }
      }

      if ($paged < $pages && $showitems < $pages) {
        echo '<li class="page-item"><a class="page-link" href="' . esc_url(get_pagenum_link(($paged === 0 ? 1 : $paged) + 1)) . '" aria-label="' . esc_attr__('Next Page', 'bootscore') . '">&rsaquo;</a></li>';
      }

      if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) {
        echo '<li class="page-item"><a class="page-link" href="' . esc_url(get_pagenum_link($pages)) . '" aria-label="' . esc_attr__('Last Page', 'bootscore') . '">&raquo;</a></li>';
      }

      echo '</ul>';
      echo '</nav>';
    }
  }

endif;

add_action('bootscore_loop_pagination', 'bootscore_pagination_render');


/**
 * Pagination Single Posts
 */
add_filter('next_post_link', 'post_link_attributes');
add_filter('previous_post_link', 'post_link_attributes');

function post_link_attributes($output) {
  $code = 'class="page-link"';

  return str_replace('<a href=', '<a ' . $code . ' href=', $output);
}
