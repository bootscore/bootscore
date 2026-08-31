<?php

/**
 * Breadcrumb
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Breadcrumb
 */
if (!function_exists('the_breadcrumb')) :
  function the_breadcrumb() {

    if (is_home()) {
      return;
    }

    echo '<nav aria-label="breadcrumb" class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/nav', 'overflow-x-auto text-nowrap mb-4 mt-2 py-2 px-3 bg-body-tertiary rounded')) . '">' . PHP_EOL;
    echo '<ol class="breadcrumb ' . esc_attr(apply_filters('bootscore/class/breadcrumb/ol', 'flex-nowrap mb-0')) . '">' . PHP_EOL;

    // Home link
    echo '<li class="breadcrumb-item"><a aria-label="' . esc_attr__('Home', 'bootscore') . '" class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url(home_url()) . '">' . wp_kses( apply_filters('bootscore/icon/home', '<svg class="bs-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" aria-hidden="true"><path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>'), bootscore_kses_allowed_svg( wp_kses_allowed_html( 'post' ) ) ) . '<span class="visually-hidden">' . esc_html__('Home', 'bootscore') . '</span></a></li>' . PHP_EOL;
    
    // Hook for custom breadcrumb handlers (WooCommerce, other CPTs, etc.)
    // If any handler returns true, it means it handled the breadcrumb and we should stop
    $handled = apply_filters('bootscore/breadcrumb/handler', false);
    
    if (!$handled) {
      // ===== DEFAULT WORDPRESS PAGES =====
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
      
      // Custom Post Type Archive (default handling)
      elseif (is_post_type_archive()) {
        $archive_title = preg_replace('/^\w+: /', '', get_the_archive_title());
        echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(wp_strip_all_tags($archive_title)) . '</li>' . PHP_EOL;
      }
      
      // Single post (regular posts and custom post types)
      elseif (is_single()) {
        $post_type = get_post_type();
        $post_type_obj = get_post_type_object($post_type);
        
        // Show CPT archive link if it has an archive (default CPT handling)
        if ($post_type !== 'post' && $post_type_obj && $post_type_obj->has_archive) {
          $archive_link = get_post_type_archive_link($post_type);
          $archive_title = $post_type_obj->labels->name;
          
          if ($archive_link) {
            echo '<li class="breadcrumb-item"><a class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url($archive_link) . '">' . esc_html($archive_title) . '</a></li>' . PHP_EOL;
          }
        }
        // Regular posts - show categories
        elseif ($post_type === 'post') {
          $cat_ids = wp_get_post_categories(get_the_ID());
          foreach ($cat_ids as $cat_id) {
            $cat = get_category($cat_id);
            if ($cat && !is_wp_error($cat)) {
              echo '<li class="breadcrumb-item"><a class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url(get_term_link($cat)) . '">' . esc_html($cat->name) . '</a></li>' . PHP_EOL;
            }
          }
        }
        
        // Current post title
        echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>' . PHP_EOL;
      }
      
      // Pages, handle parent pages and current page
      elseif (is_page()) {
        $parent_id = wp_get_post_parent_id(get_the_ID());
        
        if ($parent_id) {
          $parents = array();
          while ($parent_id) {
            $page = get_post($parent_id);
            if ($page && !is_wp_error($page)) {
              $parents[] = '<li class="breadcrumb-item"><a class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url(get_permalink($page->ID)) . '">' . esc_html(get_the_title($page->ID)) . '</a></li>' . PHP_EOL;
              $parent_id = wp_get_post_parent_id($page->ID);
            } else {
              break;
            }
          }
          $parents = array_reverse($parents);
          echo implode('', $parents);
        }

        // Display the current page title (active breadcrumb item)
        echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>' . PHP_EOL;
      }
      
      // Search results
      elseif (is_search()) {
        echo '<li class="breadcrumb-item active" aria-current="page">' . 
         sprintf(
           /* translators: %s: search query */
           esc_html__('Search Results for: %s', 'bootscore'),
           esc_html(get_search_query())
         ) . 
         '</li>' . PHP_EOL;
      }
      
      // Other archives (tags, custom taxonomies, date, author) - MUST BE LAST
      elseif (is_archive()) {
        echo '<li class="breadcrumb-item active" aria-current="page">' . 
             wp_strip_all_tags(get_the_archive_title()) . 
             '</li>' . PHP_EOL;
      }
    }

    echo '</ol>' . PHP_EOL;
    echo '</nav>' . PHP_EOL;
  }

endif; // End of the_breadcrumb() function


/**
 * Breadcrumb Shortcode
 * Usage: [bs-breadcrumb]
 * 
 * Displays the breadcrumb navigation within content areas.
 * Useful for widget areas or the Page Blank template where breadcrumbs cannot be added via action hook.
 */
if (!function_exists('bootscore_breadcrumb_shortcode')) {
  function bootscore_breadcrumb_shortcode($atts) {
    // Skip in admin to prevent JSON errors during editor saves
    if (is_admin()) {
      return '';
    }
    
    ob_start();
    the_breadcrumb();
    return ob_get_clean();
  }
  add_shortcode('bs-breadcrumb', 'bootscore_breadcrumb_shortcode');
}
