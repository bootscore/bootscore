<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.1.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

  <div id="content" class="site-content <?= apply_filters('bootscore/class/container', 'container', 'page'); ?> <?= apply_filters('bootscore/class/content/spacer', 'pt-4 pb-5', 'page'); ?>">
    <div id="primary" class="content-area">
      
      <?php do_action( 'bootscore_after_primary_open', 'page' ); ?>

      <div class="row">
        <div class="<?= apply_filters('bootscore/class/main/col', 'col'); ?>">

          <main id="main" class="site-main">

            <div class="entry-header">
              <?php the_post(); ?>
              <?php do_action( 'bootscore_before_title', 'page' ); ?>
              <?php the_title('<h1 class="entry-title ' . apply_filters('bootscore/class/entry/title', '', 'page') . '">', '</h1>'); ?>
              <?php do_action( 'bootscore_after_title', 'page' ); ?>
              <?php bootscore_post_thumbnail(); ?>
            </div>
            
            <?php do_action( 'bootscore_after_featured_image', 'page' ); ?>

            <div class="entry-content">
              <?php the_content(); ?>
            </div>
            
            <?php do_action( 'bootscore_before_entry_footer', 'page' ); ?>

            <div class="entry-footer">
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
