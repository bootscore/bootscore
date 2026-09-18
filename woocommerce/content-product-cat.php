<?php

/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.7.0
 * 
 * @package Bootscore
 * @version 7.0.0
 */

if (!defined('ABSPATH')) {
  exit;
}

// When called from bs Loop shortcode, the term is passed via $args['term'].
// When called from the native WooCommerce loop, $category is set by WooCommerce.
if (!empty($args['term']) && $args['term'] instanceof WP_Term) {
  $category = $args['term'];
}

if (empty($category)) {
  return;
}

// When called from bs Loop shortcode, $GLOBALS['bs_loop_atts'] is set.
// In that case the col wrapper is handled by the shortcode's own row/col dispatch.
// When called from the native WooCommerce loop, we add the col wrapper here.
$in_bs_loop = isset($GLOBALS['bs_loop_atts']);
?>

<?php if (!$in_bs_loop) : ?>
<div class="col bs-loop-grid-item">
<?php endif; ?>

  <div <?php wc_product_cat_class(esc_attr(apply_filters('bootscore/class/woocommerce/product/card', 'card h-100 text-center')), $category); ?>>

    <?php
    /**
     * The woocommerce_before_subcategory hook.
     *
     * @hooked woocommerce_template_loop_category_link_open - 10
     */
    do_action('woocommerce_before_subcategory', $category);

    /**
     * The woocommerce_before_subcategory_title hook.
     *
     * @hooked woocommerce_subcategory_thumbnail - 10
     */
    do_action('woocommerce_before_subcategory_title', $category);
    ?>

    <div class="<?= esc_attr(apply_filters('bootscore/class/woocommerce/product/card/card-body', 'card-body d-flex flex-column')); ?>">
      <?php
      /**
       * The woocommerce_shop_loop_subcategory_title hook.
       *
       * @hooked woocommerce_template_loop_category_title - 10
       */
      do_action('woocommerce_shop_loop_subcategory_title', $category);

      /**
       * The woocommerce_after_subcategory_title hook.
       */
      do_action('woocommerce_after_subcategory_title', $category);

      /**
       * The woocommerce_after_subcategory hook.
       *
       * @hooked woocommerce_template_loop_category_link_close - 10
       */
      do_action('woocommerce_after_subcategory', $category);
      ?>
    </div>

  </div>

<?php if (!$in_bs_loop) : ?>
</div><!-- .col -->
<?php endif; ?>
