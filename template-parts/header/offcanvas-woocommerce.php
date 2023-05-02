<?php

/**
 * Template part for displaying the offcanvas user and cart if WooCommerce is installed
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

?>


<!-- offcanvas user -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-user">
  <div class="offcanvas-header">
    <span class="h5 offcanvas-title"><?php esc_html_e('Account', 'bootscore'); ?></span>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="my-offcanvas-account">
      <?php include get_template_directory() . '/woocommerce/myaccount/my-account-offcanvas.php'; ?>
    </div>
  </div>
</div>

<!-- offcanvas cart -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-cart">
  <div class="offcanvas-header">
    <span class="h5 offcanvas-title"><?php esc_html_e('Cart', 'bootscore'); ?></span>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-0">
    <div class="cart-list">
      <div class="widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div>
    </div>
  </div>
</div>
