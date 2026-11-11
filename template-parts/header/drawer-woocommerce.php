<?php

/**
 * Template part for displaying the drawer user and cart if WooCommerce is installed
 * Template Version: 7.0.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<!-- Drawer account -->
<?php if (apply_filters('bootscore/enable_account', true) && !is_account_page() && !is_checkout()) : ?>
  <dialog class="drawer drawer-<?= esc_attr(apply_filters('bootscore/class/drawer/direction', 'end', 'account')); ?>" tabindex="-1" id="drawer-account" aria-labelledby="drawer-account-label">
    <div class="drawer-header <?= esc_attr(apply_filters('bootscore/class/drawer/header', '', 'account')); ?>">
      <span class="h5 drawer-title" id="drawer-account-label"><?= esc_html(apply_filters('bootscore/header/account/drawer/title', __('Account', 'bootscore'))); ?></span>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="drawer" aria-label="<?php esc_attr_e('Close', 'bootscore'); ?>"></button>
    </div>
    <div class="drawer-body position-relative <?= esc_attr(apply_filters('bootscore/class/drawer/body', '', 'account')); ?>">
      <?php do_action('bootscore_before_drawer_account'); ?>
      <div class="drawer-account-content">
        <?php if (!apply_filters('bootscore/wc_ajax_login', true)) : ?>
          <?= do_shortcode('[woocommerce_my_account]'); ?>
        <?php endif; ?>
      </div>
    </div>
  </dialog>
<?php endif; ?>

<!-- Drawer cart -->
<?php if (!is_checkout() && !is_cart()) : ?>
  <dialog class="drawer drawer-<?= esc_attr(apply_filters('bootscore/class/drawer/direction', 'end', 'cart')); ?>" tabindex="-1" id="drawer-cart" aria-labelledby="drawer-cart-label">
    <div class="drawer-header <?= esc_attr(apply_filters('bootscore/class/drawer/header', '', 'cart')); ?>">
      <span class="h5 drawer-title" id="drawer-cart-label"><?= esc_html(apply_filters('bootscore/header/cart/drawer/title', __('Cart', 'bootscore'))); ?></span>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="drawer" aria-label="<?php esc_attr_e('Close', 'bootscore'); ?>"></button>
    </div>
    <div class="drawer-body p-0 <?= esc_attr(apply_filters('bootscore/class/drawer/body', '', 'cart')); ?>">
      <?php do_action('bootscore_before_drawer_cart'); ?>
      <div class="widget_shopping_cart_content"></div>
    </div>
  </dialog>
<?php endif; ?>
