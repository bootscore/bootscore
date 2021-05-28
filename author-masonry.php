<?php
	/**
	 * Category Template: Equal Height
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
        
        <main id="main" class="site-main">

            <!-- Author & Bio -->
            <header class="page-header mb-4 d-flex">
                <div class="flex-shrink-0 me-3">
                    <?php echo get_avatar( get_the_author_meta('email'), '80', $default='', $alt='', array( 'class' => array( 'img-thumbnail rounded-circle' ) ) ); ?>
                </div>
                <div class="author-bio">
                    <h1><?php the_author(); ?></h1>
                    <?php the_author_meta('description'); ?>
                </div>
            </header>

            <div class="row" data-masonry='{"percentPosition": true }'>
                <?php if (have_posts() ) : ?>
                <?php while (have_posts() ) : the_post(); ?>

                <div class="col-md-6 col-lg-4 col-xxl-3 mb-4">

                    <div class="card">

                        <?php the_post_thumbnail('medium', array('class' => 'card-img-top')); ?>

                        <div class="card-body">

                            <?php bootscore_category_badge(); ?>

                            <h2 class="blog-post-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <?php if ( 'post' === get_post_type() ) : ?>

                            <small class="text-muted mb-2">
                                <?php
								bootscore_date();
								bootscore_author();
								bootscore_comments();
								bootscore_edit();
								?>
                            </small>

                            <?php endif; ?>

                            <div class="card-text">
                                <?php the_excerpt(); ?>
                            </div>

                            <div class="">
                                <a class="read-more" href="<?php the_permalink(); ?>"><?php _e('Read more »', 'bootscore'); ?></a>
                            </div>

                            <?php bootscore_tags(); ?>

                        </div><!-- card-body -->

                    </div><!-- card -->

                </div><!-- col -->

                <?php endwhile; ?>
                <?php endif; ?>

            </div><!-- row -->

            <!-- Pagination -->
            <div>
                <?php bootscore_pagination(); ?>
            </div>

        </main><!-- #main -->

    </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
