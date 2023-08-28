<?php

/**
 * Loop
 *
 * @package Bootscore 
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Add card-img-top class to product loop
 */
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'custom_loop_product_thumbnail', 10);
function custom_loop_product_thumbnail() {
  global $product;
  $size = 'woocommerce_thumbnail';
  $code = 'class=card-img-top';

  $image_size = apply_filters('single_product_archive_thumbnail_size', $size);

  echo $product ? $product->get_image($image_size, $code) : '';
}


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
