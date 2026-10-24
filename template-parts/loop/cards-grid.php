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
    <?php the_post_thumbnail('medium', array('class' => esc_attr(apply_filters('bootscore/class/loop/card/image', 'card-img-top', 'cards-grid')))); ?>
  <?php endif; ?>

  <?php do_action('bootscore_after_loop_thumbnail', 'cards-grid'); ?>

  <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/body', 'card-body', 'cards-grid')); ?>">

    <?php if (apply_filters('bootscore/loop/category', true, 'cards-grid')) : ?>
      <?php bootscore_categories(); ?>
    <?php endif; ?>

    <?php do_action('bootscore_before_loop_title', 'cards-grid'); ?>

    <h2 class="<?= esc_attr(apply_filters('bootscore/class/loop/card/title', 'h4 card-title', 'cards-grid')); ?>">
      <a class="<?= esc_attr(apply_filters('bootscore/class/loop/card/title/link', 'fg-body text-decoration-none stretched-link', 'cards-grid')); ?>" href="<?php the_permalink(); ?>">
        <?php if (is_sticky() && apply_filters('bootscore/loop/sticky-pin', true, 'cards-grid')) : ?><small class="<?= esc_attr(apply_filters('bootscore/class/loop/card/sticky/pin', 'sticky-badge fg-secondary me-2', 'cards-grid')); ?>"><?php bootscore_icon('thumbtack'); ?><span class="visually-hidden"><?php esc_html_e('Sticky post:', 'bootscore'); ?></span></small><?php endif; ?><?php the_title(); ?>
      </a>
    </h2>
   
    <?php do_action('bootscore_after_loop_title', 'cards-grid'); ?>

    <?php if (apply_filters('bootscore/loop/meta', true, 'cards-grid')) : ?>
      <?php if ('post' === get_post_type()) : ?>
        <p class="card-subtitle fg-secondary align-self-start fs-sm z-1">
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
      <p class="<?= esc_attr(apply_filters('bootscore/class/loop/excerpt', 'excerpt', 'cards-grid')); ?>">
        <?= wp_kses_post(get_the_excerpt()); ?>
      </p>
    <?php endif; ?>

    <?php if (apply_filters('bootscore/loop/read-more', true, 'cards-grid')) : ?>
      <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/read-more', 'mt-auto', 'cards-grid')); ?>">
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
