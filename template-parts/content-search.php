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
    <div class="row">
      <!-- Featured Image-->
      <?php if (has_post_thumbnail())
        echo '<div class="card-img-left-md col-lg-5">' . get_the_post_thumbnail(null, 'medium') . '</div>';
      ?>
      <div class="col">
        <div class="card-body">

          <?php bootscore_category_badge(); ?>

          <!-- Title -->
          <h2 class="blog-post-title">
            <a href="<?php the_permalink(); ?>">
              <?php the_title(); ?>
            </a>
          </h2>
          <!-- Meta -->
          <?php if ('post' === get_post_type()) : ?>
            <small class="text-muted mb-2">
              <?php
              bootscore_date();
              bootscore_author();
              bootscore_comments();
              bootscore_edit();
              ?>
            </small>
          <?php endif; ?>
          <!-- Excerpt & Read more -->
          <div class="card-text mt-auto">
            <?php the_excerpt(); ?> <a class="read-more" href="<?php the_permalink(); ?>"><?php _e('Read more »', 'bootscore'); ?></a>
          </div>
          <!-- Tags -->
          <?php bootscore_tags(); ?>
        </div>
      </div>
    </div>
  </div>
</article>
<!-- #post-<?php the_ID(); ?> -->