<?php

/**
 * Template part for displaying the offcanvas user and cart if WooCommerce is installed
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<!-- Offcanvas user -->
<?php
if ( is_account_page() || is_checkout() ) {
 // Do nothing
} else { ?>
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-user">
  <div class="offcanvas-header">
    <span class="h5 offcanvas-title"><?= apply_filters('bootscore/offcanvas/user/title', __('Account', 'bootscore')); ?></span>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="my-offcanvas-account">
      <?= do_shortcode('[woocommerce_my_account]'); ?>
    </div>
  </div>
</div>
<?php } ?>

<!-- Offcanvas cart -->
<?php
if ( is_checkout() || is_cart() ) {
 // Do nothing
} else { ?>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-cart">
    <div class="offcanvas-header">
      <span class="h5 offcanvas-title"><?= apply_filters('bootscore/offcanvas/cart/title', __('Cart', 'bootscore')); ?></span>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
      <div class="cart-list">
        <div class="widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div>
      </div>
    </div>
  </div>
<?php } ?>