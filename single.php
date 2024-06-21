<?php
/**
 * Template Post Type: post
 *
 * @package Bootscore
 * @version 6.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>

  <div id="content" class="site-content <?= apply_filters('bootscore/class/container', 'container', 'single'); ?> <?= apply_filters('bootscore/class/content/spacer', 'pt-3 pb-5', 'single'); ?>">
    <div id="primary" class="content-area">

      <?php the_breadcrumb(); ?>

      <div class="row">
        <div class="<?= apply_filters('bootscore/class/main/col', 'col'); ?>">

          <main id="main" class="site-main">

            <div class="entry-header">
              <?php the_post(); ?>
              <?php bootscore_category_badge(); ?>
              <h1><?php the_title(); ?></h1>
              <p class="entry-meta">
                <small class="text-body-secondary">
                  <?php
                  bootscore_date();
                  bootscore_author();
                  bootscore_comment_count();
                  ?>
                </small>
              </p>
              <?php bootscore_post_thumbnail(); ?>
            </div>

            <div class="entry-content">
              <?php the_content(); ?>
            </div>

            <div class="entry-footer clear-both">
              <div class="mb-4">
                <?php bootscore_tags(); ?>
              </div>
              <!-- Related posts using bS Swiper plugin -->
              <?php if (function_exists('bootscore_related_posts')) bootscore_related_posts(); ?>
              <nav aria-label="bs page navigation">
                <ul class="pagination justify-content-center">
                  <li class="page-item">
                    <?php previous_post_link('%link'); ?>
                  </li>
                  <li class="page-item">
                    <?php next_post_link('%link'); ?>
                  </li>
                </ul>
              </nav>
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
