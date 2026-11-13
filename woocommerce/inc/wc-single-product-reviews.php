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
      ['<p class="form-field comment-form-', '<div class="form-field mb-5 comment-form-', '<label class="form-label" ', '<input class="form-control" ', '<select class="form-control" ', '<textarea class="form-control" '],
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
