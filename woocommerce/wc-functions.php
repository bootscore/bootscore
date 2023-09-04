<?php

/**
 * WooCommerce functions and definitions
 *
 * @package Bootscore
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Load required files
 */
require_once('inc/wc-breadcrumb.php');
require_once('inc/wc-enqueue.php');
require_once('inc/wc-forms.php');
require_once('inc/wc-loop.php');
require_once('inc/wc-mini-cart.php');
require_once('inc/wc-qty-btn.php'); 
require_once('inc/wc-redirects.php'); 
require_once('inc/wc-setup.php'); 
require_once('inc/wc-deprecated.php'); 


/**
 * Register Ajax Cart
 * Allow users to edit/disable entire AJAX functionality in child theme
 */
if (!function_exists('register_ajax_cart')) :

  function register_ajax_cart() {
    require_once('inc/ajax-add-to-cart.php');
  }

  add_action('after_setup_theme', 'register_ajax_cart');

endif;
