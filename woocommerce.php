<?php

/**
 * The template for displaying all WooCommerce pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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

  <div id="content" class="site-content <?= apply_filters('bootscore/class/container', 'container', 'woocommerce'); ?> <?= apply_filters('bootscore/class/content/spacer', 'pt-3 pb-5', 'woocommerce'); ?>">
    <div id="primary" class="content-area">

      <main id="main" class="site-main">

        <?php woocommerce_breadcrumb(); ?>
        <div class="row">
          <div class="<?= apply_filters('bootscore/class/main/col', 'col'); ?>">
            <?php woocommerce_content(); ?>
          </div>
          <?php get_sidebar(); ?>
        </div><!-- row -->
      </main><!-- #main -->
    </div><!-- #primary -->
  </div><!-- #content -->
<?php
get_footer();
