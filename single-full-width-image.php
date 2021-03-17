<?php
	/*
	 * Template Name: Full width image
	 * Template Post Type: post
	 */
	  
	 get_header();  ?>

<div id="content" class="site-content">
    <div id="primary" class="content-area">

        <!-- Hook to add something nice -->
        <?php bs_after_primary(); ?>

        <main id="main" class="site-main">

            <header class="entry-header">
                <?php the_post(); ?>
                <!-- Featured Image-->
                <div class="height-75 bg-dark text-light align-items-end dflex mb-3" <?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );?> <div id="featured-full-image" class="page-full-image" style="background-image: url('<?php echo $thumb['0'];?>')">

                    <div class="container align-items-end d-flex h-100 pb-3">
                        <?php the_title('<h1>', '</h1>'); ?>
                    </div>

            </header>

            <div class="container pb-5">

                <div class="entry-content">

                    <?php bootscore_category_badge(); ?>

                    <p class="entry-meta">
                        <small class="text-muted">
                            <?php
							     bootscore_date();
							     _e(' by ', 'bootscore'); the_author_posts_link();
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
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item">
                                <?php previous_post_link('%link'); ?>
                            </li>
                            <li class="page-item">
                                <?php next_post_link('%link'); ?>
                            </li>
                        </ul>
                    </nav>
                </footer>

                <?php comments_template(); ?>

            </div><!-- container -->

        </main><!-- #main -->

    </div><!-- #primary -->
</div><!-- #content -->
<?php get_footer(); ?>
