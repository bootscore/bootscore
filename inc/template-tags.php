<?php

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly.
defined('ABSPATH') || exit;


/**
 * Category Badge
 */
if (!function_exists('bootscore_category_badge')) :
  function bootscore_category_badge() {
    // Hide category and tag text for pages.
    if ('post' === get_post_type()) {
      echo '<p class="category-badge">';
      $thelist = '';
      $i       = 0;
      foreach (get_the_category() as $category) {
        if (0 < $i) $thelist .= ' ';
        // Apply a filter to modify the class name
        $class = apply_filters('bootscore/class/badge/category', 'badge bg-primary-subtle text-primary-emphasis text-decoration-none');
        $thelist .= '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="' . esc_attr($class) . '">' . esc_html($category->name) . '</a>';
        $i ++;
      }
      echo wp_kses_post($thelist);
      echo '</p>';
    }
  }
endif;


/**
 * Category
 */
if (!function_exists('bootscore_category')) :
  function bootscore_category() {
    // Hide category and tag text for pages.
    if ('post' === get_post_type()) {
      /* translators: used between list items, there is a space after the comma */
      $categories_list = get_the_category_list(esc_html__(', ', 'bootscore'));
      if ($categories_list) {
        /* translators: 1: list of categories. */
        printf('<span class="cat-links">%s</span>', $categories_list); // WPCS: XSS OK.	
      }
    }
  }
endif;


/**
 * Date
 */
if (!function_exists('bootscore_date')) :

  /**
   * Prints HTML with meta information for the current post-date/time.
   */
  function bootscore_date() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    
    // Check if modified time is different from the published time
    if (get_the_time('U') !== get_the_modified_time('U')) {
      $show_updated_time = apply_filters('bootscore/meta/time/updated', true);
      
      // If filter returns false, don't display modified time
      if (!$show_updated_time) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
      } else {
        $icon = wp_kses(
          apply_filters(
            'bootscore/icon/arrow-rotate',
            '<svg class="bs-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" aria-hidden="true"><path d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160 352 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l111.5 0c0 0 0 0 0 0l.4 0c17.7 0 32-14.3 32-32l0-112c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 35.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1L16 432c0 17.7 14.3 32 32 32s32-14.3 32-32l0-35.1 17.6 17.5c0 0 0 0 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.8c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352l34.4 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L48.4 288c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"/></svg>'
          ),
          bootscore_kses_allowed_svg( wp_kses_allowed_html( 'post' ) )
        );

        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time> <span class="time-updated-separator">/</span> ' . $icon . ' <time class="updated" datetime="%3$s">%4$s</time>';
      }
    }

    $time_string = sprintf(
      $time_string,
      esc_attr(get_the_date(DATE_W3C)),
      esc_html(get_the_date()),
      esc_attr(get_the_modified_date(DATE_W3C)),
      esc_html(get_the_modified_date())
    );

    $posted_on = sprintf(
      /* translators: %s: post date. */
      '%s',
      '<span rel="bookmark">' . $time_string . '</span>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

  }
endif;


/**
 * Author
 */
if (!function_exists('bootscore_author')) {

  function bootscore_author() {
    $display_author = apply_filters('bootscore/meta/author', true);

    // Check if the filter returns false, if so, return early without displaying the author
    if (!$display_author) {
      return;
    }

    $byline = sprintf(
      esc_html_x('by %s', 'post author', 'bootscore'),
      '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );

    echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

  }
}


/**
 * Fix wpautop in author description archive.php
 * See https://github.com/bootscore/bootscore/pull/1017
 */
add_filter('get_the_archive_description', function ($description) {
  if (is_author()) {
    return wpautop($description);
  }
  return $description;
});


/**
 * Comments
 */
if (!function_exists('bootscore_comments')) :
  /**
   * Prints HTML with meta information for the categories, tags and comments.
   */
  function bootscore_comments() {

    if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
      echo ' <span class="comment-divider">|</span> ' . wp_kses( apply_filters('bootscore/icon/comments', '<svg class="bs-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" aria-hidden="true"><path d="M123.6 391.3c12.9-9.4 29.6-11.8 44.6-6.4c26.5 9.6 56.2 15.1 87.8 15.1c124.7 0 208-80.5 208-160s-83.3-160-208-160S48 160.5 48 240c0 32 12.4 62.8 35.7 89.2c8.6 9.7 12.8 22.5 11.8 35.5c-1.4 18.1-5.7 34.7-11.3 49.4c17-7.9 31.1-16.7 39.4-22.7zM21.2 431.9c1.8-2.7 3.5-5.4 5.1-8.1c10-16.6 19.5-38.4 21.4-62.9C17.7 326.8 0 285.1 0 240C0 125.1 114.6 32 256 32s256 93.1 256 208s-114.6 208-256 208c-37.1 0-72.3-6.4-104.1-17.9c-11.9 8.7-31.3 20.6-54.3 30.6c-15.1 6.6-32.3 12.6-50.1 16.1c-.8 .2-1.6 .3-2.4 .5c-4.4 .8-8.7 1.5-13.2 1.9c-.2 0-.5 .1-.7 .1c-5.1 .5-10.2 .8-15.3 .8c-6.5 0-12.3-3.9-14.8-9.9c-2.5-6-1.1-12.8 3.4-17.4c4.1-4.2 7.8-8.7 11.3-13.5c1.7-2.3 3.3-4.6 4.8-6.9l.3-.5z"/></svg>'), bootscore_kses_allowed_svg( wp_kses_allowed_html( 'post' ) ) ) . ' <span class="comments-link">';
      comments_popup_link(
        sprintf(
          wp_kses(
          /* translators: %s: post title */
            __('Leave a Comment', 'bootscore'),
            array(
              'span' => array(
                'class' => array(),
              ),
            )
          ),
          get_the_title()
        )
      );
      echo '</span>';
    }
  }
endif;


/**
 * Edit link
 */
if (!function_exists('bootscore_edit')) :
  /**
   * Prints HTML with the edit link for the current post.
   */
  function bootscore_edit() {

    edit_post_link(
      sprintf(
        wp_kses(
        /* translators: %s: Name of current post. Only visible to screen readers */
          __('Edit', 'bootscore'),
          array(
            'span' => array(
              'class' => array(),
            ),
          )
        ),
        get_the_title()
      ),
      ' | <span class="edit-link">',
      '</span>'
    );
  }
endif;


/**
 * Single comments count
 */
if (!function_exists('bootscore_comment_count')) :
  /**
   * Prints HTML with the comment count for the current post.
   */
  function bootscore_comment_count() {
    if (!post_password_required() && (comments_open() || get_comments_number())) {
      echo ' <span class="comment-divider">|</span> ' . wp_kses( apply_filters('bootscore/icon/comments', '<svg class="bs-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" aria-hidden="true"><path d="M123.6 391.3c12.9-9.4 29.6-11.8 44.6-6.4c26.5 9.6 56.2 15.1 87.8 15.1c124.7 0 208-80.5 208-160s-83.3-160-208-160S48 160.5 48 240c0 32 12.4 62.8 35.7 89.2c8.6 9.7 12.8 22.5 11.8 35.5c-1.4 18.1-5.7 34.7-11.3 49.4c17-7.9 31.1-16.7 39.4-22.7zM21.2 431.9c1.8-2.7 3.5-5.4 5.1-8.1c10-16.6 19.5-38.4 21.4-62.9C17.7 326.8 0 285.1 0 240C0 125.1 114.6 32 256 32s256 93.1 256 208s-114.6 208-256 208c-37.1 0-72.3-6.4-104.1-17.9c-11.9 8.7-31.3 20.6-54.3 30.6c-15.1 6.6-32.3 12.6-50.1 16.1c-.8 .2-1.6 .3-2.4 .5c-4.4 .8-8.7 1.5-13.2 1.9c-.2 0-.5 .1-.7 .1c-5.1 .5-10.2 .8-15.3 .8c-6.5 0-12.3-3.9-14.8-9.9c-2.5-6-1.1-12.8 3.4-17.4c4.1-4.2 7.8-8.7 11.3-13.5c1.7-2.3 3.3-4.6 4.8-6.9l.3-.5z"/></svg>'), bootscore_kses_allowed_svg( wp_kses_allowed_html( 'post' ) ) ) . ' <span class="comments-link">';
      /* translators: %s: Name of current post. Only visible to screen readers. */
      // comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'bootscore' ), get_the_title() ) );
      comments_popup_link(sprintf(__('Leave a comment', 'bootscore'), get_the_title()));
      echo '</span>';
    }
  }
endif;


/**
 * Tags
 */
if (!function_exists('bootscore_tags')) :
  /**
   * Prints HTML with meta information for the tags.
   */
  function bootscore_tags() {
    // Hide category and tag text for pages.
    if ('post' === get_post_type()) {

      $tags_list = get_the_tag_list('', ' ');
      if ($tags_list) {
        echo '<div class="tags-links">';

        // Show 'Tagged' heading only on singular post pages
        if (is_singular('post') && get_the_ID() === get_queried_object_id()) {
          echo '<p class="tags-heading h6">' . esc_html__('Tagged', 'bootscore') . '</p>';
        }

        echo get_the_tag_list();
        echo '</div>';
      }
    }
  }

  add_filter("term_links-post_tag", 'add_tag_class');

  function add_tag_class($links) {
    $class = apply_filters('bootscore/class/badge/tag', 'badge bg-primary-subtle text-primary-emphasis text-decoration-none');

    // Check if icon should be shown
    if (apply_filters('bootscore/show/tag/icon', true)) {
      $icon = wp_kses( apply_filters('bootscore/icon/tag', '<svg class="bs-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" aria-hidden="true"><path d="M0 80L0 229.5c0 17 6.7 33.3 18.7 45.3l176 176c25 25 65.5 25 90.5 0L418.7 317.3c25-25 25-65.5 0-90.5l-176-176c-12-12-28.3-18.7-45.3-18.7L48 32C21.5 32 0 53.5 0 80zm112 32a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>'), bootscore_kses_allowed_svg( wp_kses_allowed_html( 'post' ) ) ) . ' ';
    } else {
      $icon = '';
    }

    return str_replace(
      ['<a href="', '">', '</a>'],
      ['<a class="' . esc_attr($class) . '" href="', '">' . $icon, '</a> '],
      $links
    );
  }
endif;


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

