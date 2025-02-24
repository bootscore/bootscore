<?php

/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.1.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<?php do_action('bootscore_before_loop_item', 'content-search'); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( apply_filters('bootscore/class/loop/card', 'card horizontal mb-4', 'content-search') ); ?>>
  
  <div class="<?= apply_filters('bootscore/class/loop/card/row', 'row g-0', 'content-search'); ?>">

    <?php if (has_post_thumbnail()) : ?>
      <div class="<?= apply_filters('bootscore/class/loop/card/image/col', 'col-lg-6 col-xl-5 col-xxl-4', 'content-search'); ?>">
        <a href="<?php the_permalink(); ?>">
          <?php the_post_thumbnail('medium', array('class' => apply_filters('bootscore/class/loop/card/image', 'card-img-lg-start', 'content-search'))); ?>
        </a>
      </div>
    <?php endif; ?>

    <div class="<?= apply_filters('bootscore/class/loop/card/content/col', 'col', 'content-search'); ?>">
      <div class="<?= apply_filters('bootscore/class/loop/card/body', 'card-body', 'content-search'); ?>">

        <?php if (apply_filters('bootscore/loop/category', true, 'content-search')) : ?>
          <?php bootscore_category_badge(); ?>
        <?php endif; ?>
        
        <?php do_action('bootscore_before_loop_title', 'content-search'); ?>

        <a class="text-body text-decoration-none" href="<?php the_permalink(); ?>">
          <?php the_title('<h2 class="' . apply_filters('bootscore/class/loop/card/title', 'blog-post-title h5', 'content-search') . '">', '</h2>'); ?>
        </a>

        <?php if (apply_filters('bootscore/loop/meta', true, 'content-search')) : ?>
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

        <?php if (apply_filters('bootscore/loop/excerpt', true, 'content-search')) : ?>
          <p class="<?= apply_filters('bootscore/class/loop/card-text/excerpt', 'card-text', 'content-search'); ?>">
            <a class="text-body text-decoration-none" href="<?php the_permalink(); ?>">
              <?= strip_tags(get_the_excerpt()); ?>
            </a>
          </p>
        <?php endif; ?>

        <?php if (apply_filters('bootscore/loop/read-more', true, 'content-search')) : ?>
          <p class="<?= apply_filters('bootscore/class/loop/card-text/read-more', 'card-text', 'content-search'); ?>">
            <a class="<?= apply_filters('bootscore/class/loop/read-more', 'read-more', 'content-search'); ?>" href="<?php the_permalink(); ?>">
              <?= apply_filters('bootscore/loop/read-more/text', __('Read more »', 'bootscore', 'content-search')); ?>
            </a>
          </p>
        <?php endif; ?>

        <?php if (apply_filters('bootscore/loop/tags', true, 'content-search')) : ?>
          <?php bootscore_tags(); ?>
        <?php endif; ?>

      </div>

      <?php do_action('bootscore_loop_item_after_card_body', 'content-search'); ?>

    </div>
  </div>

</article>

<?php do_action('bootscore_after_loop_item', 'content-search'); ?>

<!-- #post-<?php the_ID(); ?> -->
