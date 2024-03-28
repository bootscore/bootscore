<?php

/**
 * WooCommerce Redirects
 *
 * @package Bootscore 
 * @version 5.3.3
 */


// Exit if accessed directly
defined('ABSPATH') || exit;
 
 
/**
 * Redirect to my-account if offcanvas login failed
 */
add_action('woocommerce_login_failed', 'bootscore_redirect_on_login_failed', 10, 0);
function bootscore_redirect_on_login_failed() {
  // Logout user doesn't have session, we need this to display notices
  if (!WC()->session->has_session()) {
    WC()->session->set_customer_session_cookie(true);
  }
  wp_redirect(wp_validate_redirect(wc_get_page_permalink('myaccount')));
  exit;
}


/**
 * Redirect to home on logout
 */
add_action('wp_logout', 'bootscore_redirect_after_logout');
function bootscore_redirect_after_logout() {
  wp_redirect(home_url());
  exit();
}


/**
 * Redirect to my-account after (un)sucessful registration
 */
add_action('wp_loaded', 'bootscore_redirect_after_registration', 999);
function bootscore_redirect_after_registration() {
  $nonce_value = isset($_POST['_wpnonce']) ? wp_unslash($_POST['_wpnonce']) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
  $nonce_value = isset($_POST['woocommerce-register-nonce']) ? wp_unslash($_POST['woocommerce-register-nonce']) : $nonce_value; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
  if (isset($_POST['register'], $_POST['email']) && wp_verify_nonce($nonce_value, 'woocommerce-register')) {
    if (!WC()->session->has_session()) {
      WC()->session->set_customer_session_cookie(true);
    }
    wp_redirect(wp_validate_redirect(wc_get_page_permalink('myaccount')));
    exit;
  }
}
