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
 * Add WooCommerce support to bootscore breadcrumbs
 */
function bootscore_add_woocommerce_breadcrumbs($breadcrumbs) {
  
  // If WooCommerce is not active, return original breadcrumbs
  if (!class_exists('WooCommerce')) {
    return $breadcrumbs;
  }
  
  // Reset breadcrumbs array but keep home
  $woo_breadcrumbs = array($breadcrumbs[0]); // Keep home
  
  // Shop page
  if (is_shop() || is_product_category() || is_product_tag() || is_product()) {
    $shop_page_id = wc_get_page_id('shop');
    if ($shop_page_id) {
      $woo_breadcrumbs[] = array(
        'url' => get_permalink($shop_page_id),
        'text' => get_the_title($shop_page_id),
        'class' => ''
      );
    }
  }
  
  // Product category
  if (is_product_category() || is_product_tag()) {
    $current_term = get_queried_object();
    
    if ($current_term && !is_wp_error($current_term)) {
      // Get ancestors
      $ancestors = get_ancestors($current_term->term_id, $current_term->taxonomy);
      $ancestors = array_reverse($ancestors);
      
      foreach ($ancestors as $ancestor_id) {
        $ancestor = get_term($ancestor_id, $current_term->taxonomy);
        if ($ancestor && !is_wp_error($ancestor)) {
          $woo_breadcrumbs[] = array(
            'url' => get_term_link($ancestor),
            'text' => $ancestor->name,
            'class' => ''
          );
        }
      }
      
      // Current term
      $woo_breadcrumbs[] = array(
        'url' => '',
        'text' => $current_term->name,
        'class' => 'active pe-0',
        'aria_current' => 'page'
      );
    }
  }
  
  // Single product
  elseif (is_product()) {
    global $product;
    
    // Product categories
    $terms = wc_get_product_terms(get_the_ID(), 'product_cat', array(
      'orderby' => 'parent',
      'order' => 'DESC'
    ));
    
    if ($terms) {
      // Get the deepest category (usually the last one)
      $main_term = apply_filters('woocommerce_breadcrumb_main_term', $terms[0], $terms);
      
      if ($main_term) {
        // Get ancestors
        $ancestors = get_ancestors($main_term->term_id, 'product_cat');
        $ancestors = array_reverse($ancestors);
        
        foreach ($ancestors as $ancestor_id) {
          $ancestor = get_term($ancestor_id, 'product_cat');
          if ($ancestor && !is_wp_error($ancestor)) {
            $woo_breadcrumbs[] = array(
              'url' => get_term_link($ancestor),
              'text' => $ancestor->name,
              'class' => ''
            );
          }
        }
        
        // Main category
        $woo_breadcrumbs[] = array(
          'url' => get_term_link($main_term),
          'text' => $main_term->name,
          'class' => ''
        );
      }
    }
    
    // Product name
    $woo_breadcrumbs[] = array(
      'url' => '',
      'text' => get_the_title(),
      'class' => 'active pe-0',
      'aria_current' => 'page'
    );
  }
  
  // Cart page
  elseif (is_cart()) {
    $woo_breadcrumbs[] = array(
      'url' => '',
      'text' => __('Cart', 'woocommerce'),
      'class' => 'active pe-0',
      'aria_current' => 'page'
    );
  }
  
  // Checkout page
  elseif (is_checkout()) {
    $woo_breadcrumbs[] = array(
      'url' => '',
      'text' => __('Checkout', 'woocommerce'),
      'class' => 'active pe-0',
      'aria_current' => 'page'
    );
  }
  
  // Account page
  elseif (is_account_page()) {
    if (is_wc_endpoint_url('orders')) {
      $woo_breadcrumbs[] = array(
        'url' => wc_get_page_permalink('myaccount'),
        'text' => __('My Account', 'woocommerce'),
        'class' => ''
      );
      $woo_breadcrumbs[] = array(
        'url' => '',
        'text' => __('Orders', 'woocommerce'),
        'class' => 'active pe-0',
        'aria_current' => 'page'
      );
    } elseif (is_wc_endpoint_url('view-order')) {
      $order_id = get_query_var('view-order');
      $woo_breadcrumbs[] = array(
        'url' => wc_get_page_permalink('myaccount'),
        'text' => __('My Account', 'woocommerce'),
        'class' => ''
      );
      $woo_breadcrumbs[] = array(
        'url' => wc_get_endpoint_url('orders', '', wc_get_page_permalink('myaccount')),
        'text' => __('Orders', 'woocommerce'),
        'class' => ''
      );
      $woo_breadcrumbs[] = array(
        'url' => '',
        'text' => sprintf(__('Order #%s', 'woocommerce'), $order_id),
        'class' => 'active pe-0',
        'aria_current' => 'page'
      );
    } elseif (is_wc_endpoint_url('edit-address')) {
      $woo_breadcrumbs[] = array(
        'url' => wc_get_page_permalink('myaccount'),
        'text' => __('My Account', 'woocommerce'),
        'class' => ''
      );
      $woo_breadcrumbs[] = array(
        'url' => '',
        'text' => __('Edit Address', 'woocommerce'),
        'class' => 'active pe-0',
        'aria_current' => 'page'
      );
    } else {
      $woo_breadcrumbs[] = array(
        'url' => '',
        'text' => __('My Account', 'woocommerce'),
        'class' => 'active pe-0',
        'aria_current' => 'page'
      );
    }
  }
  
  // Return WooCommerce breadcrumbs if any were added
  if (count($woo_breadcrumbs) > 1) {
    return $woo_breadcrumbs;
  }
  
  // Otherwise return original breadcrumbs
  return $breadcrumbs;
}
add_filter('bootscore_breadcrumb_items', 'bootscore_add_woocommerce_breadcrumbs', 20);
