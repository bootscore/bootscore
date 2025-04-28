<?php

/**
 * Experimantal: Disable unsupported blocks and patterns - Blacklist
 *
 * @package Bootscore
 * @version 6.2.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Disable unsupported blocks
 */
add_filter( 'allowed_block_types_all', function( $allowed_blocks, $editor_context ) {
  if ( ! empty( $editor_context->post ) ) {
    $all_blocks = array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() );

    // Blocks to disable
    $disabled_blocks = apply_filters('bootscore/unsupported_blocks', [

      // WordPress core
      // Text
      'core/details',
      'core/pullquote',
      'core/verse',

      // Media
      'core/cover',
      'core/file',
      'core/media-text',
      
      // Design
      'core/columns',
      'core/more',
      'core/nextpage',
      
      // Widgets
      'core/page-list',
      'core/rss',
      'core/social-links',
      'core/tag-cloud',
      
      // Theme
      'core/navigation',
      'core/site-logo',
      'core/site-tagline',
      'core/site-title',
      'core/query',
      'core/avatar',
      'core/post-title',
      'core/post-excerpt',
      'core/post-featured-image',
      'core/post-author',
      'core/post-author-biography',
      'core/post-author-name',
      'core/post-date',
      //'core/query-pagination',
      //'core/query-pagination-next',
      //'core/query-pagination-previous',
      'core/read-more',
      'core/loginout',
      'core/term-description',
      'core/comments',
      'core/post-comments-form',
      
      // Embed
      'core/embed',
      
      
      
      

      // WooCommerce
      'woocommerce/cart-link',
      'woocommerce/customer-account',
      'woocommerce/featured-category',
      'woocommerce/featured-product',
      'woocommerce/mini-cart',
      'woocommerce/store-notices',
      'woocommerce/single-product',
      'woocommerce/product-collection',
      
      // Filters
      'woocommerce/filter-wrapper',
      'woocommerce/active-filters',
      'woocommerce/attribute-filter',
      'woocommerce/price-filter',
      'woocommerce/product-filters',
      'woocommerce/product-filter-active',
      'woocommerce/product-filter-attribute',
      'woocommerce/product-filter-checkbox-list',
      'woocommerce/product-filter-chips',
      'woocommerce/product-filter-clear-button',
      'woocommerce/product-filter-price',
      'woocommerce/product-filter-price-slider',
      'woocommerce/product-filter-rating',
      'woocommerce/product-filter-removable-chips',
      'woocommerce/product-filter-status',
      
      // Reviews
      'woocommerce/product-details',
      'woocommerce/product-reviews',
      'woocommerce/blockified-product-details',
      'woocommerce/blockified-product-reviews',
      'woocommerce/product-review-rating',
      'woocommerce/product-reviews-title',
      


    ]);

    return array_values( array_filter( $all_blocks, fn( $block ) => ! in_array( $block, $disabled_blocks, true ) ) );
  }

  return $allowed_blocks;
}, 10, 2 );






// Disable all WP core patterns
add_action('init', function() {
    remove_theme_support('core-block-patterns');
},  9  );

if ( is_admin() ) {
    remove_theme_support('core-block-patterns');
}
