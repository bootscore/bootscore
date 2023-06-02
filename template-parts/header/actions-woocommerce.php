<?php

/**
 * Template part for displaying the header-actions if WooCommerce if installed
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

?>

<!-- Search Toggler -->
<?php if (is_active_sidebar('top-nav-search')) : ?>
  <button class="btn btn-outline-secondary ms-1 ms-md-2 top-nav-search-md" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-search" aria-expanded="false" aria-controls="collapse-search">
    <i class="fa-solid fa-magnifying-glass"></i><span class="visually-hidden-focusable">Search</span>
  </button>
<?php endif; ?>

<!-- User Toggler -->
<button class="btn btn-outline-secondary ms-1 ms-md-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-user" aria-controls="offcanvas-user">
  <i class="fa-solid fa-user"></i><span class="visually-hidden-focusable">Account</span>
</button>

<!-- Mini Cart Toggler -->
<button class="btn btn-outline-secondary ms-1 ms-md-2 position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-cart" aria-controls="offcanvas-cart">
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
