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
 * Breadcrumb item
 *
 * Outputs a divider followed by a breadcrumb item.
 * Pass an escaped label. Without $url the item is rendered as the current page.
 * Use this in custom breadcrumb handlers (WooCommerce, CPTs) as well.
 */
if (!function_exists('bootscore_breadcrumb_item')) :
  function bootscore_breadcrumb_item($label, $url = '', $context = '') {

    echo '<li class="breadcrumb-divider" aria-hidden="true">' . wp_kses(apply_filters('bootscore/breadcrumb/divider', '', $context), bootscore_kses_allowed_svg(wp_kses_allowed_html('post'))) . '</li>' . PHP_EOL;

    $link_class = trim('breadcrumb-link ' . apply_filters('bootscore/class/breadcrumb/item/link', '', $context));

    if ($url) {
      echo '<li class="breadcrumb-item"><a class="' . esc_attr($link_class) . '" href="' . esc_url($url) . '">' . $label . '</a></li>' . PHP_EOL;
    } else {
      echo '<li class="breadcrumb-item"><span class="' . esc_attr($link_class . ' active') . '" aria-current="page">' . $label . '</span></li>' . PHP_EOL;
    }
  }
endif;


/**
 * Breadcrumb
 */
if (!function_exists('bootscore_breadcrumb')) :
  function bootscore_breadcrumb($context = '') {

    if (is_home()) {
      return;
    }

    echo '<nav aria-label="breadcrumb" class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/nav', 'overflow-x-auto text-nowrap mb-6 mt-2 bg-1 rounded', $context)) . '">' . PHP_EOL;
    echo '<ol class="breadcrumb ' . esc_attr(apply_filters('bootscore/class/breadcrumb/ol', 'flex-nowrap', $context)) . '">' . PHP_EOL;

    // Home link, first item, no divider
    $home_link_class = trim('breadcrumb-link ' . apply_filters('bootscore/class/breadcrumb/item/link', '', $context));
    echo '<li class="breadcrumb-item"><a aria-label="' . esc_attr__('Home', 'bootscore') . '" class="' . esc_attr($home_link_class) . '" href="' . esc_url(home_url()) . '">' . bootscore_icon('home', false) . '</a></li>' . PHP_EOL;

    // Hook for custom breadcrumb handlers (WooCommerce, other CPTs, etc.)
    // If any handler returns true, it means it handled the breadcrumb and we should stop
    $handled = apply_filters('bootscore/breadcrumb/handler', false, $context);

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
              bootscore_breadcrumb_item(esc_html($ancestor->name), get_term_link($ancestor), $context);
            }
          }
          // Current category as text only
          bootscore_breadcrumb_item(esc_html(single_cat_title('', false)), '', $context);
        }
      }

      // Custom Post Type Archive (default handling)
      elseif (is_post_type_archive()) {
        $archive_title = preg_replace('/^\w+: /', '', get_the_archive_title());
        bootscore_breadcrumb_item(esc_html(wp_strip_all_tags($archive_title)), '', $context);
      }

      // Single post (regular posts and custom post types)
      elseif (is_single()) {
        $post_type     = get_post_type();
        $post_type_obj = get_post_type_object($post_type);

        // Show CPT archive link if it has an archive (default CPT handling)
        if ($post_type !== 'post' && $post_type_obj && $post_type_obj->has_archive) {
          $archive_link = get_post_type_archive_link($post_type);
          if ($archive_link) {
            bootscore_breadcrumb_item(esc_html($post_type_obj->labels->name), $archive_link, $context);
          }
        }
        // Regular posts - show categories
        elseif ($post_type === 'post') {
          $cat_ids = wp_get_post_categories(get_the_ID());
          foreach ($cat_ids as $cat_id) {
            $cat = get_category($cat_id);
            if ($cat && !is_wp_error($cat)) {
              bootscore_breadcrumb_item(esc_html($cat->name), get_term_link($cat), $context);
            }
          }
        }

        // Current post title
        bootscore_breadcrumb_item(esc_html(get_the_title()), '', $context);
      }

      // Pages, handle parent pages and current page
      elseif (is_page()) {
        $parent_ids = array_reverse(get_post_ancestors(get_the_ID()));
        foreach ($parent_ids as $parent_id) {
          bootscore_breadcrumb_item(esc_html(get_the_title($parent_id)), get_permalink($parent_id), $context);
        }

        // Current page title
        bootscore_breadcrumb_item(esc_html(get_the_title()), '', $context);
      }

      // Search results
      elseif (is_search()) {
        bootscore_breadcrumb_item(
          sprintf(
            /* translators: %s: search query */
            esc_html__('Search Results for: %s', 'bootscore'),
            esc_html(get_search_query())
          ),
          '',
          $context
        );
      }

      // Other archives (tags, custom taxonomies, date, author) - MUST BE LAST
      elseif (is_archive()) {
        bootscore_breadcrumb_item(esc_html(wp_strip_all_tags(get_the_archive_title())), '', $context);
      }
    }

    echo '</ol>' . PHP_EOL;
    echo '</nav>' . PHP_EOL;
  }

endif; // End of bootscore_breadcrumb() function


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
    bootscore_breadcrumb();
    return ob_get_clean();
  }
  add_shortcode('bs-breadcrumb', 'bootscore_breadcrumb_shortcode');
}
