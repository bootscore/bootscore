<?php

/**
 * WooCommerce Redirects
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Start a session for logged out visitors, so WooCommerce notices survive the redirect
 */
function bootscore_wc_redirect_session() {
  if (isset(WC()->session) && !WC()->session->has_session()) {
    WC()->session->set_customer_session_cookie(true);
  }
}


/**
 * Failed login → my account
 *
 * The login form in the account drawer posts to the current page (when AJAX login is off via
 * bootscore/wc_ajax_login), the error notice would be hidden in the closed drawer.
 */
function bootscore_wc_redirect_on_login_failed() {
  bootscore_wc_redirect_session();
  wp_safe_redirect(wc_get_page_permalink('myaccount'));
  exit;
}
add_action('woocommerce_login_failed', 'bootscore_wc_redirect_on_login_failed', 10, 0);


/**
 * Failed registration → my account
 *
 * The register form in the account drawer posts to the current page. On success WooCommerce redirects
 * itself (wp_loaded, priority 20), so this only runs when the registration failed.
 */
function bootscore_wc_redirect_after_registration() {
  $nonce_value = isset($_POST['_wpnonce']) ? wp_unslash($_POST['_wpnonce']) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
  $nonce_value = isset($_POST['woocommerce-register-nonce']) ? wp_unslash($_POST['woocommerce-register-nonce']) : $nonce_value; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

  if (isset($_POST['register'], $_POST['email']) && wp_verify_nonce($nonce_value, 'woocommerce-register')) {
    bootscore_wc_redirect_session();
    wp_safe_redirect(wc_get_page_permalink('myaccount'));
    exit;
  }
}
add_action('wp_loaded', 'bootscore_wc_redirect_after_registration', 999);


/**
 * Logout from my account → home
 *
 * Only WooCommerce's logout (account navigation, dashboard link), logging out of wp-admin is not touched.
 * Change the URL via filter.
 */
function bootscore_wc_logout_redirect_url() {
  return apply_filters('bootscore/woocommerce/logout-redirect', home_url('/'));
}
add_filter('woocommerce_logout_default_redirect_url', 'bootscore_wc_logout_redirect_url');
