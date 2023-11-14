<?php

/**
 * WooCommerce Loop
 *
 * @package Bootscore 
 * @version 5.3.4
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


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