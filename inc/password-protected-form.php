<?php

/**
 * Password protected form
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Input group to password protected form
 */
if (!function_exists('bootscore_pw_form')) :
  function bootscore_pw_form() {
    $output = '<form action="' . esc_url(site_url('wp-login.php?action=postpass', 'login_post')) . '" method="post" class="input-group pw_form mb-7">'
      . '<input name="post_password" type="password" class="form-control" placeholder="' . esc_attr__('Password', 'bootscore') . '" aria-label="' . esc_attr__('Password', 'bootscore') . '">'
      . '<input type="submit" class="btn-solid theme-primary input-group-btn" name="Submit" value="' . esc_attr__('Submit', 'bootscore') . '">'
      . '</form>';

    return $output;
  }

  add_filter('the_password_form', 'bootscore_pw_form');
endif;
