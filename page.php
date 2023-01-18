<?php

/**
 * The template for displaying all pages
 *
 * SPDX-FileCopyrightText: © 2021 Bastian Kreitrer (crftwrk) <pst@crftwrk.de>
 * SPDX-License-Identifier: MIT
 *
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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
      <div class="col-md-8 col-xxl-9">

        <main id="main" class="site-main">

          <header class="entry-header">
            <?php the_post(); ?>
            <h1><?php the_title(); ?></h1>
            <?php bootscore_post_thumbnail(); ?>
          </header>

          <div class="entry-content">
            <?php the_content(); ?>
          </div>

          <footer class="entry-footer">
            <?php comments_template(); ?>
          </footer>

        </main>

      </div>
      <?php get_sidebar(); ?>
    </div>

  </div>
</div>

<?php
get_footer();