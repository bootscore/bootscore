<?php
    /**
     * Template Name: Full Width Image
     *
     * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
     *
     * @package Bootscore
     */
    
    get_header();
    ?>

<div id="content" class="site-content">
    <div id="primary" class="content-area">

        <!-- Hook to add something nice -->
        <?php bs_after_primary(); ?>

        <main id="main" class="site-main">

            <header class="entry-header">
                <?php the_post(); ?>
                <div class="height-75 bg-dark text-light align-items-end dflex mb-3" <?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );?> <div id="featured-full-image" class"page-full-image" style="background-image: url('<?php echo $thumb['0'];?>')">
                    <div class="container align-items-end d-flex h-100 pb-3">
                        <?php the_title('<h1>', '</h1>'); ?>
                    </div>
            </header>

            <div class="container pb-5">

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <footer class="entry-footer">

                </footer>

                <?php comments_template(); ?>

            </div><!-- container -->

        </main><!-- #main -->

    </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
