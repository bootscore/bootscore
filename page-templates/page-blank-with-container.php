<?php

/**
 * Template Name: Blank with container
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 5.4.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

  <div id="content" class="site-content <?= apply_filters('bootscore/container_class', 'container', 'page-blank-with-container'); ?>">
    <div id="primary" class="content-area">

      <!-- Hook to add something nice -->
      <?php bs_after_primary(); ?>

      <main id="main" class="site-main">

        <div class="entry-content">
          <?php the_post(); ?>
          <?php the_content(); ?>
        </div>

      </main>

    </div>
  </div>

<?php
get_footer();
