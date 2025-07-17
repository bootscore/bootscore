<?php
  /**
   * Mini-cart
   *
   * Contains the markup for the mini-cart, used by the cart widget.
   *
   * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
   *
   * HOWEVER, on occasion WooCommerce will need to update template files and you
   * (the theme developer) will need to copy the new files to your theme to
   * maintain compatibility. We try to do this as little as possible, but it does
   * happen. When this occurs the version of the template file will be bumped and
   * the readme will list any important changes.
   *
   * @see     https://docs.woocommerce.com/document/template-structure/
   * @package WooCommerce\Templates
   * @version 10.0.0
   *
   * @package Bootscore
   * @version 6.3.0
   */

  defined('ABSPATH') || exit;

  do_action('woocommerce_before_mini_cart'); ?>

<?php if (WC()->cart && !WC()->cart->is_empty()) : ?>

    <div class="woocommerce-mini-cart cart_list product_list_widget list-group list-group-flush <?php echo esc_attr($args['list_class']); ?>">
      <?php
        do_action('woocommerce_before_mini_cart_contents');

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
          wc_get_template(
            'cart/mini-cart-item.php',
            array(
              'cart_item_key' => $cart_item_key,
              'cart_item' => $cart_item
            )
          );
        }

        do_action('woocommerce_mini_cart_contents');
      ?>
    </div>

    <?php do_action('bootscore_before_mini_cart_footer'); ?>
    
    <div class="cart-footer <?= apply_filters('bootscore/class/header/cart/footer', 'bg-body-tertiary p-3'); ?>">

      <?php wc_get_template('cart/mini-cart-footer.php'); ?>

    </div>

<?php else : ?>

    <p class="woocommerce-mini-cart__empty-message woocommerce-info m-3"><?php esc_html_e('No products in the cart.', 'woocommerce'); ?></p>

<?php endif; ?>

<?php do_action('woocommerce_after_mini_cart'); ?>
