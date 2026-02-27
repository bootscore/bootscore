<?php

/**
 * Template part for displaying WooCommerce product loop
 * Template Version: 6.4.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


global $product;
if (empty($product) || !is_a($product, 'WC_Product')) {
  $product = wc_get_product(get_the_ID());
}
?>

<?php do_action('bootscore_before_loop_item', 'card-product'); ?>

<div <?php wc_product_class( esc_attr(apply_filters( 'bootscore/class/woocommerce/product/card', 'card h-100 text-center', 'card-product' )), $product ); ?>>
  
  <?php do_action('woocommerce_before_shop_loop_item'); ?>
  
  <a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
    <?php do_action('woocommerce_before_shop_loop_item_title'); ?>
  </a>
  
  <div class="<?= esc_attr(apply_filters('bootscore/class/woocommerce/product/card/card-body', 'card-body d-flex flex-column', 'card-product')); ?>">
    <?php
    do_action('woocommerce_shop_loop_item_title');
    do_action('woocommerce_after_shop_loop_item_title');
    ?>
    
    <div class="mt-auto">
      <?php do_action('woocommerce_after_shop_loop_item'); ?>
    </div>
  </div>
  
</div>

<?php do_action('bootscore_after_loop_item', 'card-product'); ?>
