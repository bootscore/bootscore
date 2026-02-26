<?php

/**
 * Template part for displaying loop items in cards
 * Template Version: 6.4.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * The `position-relative z-2` classes ensure badges, meta, and tags remain clickable above the `stretched-link` which covers the card.
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

$context = 'cards';
$post_type = get_post_type();
?>


<?php do_action( 'bootscore_before_loop_item', 'cards' ); ?>

<?php if ($post_type === 'product' && class_exists('WooCommerce')) : ?>

  <!-- WooCommerce Product Card -->
  <?php 
  global $product;
  if (empty($product) || !is_a($product, 'WC_Product')) {
    $product = wc_get_product(get_the_ID());
  }
  ?>
  
  <div <?php wc_product_class( esc_attr(apply_filters( 'bootscore/class/woocommerce/product/card', 'card h-100 text-center', 'cards' )), $product ); ?>>
    
    <?php do_action('woocommerce_before_shop_loop_item'); ?>
    
    <a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
      <?php do_action('woocommerce_before_shop_loop_item_title'); ?>
    </a>
    
    <div class="<?= esc_attr(apply_filters('bootscore/class/woocommerce/product/card/card-body', 'card-body d-flex flex-column', 'cards')); ?>">
      <?php
      do_action('woocommerce_shop_loop_item_title');
      do_action('woocommerce_after_shop_loop_item_title');
      ?>
      
      <div class="mt-auto">
        <?php do_action('woocommerce_after_shop_loop_item'); ?>
      </div>
    </div>
    
  </div>
  
<?php else : ?>
  
  <!-- Default Post/CPT Card -->
  <article id="post-<?php the_ID(); ?>" <?php post_class( esc_attr(apply_filters('bootscore/class/loop/card', 'card h-100', 'cards')) ); ?>>

    <?php do_action('bootscore_before_loop_thumbnail', 'cards'); ?>
      
    <?php if ( has_post_thumbnail() ) : ?>
      <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
        <?php the_post_thumbnail('medium', array('class' => esc_attr(apply_filters('bootscore/class/loop/card/image', 'card-img-top', 'cards')))); ?>
      </a>
    <?php endif; ?>

    <?php do_action('bootscore_after_loop_thumbnail', 'cards'); ?>

    <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/body', 'card-body h-100 d-flex flex-column', 'cards')); ?>">

      <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/content/meta-wrapper', 'position-relative z-2 d-flex justify-content-between gap-3', 'cards')); ?>">

        <?php if (apply_filters('bootscore/loop/category', true, 'cards')) : ?>
          <?php bootscore_category_badge(); ?>
        <?php endif; ?>

        <?php if (is_sticky() ) { ?>
          <p class="sticky-badge"><span class="<?= esc_attr(apply_filters('bootscore/class/loop/card/content/sticky-post-badge', 'badge bg-danger-subtle text-danger-emphasis', 'cards')); ?>"><?= wp_kses_post(apply_filters('bootscore/icon/star', '<i class="fa-solid fa-star"></i>')); ?></span></p>
        <?php } ?>

      </div>

      <?php do_action('bootscore_before_loop_title', 'cards'); ?>

      <?php the_title('<h2 class="' . esc_attr(apply_filters('bootscore/class/loop/card/title', 'h5', 'cards')) . '">', '</h2>'); ?>

      <?php do_action('bootscore_after_loop_title', 'cards'); ?>

      <?php if (apply_filters('bootscore/loop/meta', true, 'cards')) : ?>
        <?php if ('post' === get_post_type()) : ?>
          <p class="position-relative z-2 meta small mb-2 text-body-secondary">
            <?php
            bootscore_date();
            bootscore_author();
            bootscore_comments();
            bootscore_edit();
            ?>
          </p>
        <?php endif; ?>
      <?php endif; ?>

      <?php if (apply_filters('bootscore/loop/excerpt', true, 'cards')) : ?>
        <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/excerpt', 'card-text', 'cards')); ?>">
          <?= esc_html(wp_strip_all_tags(get_the_excerpt())); ?>
        </p>
      <?php endif; ?>

      <?php if (apply_filters('bootscore/loop/read-more', true, 'cards')) : ?>
        <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/read-more', 'card-text mt-auto', 'cards')); ?>">
          <a class="<?= esc_attr(apply_filters('bootscore/class/loop/read-more', 'read-more stretched-link', 'cards')); ?>" href="<?php the_permalink(); ?>">
            <?= wp_kses_post(apply_filters('bootscore/loop/read-more/text', __('Read more »', 'bootscore'), 'cards')); ?>
          </a>
        </p>
      <?php endif; ?>

      <?php if (apply_filters('bootscore/loop/tags', true, 'cards') && has_tag()) : ?>
        <div class="position-relative z-2">
          <?php bootscore_tags(); ?>
        </div>
      <?php endif; ?>

      <?php do_action('bootscore_after_loop_tags', 'cards'); ?>

    </div>

    <?php do_action('bootscore_loop_item_after_card_body', 'cards'); ?>

  </article>
  
<?php endif; ?>

<?php do_action('bootscore_after_loop_item', 'cards'); ?>