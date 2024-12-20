<?php

/**
 * WooCommerce Single Product Reviews
 *
 * @package Bootscore 
 * @version 6.0.4
 */


 // Exit if accessed directly
 defined('ABSPATH') || exit;


/**
 * Change the 'comments' div ID to 'woo-comments'
 */
add_filter('comments_template', function ($template) {
    // Include the default comments template, and capture the output.
    ob_start();
    include($template);
    $output = ob_get_clean();

    // Replace the 'comments' div ID with 'woo-comments'
    $output = preg_replace('/<div id="comments"/', '<div id="woo-comments"', $output, 1);

    // Return the modified output
    echo $output;
    return '';
}, 20);


/**
 * Change reply title to h3 in WooCommerce reviews
 */
add_filter('woocommerce_product_review_comment_form_args', function ($args) {
  if (!empty($args['title_reply'])) {
    // Replace the reply title span with h3
    $args['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title">';
    $args['title_reply_after'] = '</h3>';
  }

  return $args;
});


/**
 * Reviews text area
 */
add_filter('comment_form_field_comment', function ($field) {
  // Remove the label
  $field = preg_replace('/<label[^>]*for="comment"[^>]*>.*?<\/label>/i', '', $field);

  // Add the 'form-control' class and placeholder to the textarea
  $field = str_replace(
    '<textarea',
    '<textarea class="form-control" placeholder="' . __('Your review...*', 'bootscore') . '"',
    $field
  );

  return $field;
});
