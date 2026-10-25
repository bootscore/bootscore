<?php

/**
 * Template Name: Left Sidebar
 * Template Version: 7.0.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

  <div id="content" class="site-content <?= esc_attr(apply_filters('bootscore/class/container', 'container', 'page-sidebar-left')); ?> <?= esc_attr(apply_filters('bootscore/class/content/spacer', 'pt-7 pb-12', 'page-sidebar-left')); ?>">
    <div id="primary" class="content-area">
      
      <?php do_action( 'bootscore_after_primary_open', 'page-sidebar-left' ); ?>

      <div class="row">
        <?php get_sidebar(); ?>
        <div class="<?= esc_attr(apply_filters('bootscore/class/main/col', 'col')); ?> order-first lg:order-last">

          <main id="main" class="site-main">

            <div class="entry-header">
              <?php the_post(); ?>
              <?php do_action( 'bootscore_before_title', 'page-sidebar-left' ); ?>
              <?php the_title('<h1 class="entry-title ' . esc_attr(apply_filters('bootscore/class/entry/title', '', 'page-sidebar-left')) . '">', '</h1>'); ?>
              <?php do_action( 'bootscore_after_title', 'page-sidebar-left' ); ?>
              <?php if (!post_password_required()) the_post_thumbnail('full', array('class' => esc_attr(apply_filters('bootscore/class/featured-image', 'rounded mb-5', 'page-sidebar-left')))); ?>
            </div>
            
            <?php do_action( 'bootscore_after_featured_image', 'page-sidebar-left' ); ?>

            <div class="entry-content">
              <?php the_content(); ?>
            </div>
            
            <?php do_action( 'bootscore_before_entry_footer', 'page-sidebar-left' ); ?>

            <div class="entry-footer">
              <?php comments_template(); ?>
            </div>

          </main>

        </div>
      </div>

    </div>
  </div>

<?php
get_footer();
