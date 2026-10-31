<?php

/**
 * Template Name: Full Width Image
 * Template Version: 7.0.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

// Set global template context for custom layout hooks
$GLOBALS['bootscore_template_context'] = 'page-full-width-image';

get_header();
?>

  <div id="content" class="site-content">
    <div id="primary" class="content-area">
      
      <?php do_action( 'bootscore_after_primary_open', 'page-full-width-image' ); ?>

      <main id="main" class="site-main">

        <?php the_post(); ?>
        <div class="entry-header <?= esc_attr(apply_filters('bootscore/class/featured-full-width-img', 'position-relative d-flex align-items-end min-vh-60 bg-black mb-5', 'page-full-width-image')); ?>">

          <?php the_post_thumbnail('full', array(
            'class'         => 'position-absolute top-0 start-0 w-100 h-100 object-fit-cover',
            'loading'       => 'eager',
            'fetchpriority' => 'high',
          )); ?>

          <div class="<?= esc_attr(apply_filters('bootscore/class/container', 'container', 'page-full-width-image')); ?> <?= esc_attr(apply_filters('bootscore/class/featured-full-width-img/container', 'position-relative pb-5', 'page-full-width-image')); ?>">
            <?php do_action( 'bootscore_before_title', 'page-full-width-image' ); ?>
            <div class="<?= esc_attr(apply_filters('bootscore/class/full-width-img-title-wrapper', 'full-width-img-title-wrapper card border-0 card-body bg-body bg-50 bd-blur bd-saturate-150 w-fit', 'page-full-width-image')); ?>">
              <?php the_title('<h1 class="entry-title ' . esc_attr(apply_filters('bootscore/class/entry/title', '', 'page-full-width-image')) . '">', '</h1>'); ?>
            </div>
            <?php do_action( 'bootscore_after_title', 'page-full-width-image' ); ?>
          </div>

        </div>

        <div class="<?= esc_attr(apply_filters('bootscore/class/container', 'container', 'page-full-width-image')); ?> <?= esc_attr(apply_filters('bootscore/class/content/spacer', 'pt-7 pb-12', 'page-full-width-image')); ?>">
          
          <?php do_action( 'bootscore_after_featured_image', 'page-full-width-image' ); ?>
          
          <div class="row">
            <div class="<?= esc_attr(apply_filters('bootscore/class/main/col', 'col')); ?>">

              <div class="entry-content">
                <?php the_content(); ?>
              </div>
              
              <?php do_action( 'bootscore_before_entry_footer', 'page-full-width-image' ); ?>

              <div class="entry-footer">
                <?php comments_template(); ?>
              </div>

            </div>
            <?php get_sidebar(); ?>
          </div>
        </div>

      </main>

    </div>
  </div>

<?php
get_footer();
