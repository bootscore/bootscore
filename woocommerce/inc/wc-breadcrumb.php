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
 * Add WooCommerce breadcrumb support
 */
function bootscore_woocommerce_breadcrumb_handler($handled) {
  
  // If already handled by another plugin, return
  if ($handled) {
    return $handled;
  }
  
  // Only handle WooCommerce pages
  if (!class_exists('WooCommerce')) {
    return $handled;
  }
  
  // WooCommerce search
  if (is_search() && get_query_var('post_type') === 'product') {
    $shop_page_id = wc_get_page_id('shop');
    if ($shop_page_id && $shop_page_id > 0) {
      echo '<li class="breadcrumb-item"><a class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url(get_permalink($shop_page_id)) . '">' . esc_html(get_the_title($shop_page_id)) . '</a></li>' . PHP_EOL;
    }
    echo '<li class="breadcrumb-item active" aria-current="page">' . 
         sprintf(
           esc_html__('Search results for "%s"', 'woocommerce'),
           esc_html(get_search_query())
         ) . 
         '</li>' . PHP_EOL;
    
    return true; // Mark as handled
  }
  
  // Product category
  elseif (is_product_category()) {
    $current_term = get_queried_object();
    
    // Shop page link
    $shop_page_id = wc_get_page_id('shop');
    if ($shop_page_id && $shop_page_id > 0) {
      echo '<li class="breadcrumb-item"><a class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url(get_permalink($shop_page_id)) . '">' . esc_html(get_the_title($shop_page_id)) . '</a></li>' . PHP_EOL;
    }
    
    // Parent categories
    if ($current_term->parent) {
      $ancestors = array_reverse(get_ancestors($current_term->term_id, 'product_cat'));
      foreach ($ancestors as $ancestor_id) {
        $ancestor = get_term($ancestor_id, 'product_cat');
        if ($ancestor && !is_wp_error($ancestor)) {
          echo '<li class="breadcrumb-item"><a class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url(get_term_link($ancestor)) . '">' . esc_html($ancestor->name) . '</a></li>' . PHP_EOL;
        }
      }
    }
    
    // Current category
    echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html($current_term->name) . '</li>' . PHP_EOL;
    
    return true; // Mark as handled
  }
  
  // Product tag
  elseif (is_product_tag()) {
    $current_term = get_queried_object();
    
    // Shop page link
    $shop_page_id = wc_get_page_id('shop');
    if ($shop_page_id && $shop_page_id > 0) {
      echo '<li class="breadcrumb-item"><a class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url(get_permalink($shop_page_id)) . '">' . esc_html(get_the_title($shop_page_id)) . '</a></li>' . PHP_EOL;
    }
    
    // Current tag - WooCommerce format with curly quotes
    echo '<li class="breadcrumb-item active" aria-current="page">' . 
         sprintf(
           /* translators: %s: product tag name */
           esc_html__('Products tagged &ldquo;%s&rdquo;', 'woocommerce'),
           esc_html($current_term->name)
         ) . 
         '</li>' . PHP_EOL;
    
    return true; // Mark as handled
  }
  
  // Product brand archive
  elseif (is_tax(array('product_brand', 'brand', 'pa_brand', 'yith_product_brand', 'pwb-brand'))) {
    $current_term = get_queried_object();
    
    // Shop page link
    $shop_page_id = wc_get_page_id('shop');
    if ($shop_page_id && $shop_page_id > 0) {
      echo '<li class="breadcrumb-item"><a class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url(get_permalink($shop_page_id)) . '">' . esc_html(get_the_title($shop_page_id)) . '</a></li>' . PHP_EOL;
    }
    
    // Parent brands (if hierarchical)
    if (isset($current_term->parent) && $current_term->parent) {
      $ancestors = array_reverse(get_ancestors($current_term->term_id, $current_term->taxonomy));
      foreach ($ancestors as $ancestor_id) {
        $ancestor = get_term($ancestor_id, $current_term->taxonomy);
        if ($ancestor && !is_wp_error($ancestor)) {
          echo '<li class="breadcrumb-item"><a class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url(get_term_link($ancestor)) . '">' . esc_html($ancestor->name) . '</a></li>' . PHP_EOL;
        }
      }
    }
    
    // Current brand
    echo '<li class="breadcrumb-item active" aria-current="page">' . 
         sprintf(
           /* translators: %s: brand name */
           esc_html__('Brand: %s', 'woocommerce'),
           esc_html($current_term->name)
         ) . 
         '</li>' . PHP_EOL;
    
    return true; // Mark as handled
  }
  
  // Single product
  elseif (is_product()) {
    // Shop page link
    $shop_page_id = wc_get_page_id('shop');
    if ($shop_page_id && $shop_page_id > 0) {
      echo '<li class="breadcrumb-item"><a class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url(get_permalink($shop_page_id)) . '">' . esc_html(get_the_title($shop_page_id)) . '</a></li>' . PHP_EOL;
    }
    
    // Product categories with hierarchy
    $terms = wc_get_product_terms(get_the_ID(), 'product_cat', array(
      'orderby' => 'parent',
      'order' => 'DESC'
    ));
    
    if ($terms && !is_wp_error($terms)) {
      // Get the deepest category
      $main_term = apply_filters('woocommerce_breadcrumb_main_term', $terms[0], $terms);
      
      if ($main_term) {
        // Get ancestors
        $ancestors = get_ancestors($main_term->term_id, 'product_cat');
        $ancestors = array_reverse($ancestors);
        
        foreach ($ancestors as $ancestor_id) {
          $ancestor = get_term($ancestor_id, 'product_cat');
          if ($ancestor && !is_wp_error($ancestor)) {
            echo '<li class="breadcrumb-item"><a class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url(get_term_link($ancestor)) . '">' . esc_html($ancestor->name) . '</a></li>' . PHP_EOL;
          }
        }
        
        // Main category
        echo '<li class="breadcrumb-item"><a class="' . esc_attr(apply_filters('bootscore/class/breadcrumb/item/link', '')) . '" href="' . esc_url(get_term_link($main_term)) . '">' . esc_html($main_term->name) . '</a></li>' . PHP_EOL;
      }
    }
    
    // Current product
    echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>' . PHP_EOL;
    
    return true; // Mark as handled
  }
  
  // Shop page
  elseif (is_shop()) {
    echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title(wc_get_page_id('shop'))) . '</li>' . PHP_EOL;
    return true; // Mark as handled
  }
  
  // Not a WooCommerce page we handle
  return $handled;
}
add_filter('bootscore/breadcrumb/handler', 'bootscore_woocommerce_breadcrumb_handler', 10, 1);

