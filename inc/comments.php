<?php

/**
 * Comments
 *
 * @package Bootscore 
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Comment walker with filterable class on the replies list
 */
if (!class_exists('Bootscore_Walker_Comment')) :
  class Bootscore_Walker_Comment extends Walker_Comment {

    public function start_lvl(&$output, $depth = 0, $args = array()) {
      $GLOBALS['comment_depth'] = $depth + 1;

      if ('div' === $args['style']) {
        return;
      }

      $tag   = ('ol' === $args['style']) ? 'ol' : 'ul';
      $class = trim('children ' . apply_filters('bootscore/class/comment/children', 'list-unstyled ms-12', $depth));

      $output .= '<' . $tag . ' class="' . esc_attr($class) . '">' . "\n";
    }
  }
endif;


/**
 * Comments
 */
if (!function_exists('bootscore_comment')) :
  /**
   * Template for comments and pingbacks.
   *
   * Used as a callback by wp_list_comments() for displaying the comments.
   * The <li> is left open on purpose: WordPress outputs the replies list
   * and then closes the <li> itself in Walker_Comment::end_el().
   */
  function bootscore_comment($comment, $args, $depth) {

    if ('pingback' == $comment->comment_type || 'trackback' == $comment->comment_type) : ?>

      <li id="comment-<?php comment_ID(); ?>" <?php comment_class('media alert theme-info'); ?>>
      <div class="comment-body">
        <?php esc_html_e('Pingback:', 'bootscore'); ?><?php comment_author_link(); ?><?php edit_comment_link(__('Edit', 'bootscore'), '<span class="edit-link">', '</span>'); ?>
      </div>

    <?php else : ?>

      <li id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?>>

        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body d-flex mb-5">

          <span class="avatar flex-shrink-0 me-3">
            <?php echo get_avatar($comment, 80, '', '', array('class' => esc_attr(apply_filters('bootscore/class/comment/avatar', 'avatar-img')))); ?> 
          </span>

          <div class="comment-content card flex-grow-1">
            <div class="card-body">

              <?php printf('<h3 class="h5 card-text">%s</h3>', get_comment_author_link()); ?>

              <p class="comment-meta card-subtitle fg-secondary fs-sm">
                <time datetime="<?php comment_time('c'); ?>">
                  <?php printf(_x('%1$s at %2$s', '1: date, 2: time', 'bootscore'), get_comment_date(), get_comment_time()); ?>
                </time>
                <?php edit_comment_link(__('Edit', 'bootscore'), '<span class="edit-link">', '</span>'); ?>
              </p>

              <?php if ('0' == $comment->comment_approved) : ?>
                <p class="comment-awaiting-moderation alert theme-info"><?= bootscore_alert_content(esc_html__('Your comment is awaiting moderation.', 'bootscore')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
              <?php endif; ?>

              <?php comment_text(); ?>

              <?php comment_reply_link(
                array_merge(
                  $args,
                  array(
                    'add_below' => 'div-comment',
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<p class="reply comment-reply">',
                    'after'     => '</p>'
                  )
                )
              ); ?>
            </div> <!-- card-body -->
          </div><!-- card -->

        </article><!-- .comment-body -->

    <?php
    endif;
  }
endif;


/**
 * h2 Reply Title
 */
add_filter('comment_form_defaults', 'bootscore_comment_reply_title');
function bootscore_comment_reply_title($defaults) {
  $defaults['title_reply_before'] = '<h2 id="reply-title" class="h4">';
  $defaults['title_reply_after']  = '</h2>';

  return $defaults;
}


/**
 * Comment Cookie Checkbox
 * See https://github.com/bootscore/bootscore/issues/921
 */
function bootscore_change_comment_form_cookies_consent($fields) {
  // Check if the "Show comments cookies opt-in checkbox" setting is enabled
  if (get_option('show_comments_cookies_opt_in')) {
    $commenter         = wp_get_current_commenter();
    $consent           = empty($commenter['comment_author_email']) ? '' : ' checked="checked"';
    $fields['cookies'] = '<p class="comment-form-cookies-consent form-field">' .
                         '<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" class="check"' . $consent . ' />' .
                         '<label for="wp-comment-cookies-consent">' . esc_html__('Save my name, email, and website in this browser for the next time I comment.', 'bootscore') . '</label>' .
                         '</p>';
  } else {
    // Remove the 'cookies' field if the setting is disabled
    unset($fields['cookies']);
  }

  return $fields;
}
add_filter('comment_form_default_fields', 'bootscore_change_comment_form_cookies_consent');


/**
 * Open links in comments in new tab
 *
 * WordPress already adds rel="nofollow ugc" to these links, so only target and noopener are added.
 */
if (!function_exists('bootscore_comment_links_new_tab')) :
  function bootscore_comment_links_new_tab($html) {
    $tags = new WP_HTML_Tag_Processor($html);

    while ($tags->next_tag('a')) {
      $tags->set_attribute('target', '_blank');
      $rel = trim((string) $tags->get_attribute('rel'));
      if (!preg_match('/\bnoopener\b/', $rel)) {
        $tags->set_attribute('rel', trim($rel . ' noopener'));
      }
    }

    return $tags->get_updated_html();
  }

  add_filter('comment_text', 'bootscore_comment_links_new_tab');
  add_filter('get_comment_author_link', 'bootscore_comment_links_new_tab');
endif;


/**
 * Cancel reply link class
 */
add_filter('cancel_comment_reply_link', function ($link_html) {
  $class = apply_filters('bootscore/class/comment/cancel-reply-link', 'btn-outline theme-danger btn-xs ms-3');

  return str_replace('<a ', '<a class="' . esc_attr($class) . '" ', $link_html);
});


/**
 * Comment Button
 */
if (!function_exists('bootscore_comment_button')) :
  function bootscore_comment_button($args) {
    $args['class_submit'] = apply_filters('bootscore/class/comment/submit', 'btn-solid theme-primary');

    return $args;
  }

  add_filter('comment_form_defaults', 'bootscore_comment_button');
endif;
