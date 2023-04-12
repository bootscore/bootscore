<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

get_header();
?>
<div id="content" class="site-content <?php echo bootscore_container_class(); ?> py-5 mt-4">
  <div id="primary" class="content-area">

    <!-- Hook to add something nice -->
    <?php bs_after_primary(); ?>

    <main id="main" class="site-main">

      <!-- Header -->
      <div class="py-3 py-md-5 text-center">
        <h1 class="display-1"><?php bloginfo('name'); ?></h1>
        <p class="lead"><?php bloginfo('description'); ?></p>
      </div>

      <!-- Sticky Post -->
      <?php if (is_sticky() && is_home() && !is_paged()) : ?>
        <div class="row">
          <div class="col">
            <?php
            $args = array(
              'posts_per_page' => 2,
              'post__in'  => get_option('sticky_posts'),
              'ignore_sticky_posts' => 2
            );
            $the_query = new WP_Query($args);
            if ($the_query->have_posts()) :
              while ($the_query->have_posts()) : $the_query->the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

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

                          <div class="row">
                            <div class="col-10">
                              <?php bootscore_category_badge(); ?>
                            </div>
                            <div class="col-2 text-end">
                              <span class="badge text-bg-danger"><i class="fa-solid fa-star"></i></span>
                            </div>
                          </div>

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

                </article>
            <?php
              endwhile;
            endif;
            wp_reset_postdata();
            ?>
          </div>

          <!-- col -->
        </div>
        <!-- row -->
      <?php endif; ?>
      <!-- Post List -->
      <div class="row">
        <div class="col col-md-8 col-lg-9">
          <!-- Grid Layout -->
          <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
              <?php if (is_sticky()) continue; //ignore sticky posts
              ?>

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

        </div>
        <!-- col -->
        <?php get_sidebar(); ?>
      </div>
      <!-- row -->
    </main><!-- #main -->

  </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
