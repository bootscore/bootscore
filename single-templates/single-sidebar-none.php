<?php
/**
 * Template Name: No Sidebar
 * Template Post Type: post
 *
 * @version 5.3.1
 */

get_header();
?>

  <div id="content" class="site-content <?= bootscore_container_class(); ?> py-5 mt-4">
    <div id="primary" class="content-area">

      <!-- Hook to add something nice -->
      <?php bs_after_primary(); ?>

      <?php the_breadcrumb(); ?>

      <main id="main" class="site-main">

        <header class="entry-header">
          <?php the_post(); ?>
          <?php bootscore_category_badge(); ?>
          <h1><?php the_title(); ?></h1>
          <p class="entry-meta">
            <small class="text-body-tertiary">
              <?php
              bootscore_date();
              bootscore_author();
              bootscore_comment_count();
              ?>
            </small>
          </p>
          <?php bootscore_post_thumbnail(); ?>
        </header>

        <div class="entry-content">
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

      </main>

    </div>
  </div>

<?php
get_footer();
