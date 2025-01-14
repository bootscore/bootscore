<?php

/**
 * The template for displaying archive pages
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

  <div id="content" class="site-content <?= apply_filters('bootscore/class/container', 'container', 'archive'); ?> <?= apply_filters('bootscore/class/content/spacer', 'pt-4 pb-5', 'archive'); ?>">
    <div id="primary" class="content-area">
      
      <?php do_action('bootscore_after_primary_open', 'archive'); ?>

      <div class="row">
        <div class="<?= apply_filters('bootscore/class/main/col', 'col') ?>">

          <main id="main" class="site-main">

            <div class="entry-header">
              <?php do_action( 'bootscore_before_title', 'archive' ); ?>
              <?php the_archive_title('<h1 class="entry-title ' . apply_filters('bootscore/class/entry/title', '', 'archive') . '">', '</h1>'); ?>
              <?php do_action( 'bootscore_after_title', 'archive' ); ?>
              <?php the_archive_description('<p class="archive-description">', '</p>'); ?>
            </div>
            
            <?php do_action( 'bootscore_before_loop', 'archive' ); ?>

            <?php if (have_posts()) : ?>
              <?php while (have_posts()) : the_post(); ?>
            
              <?php do_action('bootscore_before_loop_item', 'archive'); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class( apply_filters('bootscore/class/loop/card', 'card horizontal mb-4', 'archive') ); ?>>
                  
                  <div class="<?= apply_filters('bootscore/class/loop/card/row', 'row g-0', 'archive'); ?>">

                    <?php if (has_post_thumbnail()) : ?>
                      <div class="<?= apply_filters('bootscore/class/loop/card/image/col', 'col-lg-6 col-xl-5 col-xxl-4', 'archive'); ?>">
                        <a href="<?php the_permalink(); ?>">
                          <?php the_post_thumbnail('medium', array('class' => apply_filters('bootscore/class/loop/card/image', 'card-img-lg-start', 'archive'))); ?>
                        </a>
                      </div>
                    <?php endif; ?>

                    <div class="<?= apply_filters('bootscore/class/loop/card/content/col', 'col', 'archive'); ?>">
                      <div class="<?= apply_filters('bootscore/class/loop/card/body', 'card-body', 'archive'); ?>">

                        <?php if (apply_filters('bootscore/loop/category', true, 'archive')) : ?>
                          <?php bootscore_category_badge(); ?>
                        <?php endif; ?>

                        <?php do_action('bootscore_before_loop_title', 'archive'); ?>
                        
                        <a class="text-body text-decoration-none" href="<?php the_permalink(); ?>">
                          <?php the_title('<h2 class="' . apply_filters('bootscore/class/loop/card/title', 'blog-post-title h5', 'archive') . '">', '</h2>'); ?>
                        </a>

                        <?php if (apply_filters('bootscore/loop/meta', true, 'archive')) : ?>
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

                        <?php if (apply_filters('bootscore/loop/excerpt', true, 'archive')) : ?>
                          <p class="<?= apply_filters('bootscore/class/loop/card-text/excerpt', 'card-text', 'archive'); ?>">
                            <a class="text-body text-decoration-none" href="<?php the_permalink(); ?>">
                              <?= strip_tags(get_the_excerpt()); ?>
                            </a>
                          </p>
                        <?php endif; ?>

                        <?php if (apply_filters('bootscore/loop/read-more', true, 'archive')) : ?>
                          <p class="<?= apply_filters('bootscore/class/loop/card-text/read-more', 'card-text', 'archive'); ?>">
                            <a class="<?= apply_filters('bootscore/class/loop/read-more', 'read-more', 'archive'); ?>" href="<?php the_permalink(); ?>">
                              <?= apply_filters('bootscore/loop/read-more/text', __('Read more »', 'bootscore', 'archive')); ?>
                            </a>
                          </p>
                        <?php endif; ?>

                        <?php if (apply_filters('bootscore/loop/tags', true, 'archive')) : ?>
                          <?php bootscore_tags(); ?>
                        <?php endif; ?>

                      </div>
                      
                      <?php do_action('bootscore_loop_item_after_card_body', 'archive'); ?>
                      
                    </div>
                    
                  </div>
                  
                </article>
            
                <?php do_action('bootscore_after_loop_item', 'archive'); ?>

              <?php endwhile; ?>
            <?php endif; ?>
            
            <?php do_action('bootscore_after_loop', 'archive'); ?>

            <div class="entry-footer">
              <?php bootscore_pagination(); ?>
            </div>

          </main>

        </div>
        <?php get_sidebar(); ?>
      </div>

    </div>
  </div>

<?php
get_footer();
