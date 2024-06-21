<?php

/**
 * Template Name: No Sidebar
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

  <div id="content" class="site-content <?= apply_filters('bootscore/class/container', 'container', 'page-sidebar-none'); ?> <?= apply_filters('bootscore/class/content/spacer', 'pt-4 pb-5', 'page-sidebar-none'); ?>">
    <div id="primary" class="content-area">

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

<?php
get_footer();
