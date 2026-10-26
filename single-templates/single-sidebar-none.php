<?php
/**
 * Template Name: No Sidebar
 * Template Post Type: post
 * Template Version: 7.0.0
 *
 * @package Bootscore
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

// Set global template context for custom layout hooks
$GLOBALS['bootscore_template_context'] = 'single-sidebar-none';

get_header();
?>

  <div id="content" class="site-content <?= esc_attr(apply_filters('bootscore/class/container', 'container', 'single-sidebar-none')); ?> <?= esc_attr(apply_filters('bootscore/class/content/spacer', 'pt-7 pb-12', 'single-sidebar-none')); ?>">
    <div id="primary" class="content-area">
      
     <?php do_action( 'bootscore_after_primary_open', 'single-sidebar-none' ); ?>

      <main id="main" class="site-main">

        <div class="entry-header">
          <?php the_post(); ?>
          <?php do_action( 'bootscore_before_title', 'single-sidebar-none' ); ?>
          <?php the_title('<h1 class="entry-title ' . esc_attr(apply_filters('bootscore/class/entry/title', '', 'single-sidebar-none')) . '">', '</h1>'); ?>
          <?php do_action( 'bootscore_after_title', 'single-sidebar-none' ); ?>
          <p class="entry-meta fg-secondary fs-sm">
            <?php
            bootscore_date();
            bootscore_author();
            bootscore_comment_count();
            ?>
          </p>
          <?php if (!post_password_required()) the_post_thumbnail('full', array('class' => esc_attr(apply_filters('bootscore/class/featured-image', 'rounded mb-5', 'single-sidebar-none')))); ?>
        </div>
        
        <?php do_action( 'bootscore_after_featured_image', 'single-sidebar-none' ); ?>

        <div class="entry-content d-flow-root">
          <?php the_content(); ?>
        </div>
        
        <?php do_action( 'bootscore_before_entry_footer', 'single-sidebar-none' ); ?>

        <div class="entry-footer">
          <div class="mb-4">
            <?php bootscore_tags(); ?>
          </div>
        
          <?php 
            // Related posts using bs Swiper plugin
            // Deprecated, new action bootscore_before_pagination will be used for related posts
            if (function_exists('bootscore_related_posts')) bootscore_related_posts(); 
          ?>
          
          <?php do_action( 'bootscore_before_single_pagination', 'single-sidebar-none' ); ?>
          
          <?php bootscore_single_pagination(); ?>
          
          <?php comments_template(); ?>
        </div>

      </main>

    </div>
  </div>

<?php
get_footer();
