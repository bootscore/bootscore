<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 9.4.0
 */

defined('ABSPATH') || exit;

global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}
?>

<div class="<?= apply_filters('bootscore/class/woocommerce/col', 'col-md-6 col-lg-4 col-xxl-3'); ?>">
  <div <?php wc_product_class( apply_filters( 'bootscore/class/woocommerce/product/card', 'card h-100 text-center' ), $product ); ?>>
    <?php
    /**
     * Hook: woocommerce_before_shop_loop_item.
     *
     * @hooked woocommerce_template_loop_product_link_open - 10
     */
    do_action('woocommerce_before_shop_loop_item');

    /**
     * Hook: woocommerce_before_shop_loop_item_title.
     *
     * @hooked woocommerce_show_product_loop_sale_flash - 10
     * @hooked woocommerce_template_loop_product_thumbnail - 10
     */
    do_action('woocommerce_before_shop_loop_item_title');

    ?>
    <div class="<?= apply_filters('bootscore/class/woocommerce/product/card/card-body', 'card-body d-flex flex-column'); ?>">
      <?php
      /**
       * Hook: woocommerce_shop_loop_item_title.
       *
       * @hooked woocommerce_template_loop_product_title - 10
       */
      do_action('woocommerce_shop_loop_item_title');

      /**
       * Hook: woocommerce_after_shop_loop_item_title.
       *
       * @hooked woocommerce_template_loop_rating - 5
       * @hooked woocommerce_template_loop_price - 10
       */
      do_action('woocommerce_after_shop_loop_item_title');

      /**
       * Hook: woocommerce_after_shop_loop_item.
       *
       * @hooked woocommerce_template_loop_product_link_close - 5
       * @hooked woocommerce_template_loop_add_to_cart - 10
       */
      do_action('woocommerce_after_shop_loop_item');
      ?>
    </div>
  </div>
</div>