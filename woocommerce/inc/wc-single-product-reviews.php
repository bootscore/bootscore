<?php

/**
 * WooCommerce single product reviews
 *
 * Replaces the single-product/review.php override. Each review uses the pluggable woocommerce_comments()
 * with the markup of the blog comments (inc/comments.php) and fires all WooCommerce review hooks.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Review, closing li is left out on purpose (wp_list_comments adds it)
 */
if (!function_exists('woocommerce_comments')) :
  function woocommerce_comments($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

      <article id="comment-<?php comment_ID(); ?>" class="comment_container comment-body d-flex mb-5">

        <?php
        /**
         * @hooked woocommerce_review_display_gravatar - 10
         */
        do_action('woocommerce_review_before', $comment);
        ?>

        <div class="comment-text comment-content <?= esc_attr(apply_filters('bootscore/class/woocommerce/review/card', 'card flex-grow-1')); ?>">
          <div class="card-body">
            <?php
            /**
             * @hooked woocommerce_review_display_rating - 10
             */
            do_action('woocommerce_review_before_comment_meta', $comment);

            /**
             * @hooked woocommerce_review_display_meta - 10
             */
            do_action('woocommerce_review_meta', $comment);

            do_action('woocommerce_review_before_comment_text', $comment);

            /**
             * @hooked woocommerce_review_display_comment_text - 10
             */
            do_action('woocommerce_review_comment_text', $comment);

            do_action('woocommerce_review_after_comment_text', $comment);
            ?>
          </div>
        </div>

      </article>
    <?php
  }
endif;


/**
 * Avatar like in the blog comments
 */
if (!function_exists('woocommerce_review_display_gravatar')) :
  function woocommerce_review_display_gravatar($comment) {
    echo '<span class="avatar me-3">' . get_avatar($comment, apply_filters('woocommerce_review_gravatar_size', 80), '', '', ['class' => esc_attr(apply_filters('bootscore/class/comment/avatar', 'avatar-img'))]) . '</span>';
  }
endif;


/**
 * Author, date and approval note like in the blog comments (bootscore_comment() in inc/comments.php)
 */
if (!function_exists('woocommerce_review_display_meta')) :
  function woocommerce_review_display_meta() {
    global $comment;

    $verified = 'yes' === get_option('woocommerce_review_rating_verification_label') && wc_review_is_from_verified_owner($comment->comment_ID);
    ?>
    <h3 class="woocommerce-review__author h5 card-text">
      <?php comment_author(); ?>
      <?php if ($verified) : ?>
        <span class="woocommerce-review__verified verified <?= esc_attr(apply_filters('bootscore/class/woocommerce/review/verified', 'badge badge-subtle theme-success align-middle')); ?>"><?php esc_html_e('verified owner', 'woocommerce'); ?></span>
      <?php endif; ?>
    </h3>

    <p class="comment-meta card-subtitle fg-secondary fs-sm">
      <time class="woocommerce-review__published-date" datetime="<?php comment_time('c'); ?>">
        <?php printf(_x('%1$s at %2$s', '1: date, 2: time', 'bootscore'), get_comment_date(), get_comment_time()); ?>
      </time>
      <?php edit_comment_link(__('Edit', 'bootscore'), '<span class="edit-link">', '</span>'); ?>
    </p>

    <?php if ('0' === $comment->comment_approved) : ?>
      <p class="woocommerce-review__awaiting-approval <?= esc_attr(apply_filters('bootscore/class/woocommerce/review/awaiting-approval', 'alert theme-info')); ?>"><?php esc_html_e('Your review is awaiting approval', 'woocommerce'); ?></p>
    <?php endif;
  }
endif;


/**
 * Stars below the date instead of above the author, spacing from the .card-body gap
 */
if (!function_exists('woocommerce_review_display_rating')) :
  function woocommerce_review_display_rating() {
    global $comment;

    $rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));

    if (!post_type_supports('product', 'comments') || !$rating || !wc_review_ratings_enabled()) {
      return;
    }

    echo wc_get_rating_html($rating); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
  }
endif;

remove_action('woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 10);
add_action('woocommerce_review_before_comment_text', 'woocommerce_review_display_rating', 10);


/**
 * Review text without the div.description wrapper, so the paragraphs are .card-body children
 * and get its margin reset and gap like in the blog comments
 */
if (!function_exists('woocommerce_review_display_comment_text')) :
  function woocommerce_review_display_comment_text() {
    comment_text();
  }
endif;


/**
 * Replies to reviews with the theme comment walker, same ul.children as in the blog
 */
function bootscore_wc_review_list_args($args) {
  $args['walker'] = new Bootscore_Walker_Comment();

  return $args;
}
add_filter('woocommerce_product_review_list_args', 'bootscore_wc_review_list_args');


/**
 * Review form
 *
 * WooCommerce builds the fields as HTML strings, so the classes are added to these strings.
 * Only the review form is touched, the blog comment form is built in comments.php.
 */
function bootscore_wc_review_form_args($args) {
  $args['title_reply_before'] = '<h2 id="reply-title" class="comment-reply-title h4">';
  $args['title_reply_after']  = '</h2>';

  $add_classes = function ($html) {
    return str_replace(
      ['<p class="comment-form-', '<div class="comment-form-', '<label ', '<input ', '<select ', '<textarea '],
      ['<p class="form-field comment-form-', '<div class="form-field comment-form-', '<label class="form-label" ', '<input class="form-control" ', '<select class="form-control" ', '<textarea class="form-control" '],
      $html
    );
  };

  foreach (['author', 'email'] as $field) {
    if (isset($args['fields'][$field])) {
      $args['fields'][$field] = $add_classes($args['fields'][$field]);
    }
  }

  if (!empty($args['comment_field'])) {
    $args['comment_field'] = $add_classes($args['comment_field']);
  }

  return $args;
}
add_filter('woocommerce_product_review_comment_form_args', 'bootscore_wc_review_form_args');
