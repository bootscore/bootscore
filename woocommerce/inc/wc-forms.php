<?php

/**
 * WooCommerce Forms
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Remove CSS and/or JS for Select2 used by WooCommerce
 * https://gist.github.com/Willem-Siebe/c6d798ccba249d5bf080.
 */
add_action('wp_enqueue_scripts', 'bootscore_dequeue_stylesandscripts_select2', 100);

function bootscore_dequeue_stylesandscripts_select2() {
  if (class_exists('woocommerce')) {
    wp_dequeue_style('selectWoo');
    wp_deregister_style('selectWoo');

    wp_dequeue_script('selectWoo');
    wp_deregister_script('selectWoo');
  }
}


/**
 * Checkout form fields
 */
function bootscore_wc_bootstrap_form_field_args($args, $key, $value) {
  $args['input_class'][] = 'form-control';
  return $args;
}
add_filter('woocommerce_form_field_args', 'bootscore_wc_bootstrap_form_field_args', 10, 3);


/**
 * Quantity input
 */
function bootscore_quantity_input_classes( $classes ) {
  $classes[] = 'form-control';
  return $classes;
}
add_filter( 'woocommerce_quantity_input_classes', 'bootscore_quantity_input_classes' );






// Reviews


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

