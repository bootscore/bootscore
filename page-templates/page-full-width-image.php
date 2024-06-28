<?php

/**
 * Template Name: Full Width Image
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

  <div id="content" class="site-content">
    <div id="primary" class="content-area">

      <main id="main" class="site-main">

        <?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
        <div class="entry-header featured-full-width-img height-75 bg-dark text-light mb-4" style="background-image: url('<?= $thumb['0']; ?>')">
          <div class="<?= apply_filters('bootscore/class/container', 'container', 'page-full-width-image'); ?> entry-header h-100 d-flex align-items-end pb-3">
            <div class="full-width-img-title">
              <h1 class="entry-title"><?php the_title(); ?></h1>
            </div>
          </div>
        </div>

        <div class="<?= apply_filters('bootscore/class/container', 'container', 'page-full-width-image'); ?> <?= apply_filters('bootscore/class/content/spacer', 'pb-5', 'page-full-width-image'); ?>">

          <div class="row">
            <div class="<?= apply_filters('bootscore/class/main/col', 'col'); ?>">

              <div class="entry-content">
                <?php the_content(); ?>
              </div>

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
