<?php
  /**
   * Mini-cart footer template
   *
   */

?>

<div>

    <div class="woocommerce-mini-cart__total total h5 d-flex justify-content-between">
      <?php
        /**
         * Hook: woocommerce_widget_shopping_cart_total.
         *
         * @hooked woocommerce_widget_shopping_cart_subtotal - 10
         */
        do_action('woocommerce_widget_shopping_cart_total');
      ?>
    </div>

  <?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>

    <div class="woocommerce-mini-cart__buttons buttons"><?php do_action('woocommerce_widget_shopping_cart_buttons'); ?></div>

  <?php do_action('woocommerce_widget_shopping_cart_after_buttons'); ?>

</div>
