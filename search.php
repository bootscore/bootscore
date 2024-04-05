<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Bootscore
 * @version 6.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>
  <div id="content" class="site-content <?= apply_filters('bootscore/class/container', 'container', 'search'); ?> <?= apply_filters('bootscore/class/content/spacer', 'pt-4 pb-5', 'search'); ?>">
    <div id="primary" class="content-area">

      <div class="row">
        <div class="<?= apply_filters('bootscore/class/main/col', 'col'); ?>">

          <main id="main" class="site-main">

            <?php if (have_posts()) : ?>

              <div class="page-header mb-4">
                <h1>
                  <?php
                  /* translators: %s: search query. */
                  printf(esc_html__('Search Results for: %s', 'bootscore'), '<span class="text-body-secondary">' . get_search_query() . '</span>');
                  ?>
                </h1>
              </div>

              <?php
              /* Start the Loop */
              while (have_posts()) :
                the_post();

                /**
                 * Run the loop for the search to output the results.
                 * If you want to overload this in a child theme then include a file
                 * called content-search.php and that will be used instead.
                 */
                get_template_part('template-parts/search/content', 'search');

              endwhile;

              bootscore_pagination();

            else :

              get_template_part('template-parts/search/content', 'none');

            endif;
            ?>

          </main><!-- #main -->

        </div><!-- col -->
        <?php get_sidebar(); ?>
      </div><!-- row -->

    </div><!-- #primary -->
  </div><!-- #content -->
<?php
get_footer();
