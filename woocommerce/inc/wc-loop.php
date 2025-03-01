<?php

/**
 * WooCommerce Loop
 *
 * @package Bootscore 
 * @version 6.1.1
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
 * Add card-img-top class to product loop
 */
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'bootscore_loop_product_thumbnail', 10);

function bootscore_loop_product_thumbnail() {
  global $product;
  $classes = apply_filters('bootscore/class/woocommerce/product/loop/img', 'card-img-top');

  echo $product ? $product->get_image('woocommerce_thumbnail', "class=$classes") : '';
}


/**
 * Add card-img-top class to category loop
 */
remove_action('woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10);
add_action('woocommerce_before_subcategory_title', 'bootscore_loop_category_thumbnail', 10);

function bootscore_loop_category_thumbnail($category) {
  $classes = apply_filters('bootscore/class/woocommerce/category/loop/img', 'card-img-top');

  // Get the category thumbnail ID
  $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);

  if ($thumbnail_id) {
    // Display the assigned category image
    echo wp_get_attachment_image($thumbnail_id, 'woocommerce_thumbnail', false, ['class' => $classes]);
  } else {
    // Display the WooCommerce placeholder image with custom class
    echo '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr__('Placeholder', 'woocommerce') . '" class="' . esc_attr($classes) . '" />';
  }
}


/**
 * Wrap add to cart link in container.
 *
 * @param string $html Add to cart link HTML.
 * @return string Add to cart link HTML.
 */
function bootscore_loop_add_to_cart_link( $html ) {
  $classes = apply_filters('bootscore/class/woocommerce/loop/add-to-cart', 'add-to-cart-container mt-auto');
  return '<div class="' . esc_attr($classes) . '">' . $html . '</div>';
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

    // Apply filter for button classes
    $custom_classes = apply_filters('bootscore/class/woocommerce/loop/add-to-cart/button', 'btn btn-primary d-block');
    $args['class'] .= ' ' . $custom_classes;
  } else {
    $args['class'] = apply_filters('bootscore/class/woocommerce/loop/add-to-cart/button', 'btn btn-primary d-block');
  }

  return $args;
}
add_filter( 'woocommerce_loop_add_to_cart_args', 'bootscore_loop_add_to_cart_args' );
