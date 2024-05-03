<?php

/**
 * WooCommerce Loop
 *
 * @package Bootscore 
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Category loop button and badge
 */
if (!function_exists('woocommerce_template_loop_category_title')) :
  function woocommerce_template_loop_category_title($category) {
    ?>
    <h2 class="woocommerce-loop-category__title btn btn-primary w-100 mb-0">
      <?php
      echo $category->name;

      if ($category->count > 0) {
        echo apply_filters('woocommerce_subcategory_count_html', ' <mark class="count badge bg-white text-dark">' . $category->count . '</mark>', $category);
      }
      ?>
    </h2>
    <?php
  }
endif;

add_filter('bootscore_product_col_class', 'bootscore_wc_product_col_class');
function bootscore_wc_product_col_class($class) {
  if (is_cart()) {
    $class = 'col-6';
  }
  return $class;
}


/**
 * Wrap add to cart link in container.
 *
 * @param string $html Add to cart link HTML.
 * @return string Add to cart link HTML.
 */
function bootscore_loop_add_to_cart_link( $html ) {
  return '<div class="add-to-cart-container mt-auto">' . $html . '</div>';
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'bootscore_loop_add_to_cart_link' );



/**
 * Add Bootstrap button classes to add to cart link.
 *
 * @param array<string,mixed> $args Array of add to cart link arguments.
 * @return array<string,mixed> Array of add to cart link arguments.
 */
function bootscore_loop_add_to_cart_args( $args ) {
  if ( isset( $args['class'] ) && ! empty( $args['class'] ) ) {
    if ( ! is_string( $args['class'] ) ) {
      return $args;
    }

    // Remove the `button` class if it exists.
    if ( false !== strpos( $args['class'], 'button' ) ) {
      $args['class'] = explode( ' ', $args['class'] );
      $args['class'] = array_diff( $args['class'], array( 'button' ) );
      $args['class'] = implode( ' ', $args['class'] );
    }

      $args['class'] .= ' btn btn-primary w-100 mt-auto';
    } else {
      $args['class'] = 'btn btn-primary w-100 mt-auto';
    }

    return $args;
}
add_filter( 'woocommerce_loop_add_to_cart_args', 'bootscore_loop_add_to_cart_args' );