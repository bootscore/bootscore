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

<div id="content" class="site-content <?php echo bootscore_container_class(); ?> py-5 mt-5">
  <div id="primary" class="content-area">

    <?php bs_after_primary(); ?>

    <div class="row">
      <div class="col">

        <main id="main" class="site-main">

          <header class="page-header mb-4">
            <?php the_archive_title('<h1>', '</h1>'); ?>
            <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
          </header>

          <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>

              <div class="card horizontal mb-4">
                <div class="row g-0">

                  <?php if ( has_post_thumbnail() ) : ?>
                    <div class="col-lg-6 col-xl-5 col-xxl-4">
                      <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium', array('class' => 'card-img-lg-start')); ?>
                      </a>
                    </div>
                  <?php endif; ?>

                  <div class="col">
                    <div class="card-body">

                      <?php bootscore_category_badge(); ?>

                      <a class="text-body text-decoration-none" href="<?php the_permalink(); ?>">
                        <?php the_title('<h2 class="blog-post-title h5">', '</h2>'); ?>
                      </a>

                      <?php if ('post' === get_post_type()) : ?>
                        <p class="meta small mb-2 text-muted">
                          <?php
                            bootscore_date();
                            bootscore_author();
                            bootscore_comments();
                            bootscore_edit();
                          ?>
                        </p>
                      <?php endif; ?>

                      <p class="card-text">
                        <a class="text-body text-decoration-none" href="<?php the_permalink(); ?>">
                          <?php echo strip_tags(get_the_excerpt()); ?>
                        </a>
                      </p>

                      <p class="card-text">
                        <a class="read-more" href="<?php the_permalink(); ?>">
                          <?php _e('Read more »', 'bootscore'); ?>
                        </a>
                      </p>

                      <?php bootscore_tags(); ?>

                    </div>
                  </div>
                </div>
              </div>

            <?php endwhile; ?>
          <?php endif; ?>

          <footer class="entry-footer">
            <?php bootscore_pagination(); ?>
          </footer>

        </main>

      </div>
      <?php get_sidebar(); ?>
    </div>

  </div>
</div>

<?php
get_footer();
