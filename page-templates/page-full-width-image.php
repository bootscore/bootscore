<?php

/**
 * Template Name: Full Width Image
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

get_header();
?>

  <div id="content" class="site-content">
    <div id="primary" class="content-area">

      <main id="main" class="site-main">

        <?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
        <header class="entry-header featured-full-width-img height-75 bg-dark text-light mb-3" style="background-image: url('<?= $thumb['0']; ?>')">
          <div class="<?= apply_filters('bootscore/container_class', 'container', 'page-full-width-image'); ?> entry-header h-100 d-flex align-items-end pb-3">
            <div>
              <h1 class="entry-title"><?php the_title(); ?></h1>
            </div>
          </div>
        </header>

        <div class="<?= apply_filters('bootscore/container_class', 'container', 'page-full-width-image'); ?> pb-5">

          <!-- Hook to add something nice -->
          <?php bs_after_primary(); ?>

          <div class="row">
            <div class="<?= apply_filters('bootscore/main/col_class', 'col'); ?>">

              <div class="entry-content">
                <?php the_content(); ?>
              </div>

              <footer class="entry-footer">
                <?php comments_template(); ?>
              </footer>

            </div>
            <?php get_sidebar(); ?>
          </div>
        </div>

      </main>

    </div>
  </div>

<?php
get_footer();
