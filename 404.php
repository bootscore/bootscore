<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Bootscore
 * @version 6.1.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>
  <div id="content" class="site-content <?= apply_filters('bootscore/class/container', 'container', '404'); ?> <?= apply_filters('bootscore/class/content/spacer', 'pt-4 pb-5', '404'); ?>">
    <div id="primary" class="content-area">
      
      <?php do_action( 'bootscore_after_primary_open', '404' ); ?>

      <main id="main" class="site-main">

        <section class="error-404 not-found">
          <div class="page-404">

            <div class="entry-header">
              <?php do_action( 'bootscore_before_title', '404' ); ?>
              <h1 class="entry-title <?= apply_filters('bootscore/class/entry/title', '', '404'); ?>">404</h1>
              <?php do_action( 'bootscore_after_title', '404' ); ?>
            </div>
            <!-- Remove this line and place some widgets -->
            <p class="alert alert-info mb-4"><?php esc_html_e('Page not found.', 'bootscore'); ?></p>
            <!-- 404 Widget -->
            <?php if (is_active_sidebar('404-page')) : ?>
              <div><?php dynamic_sidebar('404-page'); ?></div>
            <?php endif; ?>
            <a class="btn btn-outline-primary" href="<?= esc_url(home_url()); ?>" role="button"><?php esc_html_e('Back Home &raquo;', 'bootscore'); ?></a>
          </div>
        </section><!-- .error-404 -->

      </main><!-- #main -->

    </div><!-- #primary -->
  </div><!-- #content -->

<?php
get_footer();
