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
 * Replace <div id="comments"> with <div id="woo-comments"> in WooCommerce reviews.
 */
add_action('woocommerce_after_single_product_summary', function () {
  ob_start();
}, 1);

add_action('woocommerce_after_single_product_summary', function () {
  $output = ob_get_clean();
  $output = str_replace('<div id="comments"', '<div id="woo-comments"', $output);
  echo $output;
}, 100);


/**
 * Change reply title to h3 in WooCommerce reviews
 */
add_filter('woocommerce_product_review_comment_form_args', function ($args) {
  if (!empty($args['title_reply'])) {
    $args['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title">';
    $args['title_reply_after']  = '</h3>';
  }
  return $args;
});


/**
 * Replace <ol class="commentlist"> with <ul class="comment-list"> in WooCommerce reviews.
 */
add_action('woocommerce_after_single_product_summary', function () {
  ob_start();
}, 1);

add_action('woocommerce_after_single_product_summary', function () {
  $output = ob_get_clean();
  $output = str_replace('<ol class="commentlist">', '<ul class="comment-list">', $output);
  $output = str_replace('</ol>', '</ul>', $output);
  echo $output;
}, 100);


/**
 * Rating dropdown (Block themes)
 */
add_filter('woocommerce_product_review_comment_form_args', function ($comment_form) {
  if (isset($comment_form['comment_field'])) {
    $comment_form['comment_field'] = str_replace(
      '<select',
      '<select class="form-select mb-3"',
      $comment_form['comment_field']
    );
  }
  return $comment_form;
});


/**
 * Reviews text area
 */
add_filter('comment_form_field_comment', function ($field) {
  $field = preg_replace('/<label[^>]*for="comment"[^>]*>.*?<\/label>/i', '', $field);
  $field = str_replace(
    '<textarea',
    '<textarea class="form-control" placeholder="' . __('Your review...*', 'bootscore') . '"',
    $field
  );
  return $field;
});


/**
 * Review name and email form fields
 */
add_filter('woocommerce_product_review_comment_form_args', function ($comment_form) {
  foreach (['author', 'email'] as $field) {
    if (isset($comment_form['fields'][$field])) {
      $comment_form['fields'][$field] = str_replace(
        '<input',
        '<input class="form-control"',
        $comment_form['fields'][$field]
      );
    }
  }
  return $comment_form;
});


/**
 * Add 'woocommerce-info' class to the verification required message for WooCommerce reviews.
 */
add_action('woocommerce_after_single_product_summary', function () {
  ob_start();
}, 1);

add_action('woocommerce_after_single_product_summary', function () {
  $output = ob_get_clean();
  $output = str_replace(
    '<p class="woocommerce-verification-required">',
    '<p class="woocommerce-verification-required woocommerce-info">',
    $output
  );
  echo $output;
}, 100);
