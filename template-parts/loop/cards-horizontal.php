<?php

/**
 * Template part for displaying loop items in horizontal cards (default)
 * Template Version: 6.4.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * The `overflow-hidden` class is used in cards to create responsive border radius in the image.
 * The `position-relative z-2` classes ensure badges, meta, and tags remain clickable above the `stretched-link` which covers the card.
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<?php do_action( 'bootscore_before_loop_item', 'cards-horizontal' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( esc_attr(apply_filters('bootscore/class/loop/card', 'card horizontal overflow-hidden mb-4', 'cards-horizontal')) ); ?>>

  <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/row', 'row g-0', 'cards-horizontal')); ?>">

    <?php do_action('bootscore_before_loop_thumbnail', 'cards-horizontal'); ?>

    <?php if (has_post_thumbnail()) : ?>
      <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/image/col', 'col-md-5 col-lg-6 col-xl-5 col-xxl-4', 'cards-horizontal')); ?>">
        <?php the_post_thumbnail('medium', array('class' => esc_attr(apply_filters('bootscore/class/loop/card/image', 'h-100 object-fit-cover', 'cards-horizontal')))); ?>
      </div>
    <?php endif; ?>

    <?php do_action('bootscore_after_loop_thumbnail', 'cards-horizontal'); ?>

    <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/content/col', 'col', 'cards-horizontal')); ?>">
      <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/body', 'card-body', 'cards-horizontal')); ?>">

        <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/content/meta-wrapper', 'position-relative z-2 d-flex justify-content-between gap-3')); ?>">

          <?php if (apply_filters('bootscore/loop/category', true, 'cards-horizontal')) : ?>
            <?php bootscore_category_badge(); ?>
          <?php endif; ?>

          <?php if (is_sticky() ) { ?>
            <p class="sticky-badge"><span class="<?= esc_attr(apply_filters('bootscore/class/loop/card/content/sticky-post-badge', 'badge text-bg-danger')); ?>"><?= wp_kses_post(apply_filters('bootscore/icon/star', '<i class="fa-solid fa-star"></i>')); ?></span></p>
          <?php } ?>

        </div>

        <?php do_action('bootscore_before_loop_title', 'cards-horizontal'); ?>

        <?php the_title('<h2 class="' . esc_attr(apply_filters('bootscore/class/loop/card/title', 'h5', 'cards-horizontal')) . '">', '</h2>'); ?>

        <?php do_action('bootscore_after_loop_title', 'cards-horizontal'); ?>

        <?php if (apply_filters('bootscore/loop/meta', true, 'cards-horizontal')) : ?>
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

        <?php if (apply_filters('bootscore/loop/excerpt', true, 'cards-horizontal')) : ?>
          <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/excerpt', 'card-text', 'cards-horizontal')); ?>">
            <?= esc_html(wp_strip_all_tags(get_the_excerpt())); ?>
          </p>
        <?php endif; ?>

        <?php if (apply_filters('bootscore/loop/read-more', true, 'cards-horizontal')) : ?>
          <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/read-more', 'card-text', 'cards-horizontal')); ?>">
            <a class="<?= esc_attr(apply_filters('bootscore/class/loop/read-more', 'read-more stretched-link', 'cards-horizontal')); ?>" href="<?php the_permalink(); ?>">
              <?= wp_kses_post(apply_filters('bootscore/loop/read-more/text', __('Read more »', 'bootscore'), 'cards-horizontal')); ?>
            </a>
          </p>
        <?php endif; ?>

        <?php if (apply_filters('bootscore/loop/tags', true, 'cards-horizontal')) : ?>
          <div class="position-relative z-2">
            <?php bootscore_tags(); ?>
          </div>
        <?php endif; ?>

        <?php do_action('bootscore_after_loop_tags', 'cards-horizontal'); ?>

      </div>

      <?php do_action('bootscore_loop_item_after_card_body', 'cards-horizontal'); ?>

    </div>
  </div>
</article>

<?php do_action('bootscore_after_loop_item', 'cards-horizontal'); ?>
