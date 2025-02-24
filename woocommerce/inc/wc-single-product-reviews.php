<?php

/**
 * WooCommerce Single Product Reviews
 *
 * @package Bootscore 
 * @version 6.1.0
 */


 // Exit if accessed directly
 defined('ABSPATH') || exit;


/**
 * Replace <div id="comments"> with <div id="woo-comments"> in WooCommerce reviews.
 */
add_action('woocommerce_after_single_product_summary', function () {
  // Start output buffering.
  ob_start();
}, 1);

add_action('woocommerce_after_single_product_summary', function () {
  // Get the buffered content.
  $output = ob_get_clean();

  // Replace <div id="comments"> with <div id="woo-comments">.
  $output = str_replace('<div id="comments"', '<div id="woo-comments"', $output);

  // Output the modified content.
  echo $output;
}, 100);


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
 * Replace <ol class="commentlist"> with <ul class="comment-list"> in WooCommerce reviews.
 */
add_action('woocommerce_after_single_product_summary', function () {
  // Start output buffering.
  ob_start();
}, 1);

add_action('woocommerce_after_single_product_summary', function () {
  // Get the buffered content.
  $output = ob_get_clean();

  // Replace <ol class="commentlist"> with <ul class="comment-list">.
  $output = str_replace('<ol class="commentlist">', '<ul class="comment-list">', $output);

  // Replace the closing </ol> tag with </ul>.
  $output = str_replace('</ol>', '</ul>', $output);

  // Output the modified content.
  echo $output;
}, 100);


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


/**
 * Add 'woocommerce-info' class to the verification required message for WooCommerce reviews.
 */
add_action('woocommerce_after_single_product_summary', function () {
  // Start output buffering.
  ob_start();
}, 1);

add_action('woocommerce_after_single_product_summary', function () {
  // Get the buffered content.
  $output = ob_get_clean();

  // Replace the verification required paragraph with the desired class.
  $output = str_replace(
    '<p class="woocommerce-verification-required">',
    '<p class="woocommerce-verification-required woocommerce-info">',
    $output
  );

  // Output the modified content.
  echo $output;
}, 100);
