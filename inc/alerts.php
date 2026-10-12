<?php

/**
 * Theme alerts
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Must log in notice
 */
add_filter('comment_form_defaults', function ($defaults) {
  $tags = new WP_HTML_Tag_Processor($defaults['must_log_in']);

  if ($tags->next_tag('p')) {
    $tags->add_class(apply_filters('bootscore/class/comment/must-log-in', 'alert theme-info d-block'));
  }

  $defaults['must_log_in'] = $tags->get_updated_html();

  return $defaults;
});
