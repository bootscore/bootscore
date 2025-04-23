<?php

/**
 * WooCommerce AJAX login
 *
 * @package Bootscore 
 * @version 6.2.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


add_action('wp_ajax_load_account_menu_html', 'load_account_menu_html');
add_action('wp_ajax_nopriv_load_account_menu_html', 'load_account_menu_html');
function load_account_menu_html() {
  $response = [];
  $response['menu_html'] = do_shortcode('[woocommerce_my_account]');
  wp_send_json_success($response);
}

/**
 * Detect and return the client's IP address.
 *
 * This function checks various server variables to determine the correct client IP,
 * handling cases where proxies or other intermediate systems may alter the apparent IP.
 *
 * @return string|null The IP address of the client, or null if it cannot be determined.
 */
function bootscore_get_user_ip() {
  global $user_ip;

  if (!is_null($user_ip)) { // We've already discovered the browser's IP address.
    return $user_ip;
  }

  if ( !empty($_SERVER['HTTP_CLIENT_IP']) ) {
    $user_ip = filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP);
  } elseif ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
    $user_ip = filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP);
  } else {
    $user_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
  }
  return $user_ip;
}


/**
 * Handles AJAX login for non-logged-in users.
 */
add_action('wp_ajax_nopriv_ajax_login', 'ajax_login');
function ajax_login() {
  // Check if there is no IP address, just die
  if ( empty ($user_ip = bootscore_get_user_ip()) ) {
    die();
  }
  $user_key = 'login_attempt_' . $user_ip;
  // If there are too little time between logins, output: 'Slow down a bit.'
  if ( get_transient($user_key) ) {
    $response['message'] = __('Slow down a bit.', 'bootscore');
    wp_send_json_error($response, 400);
  } else {
      set_transient($user_key, '1', 3);
  }

  $response = [
    'isLoggedIn' => false,
    'errorMessage' => ''
  ];

  $username = sanitize_text_field($_POST['username'] ?? '');
  $password = sanitize_text_field($_POST['password'] ?? '');


  // If the nonce is not the right one, output: 'Login form expired, please refresh page.'
  if (!isset($_POST['woocommerceLoginNonce']) || !wp_verify_nonce($_POST['woocommerceLoginNonce'], 'woocommerce-login') ) {
    $response['message'] =  '<strong>' . __( 'Error:', 'woocommerce' ) . '</strong> ' . __( 'Login form expired, please refresh page.', 'bootscore' );
    wp_send_json_error($response, 400);
  }

  // Standard Error Handling in Frontend, this is just for bots
  if ( empty($username)|| empty($password) ) {
    wp_send_json_error($response, 400);
  }


  $user = wp_authenticate($username, $password);
  $remember = filter_var(sanitize_text_field($_POST['rememberMe'] ?? ''), FILTER_VALIDATE_BOOLEAN);

  // If the user do not exist, output: 'This combination of access data was not found in our database.'
  if ( !is_a($user, 'WP_User') ) {
    $response['message'] = '<strong>' . __( 'Error:', 'woocommerce' ) . '</strong> ' . __( 'This combination of access data was not found in our database.', 'bootscore' );
    wp_send_json_error($response, 401);
  }

  wp_set_current_user($user->ID);
  // If the user click "Remember me" -> remembered for 14 days
  wp_set_auth_cookie($user->ID, $remember);
  do_action('wp_login', $user->user_login, $user);

  $response['message'] = sprintf(
    __( 'Welcome', 'bootscore' ) . '<strong> %s</strong>!',
    esc_html($username)
  );
  $response['isLoggedIn'] = true;
  // If everything is ok till here the user logged in successfully
  wp_send_json_success($response, 200);
}


/**
 * Add a loader to offcanvas login
 */
function bootscore_add_loader_to_login() {
  echo '<div class="ajax-login-loader position-absolute top-0 end-0 bottom-0 start-0 z-1 align-items-center justify-content-center bg-body">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>';
}
add_action('bootscore_before_my_offcanvas_account', 'bootscore_add_loader_to_login');
