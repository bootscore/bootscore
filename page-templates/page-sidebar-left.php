<?php

/**
 * Template Name: Left Sidebar
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

  <div id="content" class="site-content <?= apply_filters('bootscore/class/container', 'container', 'page-sidebar-left'); ?> <?= apply_filters('bootscore/class/content/spacer', 'pt-4 pb-5', 'page-sidebar-left'); ?>">
    <div id="primary" class="content-area">

      <div class="row">
        <?php get_sidebar(); ?>
        <div class="<?= apply_filters('bootscore/class/main/col', 'col'); ?> order-first order-md-last">

          <main id="main" class="site-main">

            <div class="entry-header">
              <?php the_post(); ?>
              <h1><?php the_title(); ?></h1>
              <?php bootscore_post_thumbnail(); ?>
            </div>

            <div class="entry-content">
              <?php the_content(); ?>
            </div>

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
