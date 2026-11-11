<?php

/**
 * Template part for displaying the header-actions if WooCommerce if installed
 * Template Version: 7.0.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<!-- Search menu -->
<?php if (apply_filters('bootscore/sidebar/has_widgets', is_active_sidebar('top-nav-search'), 'top-nav-search')) : ?>
  <div class="<?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 md:ms-3', 'search-toggler')); ?>">
    <button class="<?= esc_attr(apply_filters('bootscore/class/header/button', 'btn-solid', 'search-toggler')); ?> search-toggler" type="button" data-bs-toggle="menu" data-bs-placement="bottom-end" aria-expanded="false">
      <?php bootscore_icon('search'); ?><span class="visually-hidden"><?php esc_html_e('Search', 'bootscore'); ?></span>
    </button>
    <div class="menu <?= esc_attr(apply_filters('bootscore/class/header/search/menu', 'search-menu p-3')); ?>">
      <?php dynamic_sidebar('top-nav-search'); ?>
    </div>
  </div>
<?php endif; ?>


<!-- Account toggler -->
<?php if (apply_filters('bootscore/enable_account', true) && !is_account_page() && !is_checkout()) : ?>
  <button class="<?= esc_attr(apply_filters('bootscore/class/header/button', 'btn-solid', 'account-toggler')); ?> <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 md:ms-3', 'account-toggler')); ?> account-toggler" type="button" data-bs-toggle="drawer" data-bs-target="#drawer-account" aria-controls="drawer-account" aria-label="<?php esc_attr_e('Account toggler', 'bootscore'); ?>">
    <?php bootscore_icon('user'); ?>
  </button>
<?php endif; ?>


<!-- Mini cart toggler -->
<?php if (is_checkout()) :
  // Back to shop if the cart page is skipped, otherwise back to cart
  $skip_cart = apply_filters('bootscore/skip_cart', true) && 'yes' === get_option('woocommerce_enable_ajax_add_to_cart');
  $back_url  = $skip_cart ? get_permalink(wc_get_page_id('shop')) : wc_get_cart_url();
  $back_label = $skip_cart ? __('Back to shop', 'bootscore') : __('Back to cart', 'bootscore');
  ?>
  <a class="<?= esc_attr(apply_filters('bootscore/class/header/button', 'btn-solid', 'cart-toggler')); ?> <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 md:ms-3', 'cart-toggler')); ?> back-to-cart" href="<?= esc_url($back_url); ?>" aria-label="<?= esc_attr($back_label); ?>">
    <?php bootscore_icon('arrow-left'); ?>
    <?php bootscore_icon('cart'); ?>
  </a>
<?php elseif (!is_cart()) : ?>
  <button class="<?= esc_attr(apply_filters('bootscore/class/header/button', 'btn-solid', 'cart-toggler')); ?> <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 md:ms-3', 'cart-toggler')); ?> position-relative cart-toggler" type="button" data-bs-toggle="drawer" data-bs-target="#drawer-cart" aria-controls="drawer-cart" aria-label="<?php esc_attr_e('Cart toggler', 'bootscore'); ?>">
    <?php bootscore_icon('cart'); ?>
    <span class="cart-content d-contents"></span>
  </button>
<?php endif; ?>
