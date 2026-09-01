<?php

/**
 * Template part for displaying loop items in cards
 * Template Version: 7.0.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

$context = 'cards-grid';
?>


<?php do_action( 'bootscore_before_loop_item', 'cards-grid' ); ?>

<!-- Default Post/CPT Card -->
<article id="post-<?php the_ID(); ?>" <?php post_class( esc_attr(apply_filters('bootscore/class/loop/card', 'card h-100', 'cards-grid')) ); ?>>

  <?php do_action('bootscore_before_loop_thumbnail', 'cards-grid'); ?>
    
  <?php if ( has_post_thumbnail() ) : ?>
    <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
      <?php the_post_thumbnail('medium', array('class' => esc_attr(apply_filters('bootscore/class/loop/card/image', 'card-img-top', 'cards-grid')))); ?>
    </a>
  <?php endif; ?>

  <?php do_action('bootscore_after_loop_thumbnail', 'cards-grid'); ?>

  <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/body', 'card-body h-100 d-flex flex-column', 'cards-grid')); ?>">

    <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/content/meta-wrapper', 'd-flex justify-content-between gap-3', 'cards-grid')); ?>">

      <?php if (apply_filters('bootscore/loop/category', true, 'cards-grid')) : ?>
        <?php bootscore_category_badge(); ?>
      <?php endif; ?>

      <?php if (is_sticky() ) { ?>
        <p class="sticky-badge"><span class="<?= esc_attr(apply_filters('bootscore/class/loop/card/content/sticky-post-badge', 'badge bg-danger-subtle text-danger-emphasis', 'cards-grid')); ?>"><?= wp_kses( apply_filters('bootscore/icon/thumbtack', '<svg class="bs-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M32 32C32 14.3 46.3 0 64 0L320 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-29.5 0 11.4 148.2c36.7 19.9 65.7 53.2 79.5 94.7l1 3c3.3 9.8 1.6 20.5-4.4 28.8s-15.7 13.3-26 13.3L32 352c-10.3 0-19.9-4.9-26-13.3s-7.7-19.1-4.4-28.8l1-3c13.8-41.5 42.8-74.8 79.5-94.7L93.5 64 64 64C46.3 64 32 49.7 32 32zM160 384l64 0 0 96c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-96z"/></svg>'), bootscore_kses_allowed_svg( wp_kses_allowed_html( 'post' ) )); ?></span></p>
      <?php } ?>

    </div>

    <?php do_action('bootscore_before_loop_title', 'cards-grid'); ?>
    
    <a class="<?= esc_attr(apply_filters('bootscore/class/loop/card/title/link', 'text-body text-decoration-none', 'cards-grid')); ?>" href="<?php the_permalink(); ?>">
      <?php the_title('<h2 class="' . esc_attr(apply_filters('bootscore/class/loop/card/title', 'h5', 'cards-grid')) . '">', '</h2>'); ?>
    </a>
   
    <?php do_action('bootscore_after_loop_title', 'cards-grid'); ?>

    <?php if (apply_filters('bootscore/loop/meta', true, 'cards-grid')) : ?>
      <?php if ('post' === get_post_type()) : ?>
        <p class="meta small mb-2 text-body-secondary">
          <?php
          bootscore_date();
          bootscore_author();
          bootscore_comments();
          bootscore_edit();
          ?>
        </p>
      <?php endif; ?>
    <?php endif; ?>
    
    <?php if (apply_filters('bootscore/loop/excerpt', true, 'cards-grid')) : ?>
      <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/excerpt', 'card-text', 'cards-grid')); ?>">
        <a class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/excerpt/link', 'text-body text-decoration-none', 'cards-grid')); ?>" href="<?php the_permalink(); ?>">                
          <?php bootscore_excerpt(); ?>
        </a>
      </p>
    <?php endif; ?>

    <?php if (apply_filters('bootscore/loop/read-more', true, 'cards-grid')) : ?>
      <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/read-more', 'card-text mt-auto', 'cards-grid')); ?>">
        <a class="<?= esc_attr(apply_filters('bootscore/class/loop/read-more', 'read-more', 'cards-grid')); ?>" href="<?php the_permalink(); ?>">
          <?= wp_kses_post(apply_filters('bootscore/loop/read-more/text', __('Read more »', 'bootscore'), 'cards-grid')); ?>
        </a>
      </p>
    <?php endif; ?>

    <?php if (apply_filters('bootscore/loop/tags', true, 'cards-grid') && has_tag()) : ?>
      <?php bootscore_tags(); ?>
    <?php endif; ?>

    <?php do_action('bootscore_after_loop_tags', 'cards-grid'); ?>

  </div>

  <?php do_action('bootscore_loop_item_after_card_body', 'cards-grid'); ?>

</article>

<?php do_action('bootscore_after_loop_item', 'cards-grid'); ?>