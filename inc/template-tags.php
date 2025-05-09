<?php

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Bootscore
 * @version 6.2.0
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
        $thelist .= '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="' . esc_attr($class) . '">' . $category->name . '</a>';
        $i ++;
      }
      echo $thelist;
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
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time> <span class="time-updated-separator">/</span> <time class="updated" datetime="%3$s">%4$s</time>';
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
      echo ' <span class="comment-divider">|</span> ' . apply_filters('bootscore/icon/comments', '<i class="fa-regular fa-comments"></i>') . ' <span class="comments-link">';
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
      echo ' <span class="comment-divider">|</span> ' . apply_filters('bootscore/icon/comments', '<i class="fa-regular fa-comments"></i>') . ' <span class="comments-link">';

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
   * Prints HTML with meta information for the categories, tags, and comments.
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
    return str_replace('</a>', '</a> ', str_replace('<a href="', '<a class="' . $class . '" href="', $links));
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
