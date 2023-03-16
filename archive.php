<?php

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

get_header();
?>

<div id="content" class="site-content container py-5 mt-5">
  <div id="primary" class="content-area">

    <!-- Hook to add something nice -->
    <?php bs_after_primary(); ?>

    <div class="row">
      <div class="col">

        <main id="main" class="site-main">

          <!-- Title & Description -->
          <header class="page-header">
            <h1><?php the_archive_title(); ?></h1>
            <div class="archive-description lead">
              <?php the_archive_description(); ?>
            </div>
          </header>

          <!-- Grid Layout -->
          <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
              <div class="card horizontal mb-4">
                <div class="row g-0">
                  <!-- Featured Image-->
                  <?php if (has_post_thumbnail())
                    echo '<div class="card-img-left-md col-lg-6 col-xl-5 col-xxl-4">' . get_the_post_thumbnail(null, 'medium') . '</div>';
                  ?>
                  <div class="col">
                    <div class="card-body">

                      <?php bootscore_category_badge(); ?>

                      <!-- Title -->
                      <h2 class="blog-post-title h4">
                        <a class="text-body text-decoration-none" href="<?php the_permalink(); ?>">
                          <?php the_title(); ?>
                        </a>
                      </h2>
                      <!-- Meta -->
                      <?php if ('post' === get_post_type()) : ?>
                        <p class="small mb-2 text-muted">
                          <?php
                            bootscore_date();
                            bootscore_author();
                            bootscore_comments();
                            bootscore_edit();
                          ?>
                        </p>
                      <?php endif; ?>
                      <!-- Excerpt & Read more -->
                      <div class="card-text">
                        <a class="text-body text-decoration-none" href="<?php the_permalink(); ?>">
                        <?php the_excerpt(); ?> 
                        </a>
                      </div>
                       <p class="card-text"><a class="read-more" href="<?php the_permalink(); ?>"><?php _e('Read more »', 'bootscore'); ?></a></p>
     
                      <!-- Tags -->
                      <?php bootscore_tags(); ?>

                    </div>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php endif; ?>

          <!-- Pagination -->
          <div>
            <?php bootscore_pagination(); ?>
          </div>

        </main><!-- #main -->

      </div><!-- col -->

      <?php get_sidebar(); ?>
    </div><!-- row -->

  </div><!-- #primary -->
</div><!-- #content -->

<?php
get_footer();
