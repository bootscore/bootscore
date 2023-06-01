<?php

/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

  <div class="card horizontal mb-4">
    <div class="row g-0">

      <?php if (has_post_thumbnail()) : ?>
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
            <p class="meta small mb-2 text-body-tertiary">
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
<!-- #post-<?php the_ID(); ?> -->