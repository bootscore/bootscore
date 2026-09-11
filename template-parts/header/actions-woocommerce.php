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
    <?php bootscore_icon('search'); ?><span class="visually-hidden-focusable">Search</span>
  </button>
<?php endif; ?>

<!-- User toggler -->
<?php
if (apply_filters('bootscore/enable_account', true)) {
  if ( is_account_page() || is_checkout() ) {
  // Do nothing
  } else { ?>
    <button class="<?= esc_attr(apply_filters('bootscore/class/header/button', 'btn btn-outline-secondary', 'account-toggler')); ?> <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-2', 'account-toggler')); ?> account-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-user" aria-controls="offcanvas-user" aria-label="<?php esc_attr_e( 'Account toggler', 'bootscore' ); ?>">
      <?php bootscore_icon('user'); ?> 
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
    <?php bootscore_icon('arrow-left'); ?>
    <?php bootscore_icon('cart'); ?>  
  </a>
<?php } else { ?>
  <!-- Add mini-cart toggler -->
  <button class="<?= esc_attr(apply_filters('bootscore/class/header/button', 'btn btn-outline-secondary', 'cart-toggler')); ?> <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 ms-md-2', 'cart-toggler')); ?> position-relative cart-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-cart" aria-controls="offcanvas-cart" aria-label="<?php esc_attr_e( 'Cart toggler', 'bootscore' ); ?>">
    <?php bootscore_icon('cart'); ?> <span class="visually-hidden-focusable">Cart</span>
    <?php if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
      ?>
      <span class="cart-content"></span>
    <?php } ?>
  </button>
<?php } ?>
