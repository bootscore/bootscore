<?php

/**
 * Template Name: Left Sidebar
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

get_header();
?>

<div id="content" class="site-content container py-5 mt-5">
  <div id="primary" class="content-area">

    <!-- Hook to add something nice -->
    <?php bs_after_primary(); ?>

    <div class="row">
      <!-- sidebar -->
      <?php get_sidebar(); ?>
      <div class="col-md-8 col-xxl-9 order-first order-md-last">

        <main id="main" class="site-main">

          <header class="entry-header">
            <?php the_post(); ?>
            <!-- Title -->
            <?php the_title('<h1>', '</h1>'); ?>
            <!-- Featured Image-->
            <?php bootscore_post_thumbnail(); ?>
          </header>

          <div class="entry-content">
            <!-- Content -->
            <?php the_content(); ?>
          </div>

          <footer class="entry-footer">
          <!-- Comments -->
          <?php comments_template(); ?>
          </footer>

        </main><!-- #main -->

      </div><!-- col -->
    </div><!-- row -->

  </div><!-- #primary -->
</div><!-- #contenty -->
<?php
get_footer();
