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
defined( 'ABSPATH' ) || exit;

get_header();
?>

  <div id="content" class="site-content <?= apply_filters('bootscore/container_class', 'container', 'woocommerce'); ?> <?= apply_filters('bootscore/content/spacer_class', 'py-5 mt-4', 'woocommerce'); ?>">
    <div id="primary" class="content-area">

      <!-- Hook to add something nice -->
      <?php bs_after_primary(); ?>

      <main id="main" class="site-main">

        <!-- Breadcrumb -->
        <?php woocommerce_breadcrumb(); ?>
        <div class="row">
          <div class="<?= apply_filters('bootscore/main/col_class', 'col'); ?>">
            <?php woocommerce_content(); ?>
          </div>
          <!-- sidebar -->
          <?php get_sidebar(); ?>
          <!-- row -->
        </div>
      </main><!-- #main -->
    </div><!-- #primary -->
  </div><!-- #content -->
<?php
get_footer();
