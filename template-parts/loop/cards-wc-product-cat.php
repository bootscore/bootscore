<?php

/**
 * Template part for displaying WooCommerce product category
 * Template Version: 6.4.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * 
 * Deprecated: Refactor the woocommerce/content-product-cat.php loop to use this template instead in the bs Loop plugins in Bootscore 7.
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


// get_template_part() passes data via $args (no auto-extract, unlike wc_get_template()).
// Map the term passed in by bs-loop onto $category, which the rest of this file expects.
$category = isset($args['term']) ? $args['term'] : null;

if (!$category) {
  return;
}
?>
<div <?php wc_product_cat_class(esc_attr(apply_filters('bootscore/class/woocommerce/product/card', 'card h-100 text-center')), $category); ?>>
  <?php
  do_action('woocommerce_before_subcategory', $category);
  do_action('woocommerce_before_subcategory_title', $category);
  ?>
  <div class="<?= esc_attr(apply_filters('bootscore/class/woocommerce/product/card/card-body', 'card-body d-flex flex-column')); ?>">
    <?php
    do_action('woocommerce_shop_loop_subcategory_title', $category);
    do_action('woocommerce_after_subcategory_title', $category);
    do_action('woocommerce_after_subcategory', $category);
    ?>
  </div>
</div>
