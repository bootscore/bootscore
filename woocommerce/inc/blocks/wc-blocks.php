<?php

/**
 * WooCommerce Blocks
 *
 * @package Bootscore 
 * @version 5.3.4
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Disable WooCommerce block styles
 * https://wordpress.stackexchange.com/questions/391126/remove-woocommerce-block-styles
 */
function disable_wp_blocks() {
    $wstyles = array(
        //'wp-block-library',
        //'wc-blocks-style-mini-cart',
        //'wc-blocks-style-mini-cart-contents',
        //'wc-blocks-style-product-sale-badge',
        //'wc-blocks-style-single-product',
        //'wc-blocks-style-product-add-to-cart',
        //'wc-blocks-style-product-button',
        //'wc-blocks-style-active-filters',
        //'wc-blocks-style-add-to-cart-form',
        //'wc-blocks-style-all-reviews',
        //'wc-blocks-style-attribute-filter',
        //'wc-blocks-style-breadcrumbs',
        //'wc-blocks-style-catalog-sorting',
        //'wc-blocks-style-customer-account',
        //'wc-blocks-style-featured-category',
        //'wc-blocks-style-featured-product',
        //'wc-blocks-style-price-filter',
        //'wc-blocks-style-product-categories',
        //'wc-blocks-style-product-image',
        //'wc-blocks-style-product-image-gallery',
        //'wc-blocks-style-product-query',
        //'wc-blocks-style-product-results-count',
        //'wc-blocks-style-product-reviews',
        //'wc-blocks-style-product-search',
        //'wc-blocks-style-product-sku',
        //'wc-blocks-style-product-stock-indicator',
        //'wc-blocks-style-product-summary',
        //'wc-blocks-style-product-title',
        //'wc-blocks-style-rating-filter',
        //'wc-blocks-style-reviews-by-category',
        //'wc-blocks-style-reviews-by-product',
        //'wc-blocks-style-product-details',
        //'wc-blocks-style-stock-filter',
        //'classic-theme-styles-inline',
        'wc-blocks-style-cart',
        'wc-blocks-style-checkout',
        'wc-blocks-style-all-products',
        'wc-blocks-style', // Alerts
        'wc-blocks-packages-style' // Borders and forms
    );

    foreach ( $wstyles as $wstyle ) {
        wp_deregister_style( $wstyle );
    }

    $wscripts = array(
        //'wc-blocks-middleware',
        //'wc-blocks-data-store'
    );

    foreach ( $wscripts as $wscript ) {
        //wp_deregister_script( $wscript );  
    }
}

add_action( 'init', 'disable_wp_blocks', 100 );
