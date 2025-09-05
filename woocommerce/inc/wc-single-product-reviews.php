<?php

/**
 * WooCommerce Single Product Reviews
 *
 * @package Bootscore 
 * @version 6.3.1
 */


 // Exit if accessed directly
 defined('ABSPATH') || exit;


/**
 * Modify WooCommerce reviews markup in one place
 */
add_action('woocommerce_after_single_product_summary', function () {
  ob_start();
}, 1);

add_action('woocommerce_after_single_product_summary', function () {
  $output = ob_get_clean();

  if ($output) {
    // 1. Replace <div id="comments"> with <div id="woo-comments">
    $output = str_replace('<div id="comments"', '<div id="woo-comments"', $output);

    // 2. Replace <ol class="commentlist"> with <ul class="comment-list">
    $output = str_replace('<ol class="commentlist">', '<ul class="comment-list">', $output);
    $output = str_replace('</ol>', '</ul>', $output);

    // 3. Add 'woocommerce-info' class to the verification required message
    $output = str_replace(
      '<p class="woocommerce-verification-required">',
      '<p class="woocommerce-verification-required woocommerce-info">',
      $output
    );

    echo $output;
  }
}, 100);


/**
 * Customize WooCommerce review form fields
 */
add_filter('woocommerce_product_review_comment_form_args', function ($args) {

  // Change reply title to h3
  if (!empty($args['title_reply'])) {
    $args['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title">';
    $args['title_reply_after']  = '</h3>';
  }

  // Style name and email fields
  foreach (['author', 'email'] as $field) {
    if (isset($args['fields'][$field])) {
      // Add form-label class to the label
      $args['fields'][$field] = str_replace(
        '<label',
        '<label class="form-label"',
        $args['fields'][$field]
      );

      // Add form-control class to the input
      $args['fields'][$field] = str_replace(
        '<input',
        '<input class="form-control"',
        $args['fields'][$field]
      );
    }
  }

  // Style rating select (if ratings enabled)
  if (isset($args['comment_field'])) {
    $args['comment_field'] = str_replace(
      '<select',
      '<select class="form-select mb-3"',
      $args['comment_field']
    );

    $args['comment_field'] = str_replace(
      '<label',
      '<label class="form-label"',
      $args['comment_field']
    );
  }

  return $args;
});


/**
 * Customize WooCommerce review textarea
 */
add_filter('comment_form_field_comment', function ($field) {
  // Add form-label class to label
  $field = str_replace(
    '<label',
    '<label class="form-label"',
    $field
  );

  // Add Bootstrap form-control + placeholder to textarea
  $field = str_replace(
    '<textarea',
    '<textarea class="form-control" placeholder="' . esc_attr__('Your review...*', 'bootscore') . '"',
    $field
  );

  return $field;
});
