<?php

/**
 * Template part for displaying the offcanvas user and cart if WooCommerce is installed
 * Template Version: 6.3.1
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<!-- Offcanvas user -->
<?php
if (apply_filters('bootscore/enable_account', true)) {
  if ( is_account_page() || is_checkout() ) {
  // Do nothing
  } else { ?>
  <div class="offcanvas offcanvas-<?= esc_attr(apply_filters('bootscore/class/header/offcanvas/direction', 'end', 'account')); ?>" tabindex="-1" id="offcanvas-user">
    <div class="offcanvas-header <?= esc_attr(apply_filters('bootscore/class/offcanvas/header', '', 'account')); ?>">
      <span class="h5 offcanvas-title"><?= esc_html(apply_filters('bootscore/offcanvas/account/title', __('Account', 'bootscore'))); ?></span>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e( 'Close', 'bootscore' ); ?>"></button>
    </div>
    <div class="offcanvas-body position-relative <?= esc_attr(apply_filters('bootscore/class/offcanvas/body', '', 'account')); ?>">
      <?php do_action( 'bootscore_before_my_offcanvas_account' ); ?>
      <div class="my-offcanvas-account">
        <?php if (! apply_filters('bootscore/wc_ajax_login', true)) : ?>
          <?= do_shortcode('[woocommerce_my_account]'); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php }
  }
?>

<!-- Offcanvas cart -->
<?php
if ( is_checkout() || is_cart() ) {
 // Do nothing
} else { ?>
  <div class="offcanvas offcanvas-<?= esc_attr(apply_filters('bootscore/class/header/offcanvas/direction', 'end', 'cart')); ?>" tabindex="-1" id="offcanvas-cart">
    <div class="offcanvas-header <?= esc_attr(apply_filters('bootscore/class/offcanvas/header', '', 'cart')); ?>">
      <span class="h5 offcanvas-title"><?= esc_html(apply_filters('bootscore/offcanvas/cart/title', __('Cart', 'bootscore'))); ?></span>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0 <?= esc_attr(apply_filters('bootscore/class/offcanvas/body', '', 'cart')); ?>">
      <div class="cart-list">
        <div class="widget_shopping_cart_content"></div>
      </div>
    </div>
  </div>
<?php } ?>
