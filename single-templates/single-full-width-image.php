<?php
/**
 * Template Name: Full width image
 * Template Post Type: post
 *
 * @version 5.3.1
 */

get_header();
?>

  <div id="content" class="site-content">
    <div id="primary" class="content-area">

      <main id="main" class="site-main">

        <?php the_post(); ?>
        <?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
        <header class="entry-header featured-full-width-img height-75 bg-dark text-light mb-3" style="background-image: url('<?= $thumb['0']; ?>')">
          <div class="<?= bootscore_container_class(); ?> entry-header h-100 d-flex align-items-end pb-3">
            <div>
              <h1 class="entry-title"><?php the_title(); ?></h1>
            </div>
          </div>
        </header>

        <div class="<?= bootscore_container_class(); ?> pb-5">

          <!-- Hook to add something nice -->
          <?php bs_after_primary(); ?>

          <?php the_breadcrumb(); ?>

          <div class="row">
            <div class="<?= bootscore_main_col_class(); ?>">

              <div class="entry-content">
                <?php bootscore_category_badge(); ?>
                <p class="entry-meta">
                  <small class="text-body-tertiary">
                    <?php
                    bootscore_date();
                    bootscore_author();
                    bootscore_comment_count();
                    ?>
                  </small>
                </p>
                <?php the_content(); ?>
              </div>

              <footer class="entry-footer clear-both">
                <div class="mb-4">
                  <?php bootscore_tags(); ?>
                </div>
                <!-- Related posts using bS Swiper plugin -->
                <?php if (function_exists('bootscore_related_posts')) bootscore_related_posts(); ?>
                <nav aria-label="bS page navigation">
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
              </footer>

            </div>
            <?php get_sidebar(); ?>
          </div>

        </div>

      </main>

    </div>
  </div>

<?php
get_footer();
