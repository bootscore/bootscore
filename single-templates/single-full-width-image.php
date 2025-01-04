<?php
/**
 * Template Name: Full width image
 * Template Post Type: post
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
      
      <?php do_action( 'bootscore_after_primary_open', 'single-full-width-image' ); ?>

      <main id="main" class="site-main">

        <?php the_post(); ?>
        <?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
        <div class="entry-header <?= apply_filters('bootscore/class/featured-full-width-img', 'featured-full-width-img height-75 bg-dark text-light mb-4', 'single-full-width-image'); ?>" style="background-image: url('<?= $thumb['0']; ?>')">
          <div class="<?= apply_filters('bootscore/class/container', 'container', 'single-full-width-image'); ?> <?= apply_filters('bootscore/class/featured-full-width-img/container', 'h-100 d-flex align-items-end pb-3', 'single-full-width-image'); ?>">
            <div class="<?= apply_filters('bootscore/class/full-width-img-title-wrapper', 'full-width-img-title-wrapper', 'single-full-width-image'); ?>">
              <?php do_action( 'bootscore_before_title', 'single-full-width-image' ); ?>
              <?php the_title('<h1 class="entry-title ' . apply_filters('bootscore/class/entry/title', '', 'single-full-width-image') . '">', '</h1>'); ?>
              <?php do_action( 'bootscore_after_title', 'single-full-width-image' ); ?>
            </div>
          </div>
        </div>

        <div class="<?= apply_filters('bootscore/class/container', 'container', 'single-full-width-image'); ?> <?= apply_filters('bootscore/class/content/spacer', 'pt-3 pb-5', 'single-full-width-image'); ?>">
          
          <?php do_action( 'bootscore_after_featured_image', 'single-full-width-image' ); ?>
          
          <?php the_breadcrumb(); ?>

          <div class="row">
            <div class="<?= apply_filters('bootscore/class/main/col', 'col'); ?>">

              <div class="entry-content">
                <?php bootscore_category_badge(); ?>
                <p class="entry-meta">
                  <small class="text-body-secondary">
                    <?php
                    bootscore_date();
                    bootscore_author();
                    bootscore_comment_count();
                    ?>
                  </small>
                </p>
                <?php the_content(); ?>
              </div>
              
              <?php do_action( 'bootscore_before_entry_footer', 'single-full-width-image' ); ?>

              <div class="entry-footer clear-both">
                <div class="mb-4">
                  <?php bootscore_tags(); ?>
                </div>
                <!-- Related posts using bS Swiper plugin -->
                <?php if (function_exists('bootscore_related_posts')) bootscore_related_posts(); ?>
                <nav aria-label="bs page navigation">
                  <ul class="pagination justify-content-center">
                    <li class="page-item">
                      <?php previous_post_link('%link'); ?>
                    </li>
                    <li class="page-item">
                      <?php next_post_link('%link'); ?>
                    </li>
                  </ul>
                </nav>
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
