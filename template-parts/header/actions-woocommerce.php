<?php

/**
 * Template part for displaying the header-actions if WooCommerce if installed
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<!-- Search toggler -->
<?php if (is_active_sidebar('top-nav-search')) : ?>
  <button class="<?= apply_filters('bootscore/class/header_button', 'btn btn-outline-secondary', 'search'); ?> ms-1 ms-md-2 search-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-search" aria-expanded="false" aria-controls="collapse-search">
    <i class="fa-solid fa-magnifying-glass"></i><span class="visually-hidden-focusable">Search</span>
  </button>
<?php endif; ?>

<!-- User toggler -->
<?php
if ( is_account_page() || is_checkout() ) {
 // Do nothing
} else { ?>
  <button class="<?= apply_filters('bootscore/class/header_button', 'btn btn-outline-secondary', 'account'); ?> ms-1 ms-md-2 account-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-user" aria-controls="offcanvas-user">
    <i class="fa-solid fa-user"></i><span class="visually-hidden-focusable">Account</span>
  </button>
<?php } ?>


<!-- Mini cart toggler -->
<?php
if ( is_cart() ) {
 // Do nothing
} elseif ( is_checkout() ) { ?>
  <!-- Add a back-to-cart button -->
  <a class="<?= apply_filters('bootscore/class/header_button', 'btn btn-outline-secondary', 'return-to-cart'); ?> ms-1 ms-md-2 back-to-cart" href="<?= wc_get_cart_url() ?>">
    <i class="fa-solid fa-arrow-left d-none d-md-inline me-2"></i><i class="fa-solid fa-bag-shopping"></i><span class="visually-hidden-focusable">Return to Cart</span>
  </a>
  <?php 
} else { ?>
  <!-- Add mini-cart toggler -->
  <button class="<?= apply_filters('bootscore/class/header_button', 'btn btn-outline-secondary', 'mini-cart'); ?> ms-1 ms-md-2 position-relative cart-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-cart" aria-controls="offcanvas-cart">
    <i class="fa-solid fa-bag-shopping"></i><span class="visually-hidden-focusable">Cart</span>
    <?php if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
      $count = WC()->cart->cart_contents_count;
      ?>
      <span class="cart-content">
        <?php if ($count > 0) { ?>
          <?= esc_html($count); ?>
          <?php
        }
        ?></span>
    <?php } ?>
  </button>
<?php } ?>
