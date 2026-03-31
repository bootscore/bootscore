<?php

/**
 * Template part for displaying loop items in cards
 * Template Version: 6.4.0
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
        <p class="sticky-badge"><span class="<?= esc_attr(apply_filters('bootscore/class/loop/card/content/sticky-post-badge', 'badge bg-danger-subtle text-danger-emphasis', 'cards-grid')); ?>"><?= wp_kses_post(apply_filters('bootscore/icon/map-pin', '<i class="fa-solid fa-map-pin"></i>')); ?></span></p>
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
          <?php
          $excerpt = get_post_field( 'post_excerpt', get_the_ID() );
          echo esc_html( wp_trim_words(
            strip_shortcodes( ! empty( $excerpt ) ? $excerpt : get_post_field( 'post_content', get_the_ID() ) ),
            55
          ) );
          ?>
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