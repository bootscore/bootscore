<?php

/**
 * Template part for displaying loop items in cards-overlay (hero template, featured image is required)
 * Template Version: 7.0.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

$context = 'cards-overlay';
?>


<?php do_action( 'bootscore_before_loop_item', 'cards-overlay' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( esc_attr(apply_filters('bootscore/class/loop/card', 'card overflow-hidden', 'cards-overlay')) ); ?>>

  <?php do_action('bootscore_before_loop_thumbnail', 'cards-overlay'); ?>
    
  <?php if ( has_post_thumbnail() ) : ?>
    <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
      <?php the_post_thumbnail('full', array('class' => esc_attr(apply_filters('bootscore/class/loop/card/image', 'img-card-overlay', 'cards-overlay')))); ?>
    </a>
  <?php endif; ?>

  <?php do_action('bootscore_after_loop_thumbnail', 'cards-overlay'); ?>

  <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/img-overlay', 'card-img-overlay d-flex flex-column p-0', 'cards-overlay')); ?>">

    <div class="card-body bg-body bg-50 bd-blur bd-saturate-150 rounded-5 p-5 m-5 mt-auto flex-grow-0">

      <?php if (apply_filters('bootscore/loop/category', true, 'cards-overlay')) : ?>
        <?php bootscore_categories(); ?>
      <?php endif; ?>


      <?php do_action('bootscore_before_loop_title', 'cards-overlay'); ?>

      <h2 class="<?= esc_attr(apply_filters('bootscore/class/loop/card/title', 'h4 card-title', 'cards-overlay')); ?>">
        <a class="<?= esc_attr(apply_filters('bootscore/class/loop/card/title/link', 'fg-body text-decoration-none stretched-link', 'cards-overlay')); ?>" href="<?php the_permalink(); ?>">
          <?php if (is_sticky() && apply_filters('bootscore/loop/sticky-pin', true, 'cards-overlay')) : ?><small class="<?= esc_attr(apply_filters('bootscore/class/loop/card/sticky/pin', 'sticky-badge fg-secondary me-2', 'cards-overlay')); ?>"><?php bootscore_icon('thumbtack'); ?><span class="visually-hidden"><?php esc_html_e('Sticky post:', 'bootscore'); ?></span></small><?php endif; ?><?php the_title(); ?>
        </a>
      </h2>
      
      <?php do_action('bootscore_after_loop_title', 'cards-overlay'); ?>

      <?php if (apply_filters('bootscore/loop/meta', true, 'cards-overlay')) : ?>
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
      
      <?php if (apply_filters('bootscore/loop/excerpt', true, 'cards-overlay')) : ?>
        <p class="<?= esc_attr(apply_filters('bootscore/class/loop/excerpt', 'excerpt', 'cards-overlay')); ?>">
          <?= wp_kses_post(get_the_excerpt()); ?>
        </p>
      <?php endif; ?>

      <?php if (apply_filters('bootscore/loop/read-more', true, 'cards-overlay')) : ?>
        <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/read-more', 'mt-auto', 'cards-overlay')); ?>">
          <a class="<?= esc_attr(apply_filters('bootscore/class/loop/read-more', 'read-more', 'cards-overlay')); ?>" href="<?php the_permalink(); ?>">
            <?= wp_kses_post(apply_filters('bootscore/loop/read-more/text', __('Read more »', 'bootscore'), 'cards-overlay')); ?>
          </a>
        </p>
      <?php endif; ?>

      <?php if (apply_filters('bootscore/loop/tags', true, 'cards-overlay') && has_tag()) : ?>
        <?php bootscore_tags(); ?>
      <?php endif; ?>

      <?php do_action('bootscore_after_loop_tags', 'cards-overlay'); ?>

    </div>
  </div>

  <?php do_action('bootscore_loop_item_after_card_img_overlay', 'cards-overlay'); ?>

</article>

<?php do_action('bootscore_after_loop_item', 'cards-overlay'); ?>