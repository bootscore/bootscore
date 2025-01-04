<?php

/**
 * Template Name: Full Width Image
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.1.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

  <div id="content" class="site-content">
    <div id="primary" class="content-area">
      
      <?php do_action( 'bootscore_after_primary_open', 'page-full-width-image' ); ?>

      <main id="main" class="site-main">

        <?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
        <div class="entry-header <?= apply_filters('bootscore/class/featured-full-width-img', 'featured-full-width-img height-75 bg-dark text-light mb-4', 'page-full-width-image'); ?>" style="background-image: url('<?= $thumb['0']; ?>')">
          <div class="<?= apply_filters('bootscore/class/container', 'container', 'page-full-width-image'); ?> <?= apply_filters('bootscore/class/featured-full-width-img/container', 'h-100 d-flex align-items-end pb-3', 'page-full-width-image'); ?>">
            <div class="<?= apply_filters('bootscore/class/full-width-img-title-wrapper', 'full-width-img-title-wrapper', 'page-full-width-image'); ?>">
              <?php do_action( 'bootscore_before_title', 'page-full-width-image' ); ?>
              <?php the_title('<h1 class="entry-title ' . apply_filters('bootscore/class/entry/title', '', 'page-full-width-image') . '">', '</h1>'); ?>
              <?php do_action( 'bootscore_after_title', 'page-full-width-image' ); ?>
            </div>
          </div>
        </div>

        <div class="<?= apply_filters('bootscore/class/container', 'container', 'page-full-width-image'); ?> <?= apply_filters('bootscore/class/content/spacer', 'pb-5', 'page-full-width-image'); ?>">
          
          <?php do_action( 'bootscore_after_featured_image', 'page-full-width-image' ); ?>
          
          <div class="row">
            <div class="<?= apply_filters('bootscore/class/main/col', 'col'); ?>">

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
