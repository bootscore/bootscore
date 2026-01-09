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
 */
if (!function_exists('the_breadcrumb')) :
  function the_breadcrumb() {
    
    if (is_home()) {
      return;
    }

    $breadcrumbs = array();
    
    // Home item
    $breadcrumbs[] = array(
      'url' => home_url(),
      'text' => apply_filters('bootscore/icon/home', '<i class="fa-solid fa-house" aria-hidden="true"></i>') . '<span class="visually-hidden">' . esc_html__('Home', 'bootscore') . '</span>',
      'class' => '',
      'aria_label' => __('Home', 'bootscore')
    );
    
    // Default conditions
    if (is_category()) {
      $current_cat_id = get_queried_object_id();
      if ($current_cat_id) {
        $ancestors = array_reverse(get_ancestors($current_cat_id, 'category'));
        foreach ($ancestors as $ancestor_id) {
          $ancestor = get_category($ancestor_id);
          if ($ancestor && !is_wp_error($ancestor)) {
            $breadcrumbs[] = array(
              'url' => get_term_link($ancestor),
              'text' => $ancestor->name,
              'class' => ''
            );
          }
        }
        $breadcrumbs[] = array(
          'url' => '',
          'text' => single_cat_title('', false),
          'class' => 'aactive pe-0',
          'aria_current' => 'page'
        );
      }
    }
    elseif (is_single()) {
      $cat_ids = wp_get_post_categories(get_the_ID());
      foreach ($cat_ids as $cat_id) {
        $cat = get_category($cat_id);
        if ($cat && !is_wp_error($cat)) {
          $breadcrumbs[] = array(
            'url' => get_term_link($cat),
            'text' => $cat->name,
            'class' => ''
          );
        }
      }
      $breadcrumbs[] = array(
        'url' => '',
        'text' => get_the_title(),
        'class' => 'aactive pe-0',
        'aria_current' => 'page'
      );
    }
    elseif (is_page()) {
      $breadcrumbs[] = array(
        'url' => '',
        'text' => get_the_title(),
        'class' => 'aactive pe-0',
        'aria_current' => 'page'
      );
    }
    
    // Allow developers to modify breadcrumbs array
    $breadcrumbs = apply_filters('bootscore_breadcrumb_items', $breadcrumbs);
    
    // Output
    echo '<nav aria-label="breadcrumb" class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/nav', 'overflow-x-auto text-nowrap mb-4 mt-2 py-2 px-3 bg-body-tertiary rounded')) . '">' . PHP_EOL;
    echo '<ol class="breadcrumb ' . esc_attr(apply_filters('bootscore/class/breadcrumb/ol', 'flex-nowrap mb-0')) . '">' . PHP_EOL;
    
    foreach ($breadcrumbs as $crumb) {
      $aria_current = isset($crumb['aria_current']) ? ' aria-current="' . esc_attr($crumb['aria_current']) . '"' : '';
      $aria_label = isset($crumb['aria_label']) ? ' aria-label="' . esc_attr($crumb['aria_label']) . '"' : '';
      
      if (!empty($crumb['url'])) {
        echo '<li class="breadcrumb-item ' . esc_attr($crumb['class']) . '">'
          . '<a' . $aria_label . ' class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url($crumb['url']) . '">'
          . wp_kses_post($crumb['text'])
          . '</a></li>' . PHP_EOL;
      } else {
        echo '<li class="breadcrumb-item ' . esc_attr($crumb['class']) . '"' . $aria_current . '>'
          . wp_kses_post($crumb['text'])
          . '</li>' . PHP_EOL;
      }
    }
    
    echo '</ol>' . PHP_EOL;
    echo '</nav>' . PHP_EOL;
  }
endif;

