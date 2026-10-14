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
  function bootscore_category_badge($context = '') {

    // Hide category badges for pages and other post types
    if ('post' !== get_post_type()) {
      return;
    }

    $categories = get_the_category();
    if (empty($categories)) {
      return;
    }

    $class = apply_filters('bootscore/class/badge/category', 'badge', $context);

    echo '<p class="' . esc_attr(apply_filters('bootscore/class/badge/category/wrapper', 'category-badge d-flex flex-wrap gap-1', $context)) . '">';

    foreach ($categories as $category) {
      // z-2 keeps each badge clickable above a card's stretched link
      echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="' . esc_attr($class . ' z-2') . '">' . esc_html($category->name) . '</a>';
    }

    echo '</p>';
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
        $icon = bootscore_icon('arrow-rotate', false);

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
      echo ' <span class="comment-divider">|</span> ' . bootscore_icon('comments', false) . ' <span class="comments-link">';
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
      echo ' <span class="comment-divider">|</span> ' . bootscore_icon('comments', false) . ' <span class="comments-link">';
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
  function bootscore_tags($context = '') {

    // Hide tag badges for pages and other post types
    if ('post' !== get_post_type()) {
      return;
    }

    $tags = get_the_tags();
    if (empty($tags) || is_wp_error($tags)) {
      return;
    }

    // Show 'Tagged' heading only on the single post itself
    if (is_singular('post') && get_the_ID() === get_queried_object_id()) {
      echo '<p class="tags-heading h5">' . esc_html__('Tagged', 'bootscore') . '</p>';
    }

    $class = apply_filters('bootscore/class/badge/tag', 'badge', $context);
    $icon  = apply_filters('bootscore/show/tag/icon', true) ? bootscore_icon('tag', false) . ' ' : '';

    echo '<p class="' . esc_attr(apply_filters('bootscore/class/badge/tag/wrapper', 'tags-badge d-flex flex-wrap gap-1', $context)) . '">';

    foreach ($tags as $tag) {
      // z-2 keeps each badge clickable above a card's stretched link
      echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="' . esc_attr($class . ' z-2') . '" rel="tag">' . $icon . esc_html($tag->name) . '</a>';
    }

    echo '</p>';
  }
endif;
