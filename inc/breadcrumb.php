<?php

/**
 * Breadcrumb
 *
 * @package Bootscore
 * @version 6.4.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Breadcrumb
 * @link https://github.com/bootscore/bootscore/pull/1150
 */
if (!function_exists('the_breadcrumb')) :
  function the_breadcrumb() {
    if (is_home()) {
      return;
    }
    
    // Allow complete override of breadcrumb output
    $breadcrumb_output = apply_filters('bootscore_breadcrumb_output', null);
    if ($breadcrumb_output !== null) {
      echo $breadcrumb_output;
      return;
    }
    
    echo '<nav aria-label="breadcrumb" class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/nav', 'overflow-x-auto text-nowrap mb-4 mt-2 py-2 px-3 bg-body-tertiary rounded')) . '">' . PHP_EOL;
    echo '<ol class="breadcrumb ' . esc_attr(apply_filters('bootscore/class/breadcrumb/ol', 'flex-nowrap mb-0')) . '">' . PHP_EOL;
    
    // Home link
    echo '<li class="breadcrumb-item"><a aria-label="' . esc_attr__('Home', 'bootscore') . '" class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url(home_url()) . '">' . wp_kses_post(apply_filters('bootscore/icon/home', '<i class="fa-solid fa-house" aria-hidden="true"></i>')) . '<span class="visually-hidden">' . esc_html__('Home', 'bootscore') . '</span></a></li>' . PHP_EOL;
    
    /**
     * Hook before breadcrumb items are rendered
     * 
     * @since 6.4.0
     */
    do_action('bootscore_breadcrumb_before_items');
    
    /**
     * Action hook for adding custom breadcrumb conditions
     * 
     * @since 6.4.0
     * @param WP_Post|WP_Term|null $queried_object The queried object
     * @param string $post_type Current post type (if applicable)
     * 
     * Example usage:
     * add_action('bootscore_breadcrumb_conditions', function($queried_object, $post_type) {
     *   if (is_post_type_archive('portfolio')) {
     *     echo '<li class="breadcrumb-item active" aria-current="page">Portfolio</li>';
     *   }
     * }, 10, 2);
     */
    do_action('bootscore_breadcrumb_conditions', get_queried_object(), get_post_type());
    
    // Category archive
    if (is_category()) {
      $current_cat_id = get_queried_object_id();
      if ($current_cat_id) {
        $ancestors = array_reverse(get_ancestors($current_cat_id, 'category'));
        foreach ($ancestors as $ancestor_id) {
          $ancestor = get_category($ancestor_id);
          if ($ancestor && !is_wp_error($ancestor)) {
            echo '<li class="breadcrumb-item"><a class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url(get_term_link($ancestor)) . '">' . esc_html($ancestor->name) . '</a></li>' . PHP_EOL;
          }
        }
        // current category as text only
        echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(single_cat_title('', false)) . '</li>' . PHP_EOL;
      }
    }
    // Single post (exclude products - they're handled by WooCommerce hook)
    elseif (is_single() && get_post_type() !== 'product') {
      $cat_ids = wp_get_post_categories(get_the_ID());
      foreach ($cat_ids as $cat_id) {
        $cat = get_category($cat_id);
        if ($cat && !is_wp_error($cat)) {
          echo '<li class="breadcrumb-item"><a class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url(get_term_link($cat)) . '">' . esc_html($cat->name) . '</a></li>' . PHP_EOL;
        }
      }
      echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>' . PHP_EOL;
    }
    // Page
    elseif (is_page()) {
      echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>' . PHP_EOL;
    }
    
    /**
     * Hook after breadcrumb items are rendered
     * 
     * @since 6.4.0
     */
    do_action('bootscore_breadcrumb_after_items');
    
    /**
     * Post type specific hook
     * Fires for single posts and custom post types
     * 
     * @since 6.4.0
     */
    if (is_singular()) {
      do_action('bootscore_breadcrumb_post_type_' . get_post_type());
    }
    
    echo '</ol>' . PHP_EOL;
    echo '</nav>' . PHP_EOL;
  }
  add_filter('breadcrumbs', 'breadcrumbs');
endif;
