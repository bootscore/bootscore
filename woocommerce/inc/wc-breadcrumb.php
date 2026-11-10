<?php

/**
 * WooCommerce breadcrumb
 *
 * Adds WooCommerce items to bootscore_breadcrumb() via the bootscore/breadcrumb/handler filter.
 * Shown below the masthead on all WooCommerce templates (context 'woocommerce').
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Show the breadcrumb below the masthead on WooCommerce templates
 *
 * Priority 5, so child themes can still hide it with the same filter.
 */
function bootscore_wc_breadcrumb_show($show, $context) {
  return 'woocommerce' === $context ? true : $show;
}
add_filter('bootscore/breadcrumb/show', 'bootscore_wc_breadcrumb_show', 5, 2);


/**
 * Shop page item
 */
function bootscore_wc_breadcrumb_shop_item($context, $current = false) {
  $shop_page_id = wc_get_page_id('shop');

  if ($shop_page_id > 0) {
    bootscore_breadcrumb_item(esc_html(get_the_title($shop_page_id)), $current ? '' : get_permalink($shop_page_id), $context);
  }
}


/**
 * Ancestor term items
 */
function bootscore_wc_breadcrumb_ancestor_items($term, $context) {
  $ancestors = array_reverse(get_ancestors($term->term_id, $term->taxonomy, 'taxonomy'));

  foreach ($ancestors as $ancestor_id) {
    $ancestor = get_term($ancestor_id, $term->taxonomy);
    if ($ancestor && !is_wp_error($ancestor)) {
      bootscore_breadcrumb_item(esc_html($ancestor->name), get_term_link($ancestor), $context);
    }
  }
}


/**
 * WooCommerce breadcrumb handler
 */
function bootscore_wc_breadcrumb_handler($handled, $context = '') {

  // Already handled by another handler
  if ($handled) {
    return $handled;
  }

  // Product search
  if (is_search() && 'product' === get_query_var('post_type')) {
    bootscore_wc_breadcrumb_shop_item($context);
    bootscore_breadcrumb_item(
      sprintf(
        /* translators: %s: search query */
        esc_html__('Search results for &ldquo;%s&rdquo;', 'woocommerce'),
        esc_html(get_search_query())
      ),
      '',
      $context
    );
    return true;
  }

  // Shop page
  if (is_shop()) {
    bootscore_wc_breadcrumb_shop_item($context, true);
    return true;
  }

  // Product category
  if (is_product_category()) {
    $term = get_queried_object();
    bootscore_wc_breadcrumb_shop_item($context);
    bootscore_wc_breadcrumb_ancestor_items($term, $context);
    bootscore_breadcrumb_item(esc_html($term->name), '', $context);
    return true;
  }

  // Product tag
  if (is_product_tag()) {
    $term = get_queried_object();
    bootscore_wc_breadcrumb_shop_item($context);
    bootscore_breadcrumb_item(
      sprintf(
        /* translators: %s: product tag name */
        esc_html__('Products tagged &ldquo;%s&rdquo;', 'woocommerce'),
        esc_html($term->name)
      ),
      '',
      $context
    );
    return true;
  }

  // Product brand, WooCommerce and common brand plugins
  if (is_tax(['product_brand', 'brand', 'pa_brand', 'yith_product_brand', 'pwb-brand'])) {
    $term = get_queried_object();
    bootscore_wc_breadcrumb_shop_item($context);
    bootscore_wc_breadcrumb_ancestor_items($term, $context);
    bootscore_breadcrumb_item(
      sprintf(
        /* translators: %s: brand name */
        esc_html__('Brand: %s', 'woocommerce'),
        esc_html($term->name)
      ),
      '',
      $context
    );
    return true;
  }

  // Single product: shop, main category with its parents, product
  if (is_product()) {
    bootscore_wc_breadcrumb_shop_item($context);

    $terms = wc_get_product_terms(get_the_ID(), 'product_cat', ['orderby' => 'parent', 'order' => 'DESC']);

    if ($terms && !is_wp_error($terms)) {
      $main_term = apply_filters('woocommerce_breadcrumb_main_term', $terms[0], $terms);

      if ($main_term) {
        bootscore_wc_breadcrumb_ancestor_items($main_term, $context);
        bootscore_breadcrumb_item(esc_html($main_term->name), get_term_link($main_term), $context);
      }
    }

    bootscore_breadcrumb_item(esc_html(get_the_title()), '', $context);
    return true;
  }

  return $handled;
}
add_filter('bootscore/breadcrumb/handler', 'bootscore_wc_breadcrumb_handler', 10, 2);
