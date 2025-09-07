<?php

/**
 * WooCommerce Mini Cart
 *
 * @package Bootscore 
 * @version 6.1.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Mini cart Header
 */
if (!function_exists('bootscore_mini_cart')) :
  function bootscore_mini_cart($fragments) {

    ob_start();
    $count = WC()->cart->cart_contents_count; ?>
    <span class="cart-content">
      <?php if ($count > 0) { ?>
        <?php if(apply_filters('bootscore/woocommerce/header/show_cart_total', true)): ?>
          <span class="cart-content-count <?= apply_filters('bootscore/class/header/cart/badge', 'position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-danger'); ?>"><?= esc_html($count); ?></span><span class="cart-total <?= apply_filters('bootscore/class/header/cart/total', 'ms-2 d-none d-md-inline'); ?>"><?= WC()->cart->get_cart_subtotal(); ?></span>
        <?php else: ?>
          <span class="cart-content-count <?= apply_filters('bootscore/class/header/cart/badge', 'position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-danger'); ?>"><?= esc_html($count); ?></span>
        <?php endif; ?>
      <?php } ?>
    </span>

    <?php
    $fragments['span.cart-content'] = ob_get_clean();

    return $fragments;
  }

  add_filter('woocommerce_add_to_cart_fragments', 'bootscore_mini_cart');

endif;