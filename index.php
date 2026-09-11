<?php

/**
 * The main template file
 * Template Version: 7.0.0
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

// Set global template context for custom layout hooks
$GLOBALS['bootscore_template_context'] = 'index';

get_header();
?>
<div id="content" class="site-content <?= esc_attr(apply_filters('bootscore/class/container', 'container', 'index')); ?> <?= esc_attr(apply_filters('bootscore/class/content/spacer', 'pt-4 pb-5', 'index')); ?>">
  <div id="primary" class="content-area">

    <?php do_action('bootscore_after_primary_open', 'index'); ?>

    <main id="main" class="site-main">

      <!-- Header -->
      <div class="p-5 text-center bg-body-tertiary rounded mb-4">
        <?php do_action('bootscore_before_title', 'index'); ?>
        <h1 class="entry-title <?= esc_attr(apply_filters('bootscore/class/entry/title', '', 'index')); ?>"><?= esc_html(get_bloginfo('name')); ?></h1>
        <?php do_action('bootscore_after_title', 'index'); ?>
        <p class="lead mb-0"><?= esc_html(get_bloginfo('description')); ?></p>
      </div>

      <!-- Main content row with sidebar -->
      <div class="row">
        <div class="<?= esc_attr(apply_filters('bootscore/class/main/col', 'col', 'index')); ?>">
          
          <?php do_action('bootscore_before_loop', 'index'); ?>

          <!-- Loop START -->
          <?php
          // Set layout via filter (can be overridden by plugins)
          $layout = apply_filters('bootscore/loop/layout', 'horizontal', 'index'); // 'horizontal', 'grid', 'overlay', or 'custom'

          // Default grid classes
          $grid_classes = apply_filters('bootscore/class/loop/grid/col',
            'row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4',
            'index'
          );

          // Default horizontal/overlay classes (both use same grid structure)
          $horizontal_classes = apply_filters('bootscore/class/loop/horizontal/col',
            'row row-cols-1 g-4 mb-4',
            'index'
          );
          ?>

          <?php if (have_posts()) : ?>

            <?php if ($layout === 'custom') : ?>
              <!-- Custom layout - NO GRID WRAPPER, just loop through posts -->
              <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('template-parts/loop/custom'); ?>
              <?php endwhile; ?>

            <?php else : ?>
              <!-- Grid or horizontal layout with wrapper -->
              <div class="<?= $layout === 'grid' ? esc_attr($grid_classes) : esc_attr($horizontal_classes); ?>">

                <?php while (have_posts()) : the_post(); ?>

                  <!-- Column wrapper for ALL layouts -->
                  <div class="col">

                    <?php if ($layout === 'grid') : ?>
                      <!-- Grid card -->
                      <?php get_template_part('template-parts/loop/cards-grid'); ?>
                      
                    <?php elseif ($layout === 'overlay') : ?>
                      <!-- Overlay card -->
                      <?php get_template_part('template-parts/loop/cards-overlay'); ?>
                      
                    <?php else : ?>
                      <!-- Horizontal card -->
                      <?php get_template_part('template-parts/loop/cards-horizontal'); ?>
                    <?php endif; ?>

                  </div><!-- .col -->

                <?php endwhile; ?>

              </div><!-- .row (loop row) -->
            <?php endif; ?>

          <?php else : ?>
            <!-- No posts found -->
            <?php get_template_part('template-parts/loop/loop-none'); ?>
          <?php endif; ?>
          <!-- Loop END -->

          <?php do_action('bootscore_after_loop', 'index'); ?>

          <div class="entry-footer">
            <?php do_action('bootscore_before_pagination', 'index'); ?>
            <?php bootscore_pagination(); ?>
          </div>

        </div><!-- .col (main content) -->
        
        <?php get_sidebar(); ?>
      </div><!-- .row (main content row) -->

    </main><!-- #main -->

  </div><!-- #primary -->
</div><!-- #content -->

<?php
get_footer();
