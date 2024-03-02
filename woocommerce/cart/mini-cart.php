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
 * @version 7.9.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_mini_cart'); ?>

<?php if (!WC()->cart->is_empty()) : ?>

  <div class="woocommerce-mini-cart cart_list product_list_widget list-group list-group-flush <?php echo esc_attr($args['list_class']); ?>">
    <?php
    do_action('woocommerce_before_mini_cart_contents');

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
      $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
      $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

      if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
        /**
         * This filter is documented in woocommerce/templates/cart/cart.php.
         *
         @since 2.1.0
         */
        $product_name      = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
        $thumbnail         = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
        $product_price     = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
        ?>
        <div class="woocommerce-mini-cart-item list-group-item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>" data-bootscore_product_id="<?php echo esc_attr($product_id); ?>" data-key="<?php echo $cart_item_key; ?>">

          <div class="row">

            <div class="item-image col-3">
              <?php if (empty($product_permalink)) : ?>
                <?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                ?>
              <?php else : ?>
                <a href="<?php echo esc_url($product_permalink); ?>">
                  <?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                  ?>
                </a>
              <?php endif; ?>
            </div>

            <div class="item-name col-6">
              <?php if (empty($product_permalink)) : ?>
                <?php echo $product_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                ?>
              <?php else : ?>
                <strong><a href="<?php echo esc_url($product_permalink); ?>">
                    <?php echo $product_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                  </a></strong>
              <?php endif; ?>
              <div class="item-quantity">
                <?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php
                $currency_symbol           = get_woocommerce_currency_symbol();
                $display_currency_position = get_option( 'woocommerce_currency_pos' );
                $quantity_text             = sprintf( '<span class="qty_text">%s</span>', $cart_item['quantity'] );
                $formatted_price           = sprintf( '%s &times; %s', $quantity_text, $product_price);    
                $price_with_symbol         = $display_currency_position ? $currency_symbol . ' ' . $formatted_price : $formatted_price . ' ' . $currency_symbol;
                ?>
                <?php echo apply_filters('woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf('<span class="qty_text">%s</span> &times; %s', $cart_item['quantity'], $product_price) . '</span>', $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                ?>
              </div>
            </div>

            <div class="remove col-3 text-end">
              <?php echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                'woocommerce_cart_item_remove_link',
                sprintf(
                  '<a href="%s" class="remove_from_cart_button text-danger" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"><i class="fa-regular fa-trash-can"></i></a>',
                  esc_url(wc_get_cart_remove_url($cart_item_key)),
                  /* translators: %s is the product name */
                  esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                  esc_attr($product_id),
                  esc_attr($cart_item_key),
                  esc_attr($_product->get_sku())
                ),
                $cart_item_key
              );
              ?>

              <div class="bootscore-custom-render-total">
              <?php
              $item_price      = '';
              $currency_symbol = get_woocommerce_currency_symbol();

              if ($display_currency_position === 'left') {
                  $item_price = $currency_symbol . $cart_item['line_total'] . '.00';
              } elseif ( $display_currency_position === 'right' ) {
                $item_price = $cart_item['line_total'] . '.00' . $currency_symbol;
              } elseif ($display_currency_position === 'left_space' || $display_currency_position === 'right_space') {
                $item_price = $cart_item['line_total'] . '.00' . $currency_symbol;
                if ($display_currency_position === 'left_space') {
                  $item_price = $currency_symbol . '&nbsp;' . $cart_item['line_total'] . '.00';
                } elseif ($display_currency_position === 'right_space') {
                  $item_price = $cart_item['line_total'] . '.00' . '&nbsp;' . $currency_symbol;
                }
              }
              echo $item_price;
              ?>
              </div>

            </div>

          </div>
          <!--row-->

        </div>
        <?php
      }
    }

    do_action('woocommerce_mini_cart_contents');
    ?>
  </div>

  <div class="cart-footer bg-body-tertiary text-center p-3">

    <p class="woocommerce-mini-cart__total total">
      <?php
      /**
       * Hook: woocommerce_widget_shopping_cart_total.
       *
       * @hooked woocommerce_widget_shopping_cart_subtotal - 10
       */
      do_action('woocommerce_widget_shopping_cart_total');
      ?>
    </p>

    <p class="text-body-secondary small shipping-text"><?php esc_html_e('To find out your shipping cost, please proceed to checkout.', 'bootscore'); ?></p>

    <?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>

    <div class="woocommerce-mini-cart__buttons buttons"><?php do_action('woocommerce_widget_shopping_cart_buttons'); ?></div>

    <?php do_action('woocommerce_widget_shopping_cart_after_buttons'); ?>

  </div>

<?php else : ?>

  <p class="woocommerce-mini-cart__empty-message woocommerce-info m-3"><?php esc_html_e('No products in the cart.', 'woocommerce'); ?></p>

<?php endif; ?>

<?php do_action('woocommerce_after_mini_cart'); ?>