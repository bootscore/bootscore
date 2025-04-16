<?php

/**
 * Bootscore functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Bootscore
 * @version 6.1.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Update Checker
 * https://github.com/YahnisElsts/plugin-update-checker
 */
require 'inc/update/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/bootscore/bootscore/',
	__FILE__,
	'bootscore'
);

// Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');


/**
 * Load required files
 */
require_once('inc/theme-setup.php');             // Theme setup and custom theme supports
require_once('inc/breadcrumb.php');              // Breadcrumb
require_once('inc/columns.php');                 // Main/sidebar column width and breakpoints
require_once('inc/comments.php');                // Comments
require_once('inc/enable-html.php');             // Enable HTML in category and author description
require_once('inc/enqueue.php');                 // Enqueue scripts and styles
require_once('inc/excerpt.php');                 // Adds excerpt to pages
require_once('inc/fontawesome.php');             // Adds shortcode for inserting Font Awesome icons
require_once('inc/hooks.php');                   // Custom hooks
require_once('inc/navwalker.php');               // Register the Bootstrap 5 navwalker
require_once('inc/navmenu.php');                 // Register the nav menus
require_once('inc/pagination.php');              // Pagination for loop and single posts
require_once('inc/password-protected-form.php'); // Form if post or page is protected by password
require_once('inc/template-tags.php');           // Meta information like author, date, comments, category and tags badges
require_once('inc/template-functions.php');      // Functions which enhance the theme by hooking into WordPress
require_once('inc/widgets.php');                 // Register widget area and disables Gutenberg in widgets
require_once('inc/deprecated.php');              // Fallback functions being dropped in v6
require_once('inc/tinymce-editor.php');          // Fix body margin and font-family in backend if classic editor is used

// Blocks
// Widgets
require_once('inc/blocks/block-widget-archives.php');        // Archive block
require_once('inc/blocks/block-widget-calendar.php');        // Calendar block
require_once('inc/blocks/block-widget-categories.php');      // Categories block
require_once('inc/blocks/block-widget-latest-comments.php'); // Latest posts block
require_once('inc/blocks/block-widget-latest-posts.php');    // Latest posts block
require_once('inc/blocks/block-widget-search.php');          // Searchform block

// Contents
require_once('inc/blocks/block-quote.php'); // Quote block
require_once('inc/blocks/block-table.php'); // Table block


/**
 * Load WooCommerce scripts if plugin is activated
 */
if (class_exists('WooCommerce')) {
  require get_template_directory() . '/woocommerce/wc-functions.php';
}


/**
 * Load Jetpack compatibility file
 */
if (defined('JETPACK__VERSION')) {
  require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Handles AJAX request to load account menu HTML.
 */
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
function get_user_ip() {
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
    if ( empty ($user_ip = get_user_ip()) ) {
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
    $remember = filter_var(sanitize_text_field($_POST['rememberme'] ?? ''), FILTER_VALIDATE_BOOLEAN);

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
        '<strong>' . __( 'Success:', 'bootscore' ) . '</strong>' . __( 'Welcome:', 'bootscore' ) . '<strong> %s</strong>!',
        esc_html($username)
    );
    $response['isLoggedIn'] = true;
    // If everything is ok till here the user logged in successfully
    wp_send_json_success($response, 200);
}
