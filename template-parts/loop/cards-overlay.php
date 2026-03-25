<?php

/**
 * Template part for displaying loop items in cards-overlay (hero template, featured image is required)
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

$context = 'cards-overlay';
?>


<?php do_action( 'bootscore_before_loop_item', 'cards-overlay' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( esc_attr(apply_filters('bootscore/class/loop/card', 'card rounded-4 overflow-hidden', 'cards-overlay')) ); ?>>

  <?php do_action('bootscore_before_loop_thumbnail', 'cards-overlay'); ?>
    
  <?php if ( has_post_thumbnail() ) : ?>
    <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
      <?php the_post_thumbnail('full', array('class' => esc_attr(apply_filters('bootscore/class/loop/card/image', 'card-img', 'cards-overlay')))); ?>
    </a>
  <?php endif; ?>

  <?php do_action('bootscore_after_loop_thumbnail', 'cards-overlay'); ?>

  <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/img-overlay', 'card-img-overlay d-flex flex-column', 'cards-overlay')); ?>">

    <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/img-overlay/content/wrapper', 'bg-body bg-opacity-50 backdrop-blur-10 rounded-3 p-3 mt-auto', 'cards-overlay')); ?>">
      
      <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/content/meta-wrapper', 'position-relative z-2 d-flex justify-content-between gap-3', 'cards-overlay')); ?>">

        <?php if (apply_filters('bootscore/loop/category', true, 'cards-overlay')) : ?>
          <?php bootscore_category_badge(); ?>
        <?php endif; ?>

        <?php if (is_sticky() ) { ?>
          <p class="sticky-badge"><span class="<?= esc_attr(apply_filters('bootscore/class/loop/card/content/sticky-post-badge', 'badge bg-danger-subtle text-danger-emphasis', 'cards-overlay')); ?>"><?= wp_kses_post(apply_filters('bootscore/icon/map-pin', '<i class="fa-solid fa-map-pin"></i>')); ?></span></p>
        <?php } ?>

      </div>

      <?php do_action('bootscore_before_loop_title', 'cards-overlay'); ?>

      <?php the_title('<h2 class="' . esc_attr(apply_filters('bootscore/class/loop/card/title', 'h5', 'cards-overlay')) . '">', '</h2>'); ?>

      <?php do_action('bootscore_after_loop_title', 'cards-overlay'); ?>

      <?php if (apply_filters('bootscore/loop/meta', true, 'cards-overlay')) : ?>
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

      <?php if (apply_filters('bootscore/loop/excerpt', true, 'cards-overlay')) : ?>
        <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/excerpt', 'card-text', 'cards-overlay')); ?>">
          <?= esc_html(wp_strip_all_tags(get_the_excerpt())); ?>
        </p>
      <?php endif; ?>

      <?php if (apply_filters('bootscore/loop/read-more', true, 'cards-overlay')) : ?>
        <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/read-more', 'card-text mt-auto', 'cards-overlay')); ?>">
          <a class="<?= esc_attr(apply_filters('bootscore/class/loop/read-more', 'read-more stretched-link', 'cards-overlay')); ?>" href="<?php the_permalink(); ?>">
            <?= wp_kses_post(apply_filters('bootscore/loop/read-more/text', __('Read more »', 'bootscore'), 'cards-overlay')); ?>
          </a>
        </p>
      <?php endif; ?>

      <?php if (apply_filters('bootscore/loop/tags', true, 'cards-overlay') && has_tag()) : ?>
        <div class="position-relative z-2">
          <?php bootscore_tags(); ?>
        </div>
      <?php endif; ?>

      <?php do_action('bootscore_after_loop_tags', 'cards-overlay'); ?>

    </div>
  
  </div>

  <?php do_action('bootscore_loop_item_after_card_img_overlay', 'cards-overlay'); ?>

</article>

<?php do_action('bootscore_after_loop_item', 'cards-overlay'); ?>