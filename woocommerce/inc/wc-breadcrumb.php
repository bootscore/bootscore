<?php

/**
 * WooCommerce Breadcrumb
 *
 * @package Bootscore 
 * @version 6.4.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Add WooCommerce breadcrumb support to Bootscore breadcrumb
 *
 * @link https://github.com/bootscore/bootscore/pull/1150
 */
if (!function_exists('bootscore_woocommerce_breadcrumb')) :
  function bootscore_woocommerce_breadcrumb($queried_object, $post_type) {
    
    // Only run for WooCommerce pages
    if (!class_exists('WooCommerce')) {
      return;
    }
    
    // Check if this is a WooCommerce page (shop, product, or product taxonomy)
    $is_wc_search = is_search() && get_query_var('post_type') === 'product';
    if (!is_woocommerce() && !is_product_category() && !is_product_tag() && !$is_wc_search) {
      return;
    }
    
    $breadcrumb_class = apply_filters('bootscore/class/breadcrumb/item/link', '');
    
    // WooCommerce search results
    if ($is_wc_search) {
      $shop_page_id = wc_get_page_id('shop');
      if ($shop_page_id && $shop_page_id > 0) {
        echo '<li class="breadcrumb-item"><a class="' . esc_attr($breadcrumb_class) . '" href="' . esc_url(get_permalink($shop_page_id)) . '">' . esc_html(get_the_title($shop_page_id)) . '</a></li>' . PHP_EOL;
      }
      echo '<li class="breadcrumb-item active" aria-current="page">' . sprintf(esc_html__('Search results for "%s"', 'woocommerce'), get_search_query()) . '</li>' . PHP_EOL;
    }
    
    // Shop page (main shop archive)
    elseif (is_shop() && !is_search()) {
      echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(woocommerce_page_title(false)) . '</li>' . PHP_EOL;
    }
    
    // Product category archive
    elseif (is_product_category()) {
      $current_term = $queried_object;
      
      // Shop page link
      $shop_page_id = wc_get_page_id('shop');
      if ($shop_page_id && $shop_page_id > 0) {
        echo '<li class="breadcrumb-item"><a class="' . esc_attr($breadcrumb_class) . '" href="' . esc_url(get_permalink($shop_page_id)) . '">' . esc_html(get_the_title($shop_page_id)) . '</a></li>' . PHP_EOL;
      }
      
      // Parent categories
      if ($current_term->parent) {
        $ancestors = array_reverse(get_ancestors($current_term->term_id, 'product_cat'));
        foreach ($ancestors as $ancestor_id) {
          $ancestor = get_term($ancestor_id, 'product_cat');
          if ($ancestor && !is_wp_error($ancestor)) {
            echo '<li class="breadcrumb-item"><a class="' . esc_attr($breadcrumb_class) . '" href="' . esc_url(get_term_link($ancestor)) . '">' . esc_html($ancestor->name) . '</a></li>' . PHP_EOL;
          }
        }
      }
      
      // Current category
      echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html($current_term->name) . '</li>' . PHP_EOL;
    }
    
    // Product tag archive
    elseif (is_product_tag()) {
      $current_term = $queried_object;
      
      // Shop page link
      $shop_page_id = wc_get_page_id('shop');
      if ($shop_page_id && $shop_page_id > 0) {
        echo '<li class="breadcrumb-item"><a class="' . esc_attr($breadcrumb_class) . '" href="' . esc_url(get_permalink($shop_page_id)) . '">' . esc_html(get_the_title($shop_page_id)) . '</a></li>' . PHP_EOL;
      }
      
      // Current tag
      echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html($current_term->name) . '</li>' . PHP_EOL;
    }
    
    // Product brand archive (common taxonomy names: 'product_brand', 'brand', 'pa_brand')
    elseif (is_tax(array('product_brand', 'brand', 'pa_brand'))) {
      $current_term = $queried_object;
      
      // Shop page link
      $shop_page_id = wc_get_page_id('shop');
      if ($shop_page_id && $shop_page_id > 0) {
        echo '<li class="breadcrumb-item"><a class="' . esc_attr($breadcrumb_class) . '" href="' . esc_url(get_permalink($shop_page_id)) . '">' . esc_html(get_the_title($shop_page_id)) . '</a></li>' . PHP_EOL;
      }
      
      // Parent brands (if hierarchical)
      if (isset($current_term->parent) && $current_term->parent) {
        $ancestors = array_reverse(get_ancestors($current_term->term_id, $current_term->taxonomy));
        foreach ($ancestors as $ancestor_id) {
          $ancestor = get_term($ancestor_id, $current_term->taxonomy);
          if ($ancestor && !is_wp_error($ancestor)) {
            echo '<li class="breadcrumb-item"><a class="' . esc_attr($breadcrumb_class) . '" href="' . esc_url(get_term_link($ancestor)) . '">' . esc_html($ancestor->name) . '</a></li>' . PHP_EOL;
          }
        }
      }
      
      // Current brand
      echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html($current_term->name) . '</li>' . PHP_EOL;
    }
    
    // Single product
    elseif (is_product()) {
      // Shop page link
      $shop_page_id = wc_get_page_id('shop');
      if ($shop_page_id && $shop_page_id > 0) {
        echo '<li class="breadcrumb-item"><a class="' . esc_attr($breadcrumb_class) . '" href="' . esc_url(get_permalink($shop_page_id)) . '">' . esc_html(get_the_title($shop_page_id)) . '</a></li>' . PHP_EOL;
      }
      
      // Check for product brand first (if exists)
      $brand_terms = get_the_terms(get_the_ID(), 'product_brand');
      if (!$brand_terms || is_wp_error($brand_terms)) {
        $brand_terms = get_the_terms(get_the_ID(), 'brand');
      }
      if (!$brand_terms || is_wp_error($brand_terms)) {
        $brand_terms = get_the_terms(get_the_ID(), 'pa_brand'); // Attribute brand
      }
      
      // If brand exists, show it before category
      if ($brand_terms && !is_wp_error($brand_terms)) {
        $main_brand = $brand_terms[0];
        echo '<li class="breadcrumb-item"><a class="' . esc_attr($breadcrumb_class) . '" href="' . esc_url(get_term_link($main_brand)) . '">' . esc_html($main_brand->name) . '</a></li>' . PHP_EOL;
      }
      
      // Product category (first one if multiple)
      $terms = get_the_terms(get_the_ID(), 'product_cat');
      if ($terms && !is_wp_error($terms)) {
        $main_term = $terms[0];
        echo '<li class="breadcrumb-item"><a class="' . esc_attr($breadcrumb_class) . '" href="' . esc_url(get_term_link($main_term)) . '">' . esc_html($main_term->name) . '</a></li>' . PHP_EOL;
      }
      
      // Current product
      echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>' . PHP_EOL;
    }
  }
  add_action('bootscore_breadcrumb_conditions', 'bootscore_woocommerce_breadcrumb', 10, 2);
endif;