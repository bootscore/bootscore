<?php

/**
 * WooCommerce Forms
 *
 * @package Bootscore
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Remove CSS and/or JS for Select2 used by WooCommerce
 * https://gist.github.com/Willem-Siebe/c6d798ccba249d5bf080.
 */
add_action('wp_enqueue_scripts', 'bootscore_dequeue_stylesandscripts_select2', 100);

function bootscore_dequeue_stylesandscripts_select2() {
  if (class_exists('woocommerce')) {
    wp_dequeue_style('selectWoo');
    wp_deregister_style('selectWoo');

    wp_dequeue_script('selectWoo');
    wp_deregister_script('selectWoo');
  }
}






// Bootstrap Billing forms
function iap_wc_bootstrap_form_field_args($args, $key, $value) {

  $args['input_class'][] = 'form-control';
  return $args;
}
add_filter('woocommerce_form_field_args', 'iap_wc_bootstrap_form_field_args', 10, 3);




// Qty
function woocommerce_quantity_input( $args = array(), $product = null, $echo = true ) {
    if ( is_null( $product ) ) {
        $product = $GLOBALS['product'];
    }
 
    $defaults = array(
        'input_id'     => uniqid( 'quantity_' ),
        'input_name'   => 'quantity',
        'input_value'  => '1',
        'classes'      => apply_filters( 'woocommerce_quantity_input_classes', array( 'input-text', 'qty', 'text', 'form-control' ), $product ),
        'max_value'    => apply_filters( 'woocommerce_quantity_input_max', -1, $product ),
        'min_value'    => apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
        'step'         => apply_filters( 'woocommerce_quantity_input_step', 1, $product ),
        'pattern'      => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
        'inputmode'    => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
        'product_name' => $product ? $product->get_title() : '',
        'placeholder'  => apply_filters( 'woocommerce_quantity_input_placeholder', '', $product ),
    );
 
    $args = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $product );
 
    // Apply sanity to min/max args - min cannot be lower than 0.
    $args['min_value'] = max( $args['min_value'], 0 );
    $args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : '';
 
    // Max cannot be lower than min if defined.
    if ( '' !== $args['max_value'] && $args['max_value'] < $args['min_value'] ) {
        $args['max_value'] = $args['min_value'];
    }
 
    ob_start();
 
    wc_get_template( 'global/quantity-input.php', $args );
 
    if ( $echo ) {
        echo ob_get_clean(); // WPCS: XSS ok.
    } else {
        return ob_get_clean();
    }
}
