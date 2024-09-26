<?php

/**
 * WooCommerce Breadcrumb
 *
 * @package Bootscore 
 * @version 6.1.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * WooCommerce Breadcrumb
 */
if (!function_exists('bs_woocommerce_breadcrumbs')) :
  add_filter('woocommerce_breadcrumb_defaults', 'bs_woocommerce_breadcrumbs');
  function bs_woocommerce_breadcrumbs() {
    return array(
      'delimiter'   => '',
      'wrap_before' => "<nav aria-label='breadcrumb' class='" . apply_filters('bootscore/class/breadcrumb/nav', 'overflow-x-auto text-nowrap mb-4 mt-2 py-2 px-3 bg-body-tertiary rounded') . "'>
      <ol class='breadcrumb " . apply_filters('bootscore/class/breadcrumb/ol', 'flex-nowrap mb-0') . "'>",
      'wrap_after'  => '</ol>
      </nav>',
      'before'      => '<li class="breadcrumb-item">',
      'after'       => '</li>',
      'home'        => _x('Home', 'breadcrumb', 'woocommerce'),
    );
  }
endif;
