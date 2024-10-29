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
 * @version 6.1.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>
  <div id="content" class="site-content <?= apply_filters('bootscore/class/container', 'container', 'index'); ?> <?= apply_filters('bootscore/class/content/spacer', 'pt-4 pb-5', 'index'); ?>">
    <div id="primary" class="content-area">
      
      <?php do_action( 'bootscore_after_primary_open', 'index' ); ?>

      <main id="main" class="site-main">

        <!-- Header -->
        <div class="p-5 text-center bg-body-tertiary rounded mb-4">
          <h1><?php bloginfo('name'); ?></h1>
          <p class="lead mb-0"><?php bloginfo('description'); ?></p>
        </div>

        <!-- Sticky Post -->
        <?php if (is_sticky() && is_home() && !is_paged()) : ?>
          <div class="row">
            <div class="col">
              
              <?php do_action( 'bootscore_before_loop', 'index-sticky' ); ?>
              
              <?php
              $args      = array(
                'posts_per_page'      => 2,
                'post__in'            => get_option('sticky_posts'),
                'ignore_sticky_posts' => 2
              );
              $the_query = new WP_Query($args);
              if ($the_query->have_posts()) :
                while ($the_query->have_posts()) : $the_query->the_post(); ?>
              
              <?php do_action( 'bootscore_before_loop_item', 'index-sticky' ); ?>

              <article id="post-<?php the_ID(); ?>" <?php post_class( apply_filters('bootscore/class/loop/card', 'card horizontal mb-4', 'index-sticky') ); ?>>

                <div class="row g-0">

                  <?php if (has_post_thumbnail()) : ?>
                    <div class="<?= apply_filters('bootscore/class/loop/card/image/col', 'col-lg-6 col-xl-5 col-xxl-4', 'index-sticky'); ?>">
                      <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium', array('class' => apply_filters('bootscore/class/loop/card/image', 'card-img-lg-start', 'index-sticky'))); ?>
                      </a>
                    </div>
                  <?php endif; ?>

                  <div class="col">
                    <div class="<?= apply_filters('bootscore/class/loop/card/body', 'card-body', 'index-sticky'); ?>">

                      <div class="row">
                        <div class="col-10">
                          <?php if (apply_filters('bootscore/loop/category', true, 'index-sticky')) : ?>
                            <?php bootscore_category_badge(); ?>
                          <?php endif; ?>
                        </div>
                        <div class="col-2 text-end">
                          <span class="badge text-bg-danger"><?= apply_filters('bootscore/icon/star', '<i class="fa-solid fa-star"></i>'); ?></span>
                        </div>
                      </div>

                      <a class="text-body text-decoration-none" href="<?php the_permalink(); ?>">
                        <?php the_title('<h2 class="blog-post-title h5">', '</h2>'); ?>
                      </a>

                      <?php if (apply_filters('bootscore/loop/meta', true, 'index-sticky')) : ?>
                        <?php if ('post' === get_post_type()) : ?>
                          <p class="meta small mb-2 text-body-secondary">
                            <?php
                            bootscore_date();
                            bootscore_author();
                            bootscore_comments();
                            bootscore_edit();
                            ?>
                          </p>
                        <?php endif; ?>
                      <?php endif; ?>

                      <?php if (apply_filters('bootscore/loop/excerpt', true, 'index-sticky')) : ?>
                        <p class="card-text">
                          <a class="text-body text-decoration-none" href="<?php the_permalink(); ?>">
                            <?= strip_tags(get_the_excerpt()); ?>
                          </a>
                        </p>
                      <?php endif; ?>

                      <?php if (apply_filters('bootscore/loop/read-more', true, 'index-sticky')) : ?>
                        <p class="card-text">
                          <a class="<?= apply_filters('bootscore/class/loop/read-more', 'read-more', 'index-sticky'); ?>" href="<?php the_permalink(); ?>">
                            <?= apply_filters('bootscore/loop/read-more/text', __('Read more »', 'bootscore', 'index-sticky')); ?>
                          </a>
                        </p>
                      <?php endif; ?>

                      <?php if (apply_filters('bootscore/loop/tags', true, 'index-sticky')) : ?>
                        <?php bootscore_tags(); ?>
                      <?php endif; ?>

                    </div>

                    <?php do_action( 'bootscore_loop_item_after_card_body', 'index-sticky' ); ?>

                  </div>
                  
                </div>

              </article>
              
              <?php do_action( 'bootscore_after_loop_item', 'index-sticky' ); ?>
                <?php
                endwhile;
              endif;
              wp_reset_postdata();
              ?>
              
              <?php do_action( 'bootscore_after_loop', 'index-sticky' ); ?>
              
            </div>

            <!-- col -->
          </div>
          <!-- row -->
        <?php endif; ?>
        
        <!-- Post List -->
        <div class="row">
          <div class="<?= apply_filters('bootscore/class/main/col', 'col'); ?>">
            
            <?php do_action( 'bootscore_before_loop', 'index' ); ?>
            
            <!-- Grid Layout -->
            <?php if (have_posts()) : ?>
              <?php while (have_posts()) : the_post(); ?>
                <?php if (is_sticky()) continue; //ignore sticky posts
                ?>
            
                <?php do_action( 'bootscore_before_loop_item', 'index' ); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class( apply_filters('bootscore/class/loop/card', 'card horizontal mb-4', 'index') ); ?>>
                  
                  <div class="row g-0">

                    <?php if (has_post_thumbnail()) : ?>
                      <div class="<?= apply_filters('bootscore/class/loop/card/image/col', 'col-lg-6 col-xl-5 col-xxl-4', 'index'); ?>">
                        <a href="<?php the_permalink(); ?>">
                          <?php the_post_thumbnail('medium', array('class' => apply_filters('bootscore/class/loop/card/image', 'card-img-lg-start', 'index'))); ?>
                        </a>
                      </div>
                    <?php endif; ?>

                    <div class="col">
                      
                      <div class="<?= apply_filters('bootscore/class/loop/card/body', 'card-body', 'index'); ?>">

                        <?php if (apply_filters('bootscore/loop/category', true, 'index')) : ?>
                          <?php bootscore_category_badge(); ?>
                        <?php endif; ?>

                        <a class="text-body text-decoration-none" href="<?php the_permalink(); ?>">
                          <?php the_title('<h2 class="blog-post-title h5">', '</h2>'); ?>
                        </a>

                        <?php if (apply_filters('bootscore/loop/meta', true, 'index')) : ?>
                          <?php if ('post' === get_post_type()) : ?>
                            <p class="meta small mb-2 text-body-secondary">
                              <?php
                              bootscore_date();
                              bootscore_author();
                              bootscore_comments();
                              bootscore_edit();
                              ?>
                            </p>
                          <?php endif; ?>
                        <?php endif; ?>

                        <?php if (apply_filters('bootscore/loop/excerpt', true, 'index')) : ?>
                          <p class="card-text">
                            <a class="text-body text-decoration-none" href="<?php the_permalink(); ?>">
                              <?= strip_tags(get_the_excerpt()); ?>
                            </a>
                          </p>
                        <?php endif; ?>

                        <?php if (apply_filters('bootscore/loop/read-more', true, 'index')) : ?>
                          <p class="card-text">
                            <a class="<?= apply_filters('bootscore/class/loop/read-more', 'read-more', 'index'); ?>" href="<?php the_permalink(); ?>">
                              <?= apply_filters('bootscore/loop/read-more/text', __('Read more »', 'bootscore', 'index')); ?>
                            </a>
                          </p>
                        <?php endif; ?>

                        <?php if (apply_filters('bootscore/loop/tags', true, 'index')) : ?>
                          <?php bootscore_tags(); ?>
                        <?php endif; ?>

                      </div>
                      
                      <?php do_action( 'bootscore_loop_item_after_card_body', 'index' ); ?>
                      
                    </div>
                    
                  </div>
                  
                </article>
            
                <?php do_action( 'bootscore_after_loop_item', 'index' ); ?>

              <?php endwhile; ?>
            <?php endif; ?>
            
            <?php do_action( 'bootscore_after_loop', 'index' ); ?>

            <div class="entry-footer">
              <?php bootscore_pagination(); ?>
            </div>

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
