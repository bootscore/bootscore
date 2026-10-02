<?php

/**
 * Deprecated
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;




/**
 * Featured image
 */
if (!function_exists('bootscore_post_thumbnail')) :
  /**
   * Displays an optional post thumbnail.
   *
   * Wraps the post thumbnail in an anchor element on index views, or a div
   * element when on single views.
   */
  function bootscore_post_thumbnail() {
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
      return;
    }

    if (is_singular()) :
      ?>

      <div class="post-thumbnail">
        <?php the_post_thumbnail('full', array('class' => 'rounded mb-3')); ?>
      </div><!-- .post-thumbnail -->

    <?php else : ?>

      <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
        <?php
        the_post_thumbnail('post-thumbnail', array(
          'alt' => the_title_attribute(array(
            'echo' => false,
          )),
        ));
        ?>
      </a>

    <?php
    endif; // End is_singular().
  }
endif;



/**
 * Loop excerpt
 *
 * Display post excerpt with fallback to content
 * 
 * @param int $post_id Post ID (optional, uses current post if not set)
 * @param int $word_count Number of words to trim to (default: 55)
 */
if (!function_exists('bootscore_excerpt')) {
  function bootscore_excerpt($post_id = null, $word_count = 55) {
    // Get post ID
    $post_id = $post_id ?: get_the_ID();
    
    // Get excerpt or fallback to content
    $excerpt = get_post_field('post_excerpt', $post_id);
    if (empty($excerpt)) {
      $excerpt = get_post_field('post_content', $post_id);
    }
    
    // Clean and trim
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = wp_trim_words($excerpt, $word_count);
    
    // Output
    echo esc_html($excerpt);
  }
}