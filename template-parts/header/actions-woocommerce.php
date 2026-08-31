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


<!-- Search toggler -->
<?php if (apply_filters('bootscore/sidebar/has_widgets', is_active_sidebar('top-nav-search'), 'top-nav-search')) : ?>
  <button class="<?= esc_attr(apply_filters('bootscore/class/header/button', 'btn btn-outline-secondary', 'search-toggler')); ?> <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-2', 'search-toggler')); ?> search-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-search" aria-expanded="false" aria-controls="collapse-search" aria-label="<?php esc_attr_e( 'Search toggler', 'bootscore' ); ?>">
    <?= wp_kses( apply_filters('bootscore/icon/search', '<svg class="bs-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>'), bootscore_kses_allowed_svg( wp_kses_allowed_html( 'post' ) )); ?><span class="visually-hidden-focusable">Search</span>
  </button>
<?php endif; ?>

<!-- User toggler -->
<?php
if (apply_filters('bootscore/enable_account', true)) {
  if ( is_account_page() || is_checkout() ) {
  // Do nothing
  } else { ?>
    <button class="<?= esc_attr(apply_filters('bootscore/class/header/button', 'btn btn-outline-secondary', 'account-toggler')); ?> <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-2', 'account-toggler')); ?> account-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-user" aria-controls="offcanvas-user" aria-label="<?php esc_attr_e( 'Account toggler', 'bootscore' ); ?>">
      <?= wp_kses( apply_filters('bootscore/icon/user', '<svg class="bs-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/></svg>'), bootscore_kses_allowed_svg( wp_kses_allowed_html( 'post' ) )); ?>
    </button>
  <?php } 
 }
?>



<!-- Mini cart toggler -->
<?php
if ( is_cart() ) {
 // Do nothing
} elseif ( is_checkout() ) { ?>
  <!-- Add a back-to-cart button -->
  <?php
  // Check the filter and AJAX cart option
  $skip_cart_filter = apply_filters('bootscore/skip_cart', true);
  $ajax_cart_en = 'yes' === get_option('woocommerce_enable_ajax_add_to_cart');

 if ($skip_cart_filter && $ajax_cart_en) {
    $back_to_cart_url = get_permalink(wc_get_page_id('shop'));
  } else {
    $back_to_cart_url = wc_get_cart_url();
  }

  ?>
  <a class="<?= esc_attr(apply_filters('bootscore/class/header/button', 'btn btn-outline-secondary', 'cart-toggler')); ?> <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-2', 'cart-toggler')); ?> back-to-cart" href="<?= esc_url($back_to_cart_url); ?>">
    <?= wp_kses( apply_filters('bootscore/icon/arrow-left', '<svg class="bs-icon d-none d-md-inline me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg>'), bootscore_kses_allowed_svg( wp_kses_allowed_html( 'post' ) )); ?>
    <?= wp_kses( apply_filters('bootscore/icon/cart', '<svg class="bs-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M160 112c0-35.3 28.7-64 64-64s64 28.7 64 64l0 48-128 0 0-48zm-48 48l-64 0c-26.5 0-48 21.5-48 48L0 416c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-208c0-26.5-21.5-48-48-48l-64 0 0-48C336 50.1 285.9 0 224 0S112 50.1 112 112l0 48zm24 48a24 24 0 1 1 0 48 24 24 0 1 1 0-48zm152 24a24 24 0 1 1 48 0 24 24 0 1 1 -48 0z"/></svg>'), bootscore_kses_allowed_svg( wp_kses_allowed_html( 'post' ) )); ?><span class="visually-hidden-focusable">Return to <?= esc_html(($back_to_cart_url == wc_get_cart_url()) ? 'Cart' : 'Shop'); ?></span>
  </a>
<?php } else { ?>
  <!-- Add mini-cart toggler -->
  <button class="<?= esc_attr(apply_filters('bootscore/class/header/button', 'btn btn-outline-secondary', 'cart-toggler')); ?> <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-2', 'cart-toggler')); ?> position-relative cart-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-cart" aria-controls="offcanvas-cart" aria-label="<?php esc_attr_e( 'Cart toggler', 'bootscore' ); ?>">
    <?= wp_kses( apply_filters('bootscore/icon/cart', '<svg class="bs-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M160 112c0-35.3 28.7-64 64-64s64 28.7 64 64l0 48-128 0 0-48zm-48 48l-64 0c-26.5 0-48 21.5-48 48L0 416c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-208c0-26.5-21.5-48-48-48l-64 0 0-48C336 50.1 285.9 0 224 0S112 50.1 112 112l0 48zm24 48a24 24 0 1 1 0 48 24 24 0 1 1 0-48zm152 24a24 24 0 1 1 48 0 24 24 0 1 1 -48 0z"/></svg>'), bootscore_kses_allowed_svg( wp_kses_allowed_html( 'post' ) )); ?><span class="visually-hidden-focusable">Cart</span>
    <?php if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
      ?>
      <span class="cart-content"></span>
    <?php } ?>
  </button>
<?php } ?>
