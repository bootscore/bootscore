<?php

/**
 * The template for displaying search results pages
 * Template Version: 7.0.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Bootscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

// Set global template context for custom layout hooks
$GLOBALS['bootscore_template_context'] = 'search';

get_header();
?>
<div id="content" class="site-content <?= esc_attr(apply_filters('bootscore/class/container', 'container', 'search')); ?> <?= esc_attr(apply_filters('bootscore/class/content/spacer', 'pt-4 pb-5', 'search')); ?>">
  <div id="primary" class="content-area">
    
    <?php do_action('bootscore_after_primary_open', 'search'); ?>

    <div class="row">
      <div class="<?= esc_attr(apply_filters('bootscore/class/main/col', 'col', 'search')); ?>">

        <main id="main" class="site-main">

          <?php if (have_posts()) : ?>

            <div class="entry-header">
              <?php do_action('bootscore_before_title', 'search'); ?>
              <h1 class="entry-title <?= esc_attr(apply_filters('bootscore/class/entry/title', '', 'search')); ?>">
                <?php
                /* translators: %s: search query. */
                printf(esc_html__('Search Results for: %s', 'bootscore'), '<span class="text-body-secondary">' . esc_html(get_search_query()) . '</span>');
                ?>
              </h1>
              <?php do_action('bootscore_after_title', 'search'); ?>
            </div>
          
            <?php do_action('bootscore_before_loop', 'search'); ?>

            <!-- Loop START -->
            <?php
            // Set layout via filter (can be overridden by plugins)
            $layout = apply_filters('bootscore/loop/layout', 'horizontal', 'search'); // 'horizontal', 'grid', 'overlay', or 'custom'

            // Default grid classes
            $grid_classes = apply_filters('bootscore/class/loop/grid/col',
              'row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4',
              'search'
            );

            // Default horizontal/overlay classes (both use same grid structure)
            $horizontal_classes = apply_filters('bootscore/class/loop/horizontal/col',
              'row row-cols-1 g-4 mb-4',
              'search'
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

            <?php do_action('bootscore_after_loop', 'search'); ?>
            
            <?php do_action('bootscore_before_pagination', 'search'); ?>

            <?php bootscore_pagination(); ?>

          <?php else : ?>

            <?php get_template_part('template-parts/loop/content', 'none'); ?>

          <?php endif; ?>
          
        </main>

      </div><!-- .col -->
      <?php get_sidebar(); ?>
    </div><!-- .row -->

  </div><!-- #primary -->
</div><!-- #content -->

<?php
get_footer();
