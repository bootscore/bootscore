<?php
/**
 * Template Post Type: post
 * Template Version: 6.4.0
 *
 * @package Bootscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

  <div id="content" class="site-content <?= esc_attr(apply_filters('bootscore/class/container', 'container', 'single')); ?> <?= esc_attr(apply_filters('bootscore/class/content/spacer', 'pt-3 pb-5', 'single')); ?>">
    <div id="primary" class="content-area">
      
      <?php do_action( 'bootscore_after_primary_open', 'single' ); ?>

      <?php the_breadcrumb(); ?>

      <div class="row">
        <div class="<?= esc_attr(apply_filters('bootscore/class/main/col', 'col')); ?>">

          <main id="main" class="site-main">

            <div class="entry-header">
              <?php the_post(); ?>
              <?php bootscore_category_badge(); ?>
              <?php do_action( 'bootscore_before_title', 'single' ); ?>
              <?php the_title('<h1 class="entry-title ' . esc_attr(apply_filters('bootscore/class/entry/title', '', 'single')) . '">', '</h1>'); ?>
              <?php do_action( 'bootscore_after_title', 'single' ); ?>
              <p class="entry-meta">
                <small class="text-body-secondary">
                  <?php
                  bootscore_date();
                  bootscore_author();
                  bootscore_comment_count();
                  ?>
                </small>
              </p>
              <?php bootscore_post_thumbnail(); ?>
            </div>
            
            <?php do_action( 'bootscore_after_featured_image', 'single' ); ?>

            <div class="entry-content">
              <?php the_content(); ?>
            </div>
            
            <?php do_action( 'bootscore_before_entry_footer', 'single' ); ?>

            <div class="entry-footer clear-both">
              <div class="mb-4">
                <?php bootscore_tags(); ?>
              </div>
              
              <?php 
                // Related posts using bs Swiper plugin
                // Deprecated, new action bootscore_before_pagination will be used for related posts
                if (function_exists('bootscore_related_posts')) bootscore_related_posts(); 
              ?>
              
              <?php do_action( 'bootscore_before_pagination', 'single' ); ?>
              
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

          </main>

        </div>
        <?php get_sidebar(); ?>
      </div>

    </div>
  </div>

<?php
get_footer();
