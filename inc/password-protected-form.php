<?php

/**
 * Password protected form
 *
 * @package Bootscore
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Input group to password protected form
 */
if (!function_exists('bootscore_pw_form')) :
  function bootscore_pw_form() {
    $output = '
        <form action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post" class="input-group pw_form mb-4">' . "\n"
              . '<input name="post_password" type="password" size="" class="form-control" placeholder="' . __('Password', 'bootscore') . '"/>' . "\n"
              . '<input type="submit" class="btn btn-outline-primary input-group-text" name="Submit" value="' . __('Submit', 'bootscore') . '" />' . "\n"
              . '</form>' . "\n";

    return $output;
  }

  add_filter("the_password_form", "bootscore_pw_form");
endif;
