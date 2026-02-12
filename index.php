<?php

/**
 * The main template file
 * Template Version: 6.4.0
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

// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();
?>
  <div id="content" class="site-content <?= esc_attr(apply_filters('bootscore/class/container', 'container', 'index')); ?> <?= esc_attr(apply_filters('bootscore/class/content/spacer', 'pt-4 pb-5', 'index')); ?>">
      <div id="primary" class="content-area">

        <?php do_action('bootscore_after_primary_open', 'index'); ?>

        <main id="main" class="site-main">

          <!-- Header -->
          <div class="p-5 text-center bg-body-tertiary rounded mb-4">
            <?php do_action( 'bootscore_before_title', 'index' ); ?>
            <h1 class="entry-title <?= esc_attr(apply_filters('bootscore/class/entry/title', '', 'index')); ?>"><?= esc_html(get_bloginfo('name')); ?></h1>
            <?php do_action( 'bootscore_after_title', 'index' ); ?>
            <p class="lead mb-0"><?= esc_html(get_bloginfo('description')); ?></p>
          </div>

          <!-- Post List -->
          <div class="row">
            <div class="<?= esc_attr(apply_filters('bootscore/class/main/col', 'col')); ?>">

                <?php do_action( 'bootscore_before_loop', 'index' ); ?>

                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                  <?php do_action( 'bootscore_before_loop_item', 'index' ); ?>

                  <article id="post-<?php the_ID(); ?>" <?php post_class( esc_attr(apply_filters('bootscore/class/loop/card', 'card horizontal mb-4', 'index')) ); ?>>

                    <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/row', 'row g-0', 'index')); ?>">

                      <?php do_action('bootscore_before_loop_thumbnail', 'index'); ?>
                      
                      <?php if (has_post_thumbnail()) : ?>
                        <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/image/col', 'col-lg-6 col-xl-5 col-xxl-4', 'index')); ?>">
                          <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium', array('class' => esc_attr(apply_filters('bootscore/class/loop/card/image', 'card-img-lg-start', 'index')))); ?>
                          </a>
                        </div>
                      <?php endif; ?>
                      
                      <?php do_action('bootscore_after_loop_thumbnail', 'index'); ?>

                      <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/content/col', 'col', 'index')); ?>">
                        <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/body', 'card-body', 'index')); ?>">
                          
                          <div class="<?= esc_attr(apply_filters('bootscore/class/loop/card/content/meta-wrapper', 'd-flex justify-content-between gap-3')); ?>">

                            <?php if (apply_filters('bootscore/loop/category', true, 'index')) : ?>
                              <?php bootscore_category_badge(); ?>
                            <?php endif; ?>

                            <?php if (is_sticky() ) { ?>
                              <p class="sticky-badge"><span class="<?= esc_attr(apply_filters('bootscore/class/loop/card/content/sticky-post-badge', 'badge text-bg-danger')); ?>"><?= wp_kses_post(apply_filters('bootscore/icon/star', '<i class="fa-solid fa-star"></i>')); ?></span></p>
                            <?php } ?>
                            
                          </div>
                          
                          <?php do_action('bootscore_before_loop_title', 'index'); ?>
                          
                          <a class="<?= esc_attr(apply_filters('bootscore/class/loop/card/title/link', 'text-body text-decoration-none', 'index')); ?>" href="<?php the_permalink(); ?>">
                            <?php the_title('<h2 class="' . esc_attr(apply_filters('bootscore/class/loop/card/title', 'blog-post-title h5', 'index')) . '">', '</h2>'); ?>
                          </a>
                          
                          <?php do_action('bootscore_after_loop_title', 'index'); ?>

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
                            <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/excerpt', 'card-text', 'index')); ?>">
                              <a class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/excerpt/link', 'text-body text-decoration-none', 'index')); ?>" href="<?php the_permalink(); ?>">
                                <?= esc_html(wp_strip_all_tags(get_the_excerpt())); ?>
                              </a>
                            </p>
                          <?php endif; ?>

                          <?php if (apply_filters('bootscore/loop/read-more', true, 'index')) : ?>
                            <p class="<?= esc_attr(apply_filters('bootscore/class/loop/card-text/read-more', 'card-text', 'index')); ?>">
                              <a class="<?= esc_attr(apply_filters('bootscore/class/loop/read-more', 'read-more', 'index')); ?>" href="<?php the_permalink(); ?>">
                                <?= wp_kses_post(apply_filters('bootscore/loop/read-more/text', __('Read more »', 'bootscore', 'index'))); ?>
                              </a>
                            </p>
                          <?php endif; ?>

                          <?php if (apply_filters('bootscore/loop/tags', true, 'index')) : ?>
                            <?php bootscore_tags(); ?>
                          <?php endif; ?>
                          
                          <?php do_action('bootscore_after_loop_tags', 'index'); ?>

                        </div>

                        <?php do_action('bootscore_loop_item_after_card_body', 'index'); ?>

                      </div><!-- col -->
                    </div><!-- row -->
                  </article><!-- article -->

                  <?php do_action('bootscore_after_loop_item', 'index'); ?>

                <?php endwhile; ?>
                <?php endif; ?>

                <?php do_action('bootscore_after_loop', 'index'); ?>

              <div class="entry-footer">
                
                <?php do_action( 'bootscore_before_pagination', 'index' ); ?>
                
                <?php bootscore_pagination(); ?>
                
              </div>
              
            </div><!-- col -->
            <?php get_sidebar(); ?>
          </div><!-- row -->
      </main><!-- #main -->
    </div><!-- #primary -->
  </div><!-- #content -->
<?php
get_footer();
