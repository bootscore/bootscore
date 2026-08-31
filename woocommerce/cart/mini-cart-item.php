<?php
/**
 * Mini-cart item template
 *
 * @var string $cart_item_key Cart item key
 * @var array{
 *     data: WC_Product,
 *     quantity: int,
 *     variation: array,
 *     variation_id: int
 * } $cart_item Cart item data
 *
 * @WooCommerce 11.0.0
 *
 * @package Bootscore
 * @version 7.0.0
 */

  // Exit if accessed directly
  defined('ABSPATH') || exit;

  $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
  $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

  /**
   * Filter whether this cart item is visible in the mini-cart.
   *
   * @since 1.6.0
   * @param bool   $visible       Whether the cart item is visible. Default true.
   * @param array  $cart_item     The cart item data.
   * @param string $cart_item_key The cart item key.
   */
  $visible = apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key );

  if ( $_product instanceof WC_Product && $_product->exists() && $cart_item['quantity'] > 0 && $visible ) {

    /**
     * This filter is documented in woocommerce/templates/cart/cart.php.
     *
     * @since 2.1.0
     */
    $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
    $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
    $product_subtotal = apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
    ?>
      <div class="woocommerce-mini-cart-item list-group-item py-3 <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>"
           data-bootscore_product_id="<?php echo esc_attr($product_id); ?>" data-key="<?php echo esc_attr($cart_item_key); ?>">

        <div class="row g-3">

          <div class="item-image col-3">
            <?php if (empty($product_permalink)) : ?>
              <?php echo str_replace('<img', '<img class="rounded align-text-top"', $thumbnail); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php else : ?>
                <a href="<?php echo esc_url($product_permalink); ?>">
                  <?php echo str_replace('<img', '<img class="rounded align-text-top"', $thumbnail); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </a>
            <?php endif; ?>
          </div>

          <div class="item-name col-6">
            <?php if (empty($product_permalink)) : ?>
              <?php echo $product_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
              ?>
            <?php else : ?>
                <a class="cart-product-title <?= apply_filters('bootscore/class/cart/product-title', 'h6 text-decoration-none d-block text-truncate mb-0'); ?>"
                   href="<?php echo esc_url($product_permalink); ?>">
                  <?php echo $product_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                  ?>
                </a>
            <?php endif; ?>

            <?php
              if (apply_filters('bootscore/class/cart/enable_cart_product_excerpt', true)) { ?>
                <p class="cart-product-excerpt <?= esc_attr(apply_filters('bootscore/class/cart/product-excerpt', 'small text-body-secondary text-truncate mb-0')); ?>">
                  <?= esc_html(wp_strip_all_tags(get_the_excerpt($product_id))); ?>
                </p>
              <?php }
            ?>

            <?php
              if (apply_filters('bootscore/class/cart/enable_cart_stock_quantity', true)) {
                $stock_quantity = $_product->get_stock_quantity();
                // Check if the product is sold individually
                if ($_product->is_sold_individually()) {
                  echo '<div class="cart-badge mb-2"><span class="badge bg-danger">' . esc_html__('Sold individually', 'woocommerce') . '</span></div>';
                } // Check if the product has only 5 or fewer left in stock
                elseif ($stock_quantity <= 5 && $stock_quantity > 0) {
                  $stock_message = sprintf(esc_html__('Only %s left in stock', 'woocommerce'), $stock_quantity);
                  echo '<div class="cart-badge mb-2"><span class="badge bg-danger">' . $stock_message . '</span></div>';
                }
              }
            ?>

              <div class="item-quantity">
                <?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                ?>
                <?php echo apply_filters('woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf('<span class="qty_text">%s</span> &times; %s', $cart_item['quantity'], $product_price) . '</span>', $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                ?>
              </div>

          </div>

          <div class="remove col-3 text-end">

            <div class="bootscore-custom-render-total h6 mb-4">

                <span>
              <?php echo $product_subtotal; // PHPCS: XSS ok.
              ?>
                </span>
            </div>

            <?php echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
              'woocommerce_cart_item_remove_link',
              sprintf(
                '<a role="button" href="%s" class="remove remove_from_cart_button link-danger" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s" data-success_message="%s">' . wp_kses( apply_filters('bootscore/icon/trash', '<svg class="bs-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" aria-hidden="true"><path d="M170.5 51.6L151.5 80l145 0-19-28.4c-1.5-2.2-4-3.6-6.7-3.6l-93.7 0c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80 368 80l48 0 8 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-8 0 0 304c0 44.2-35.8 80-80 80l-224 0c-44.2 0-80-35.8-80-80l0-304-8 0c-13.3 0-24-10.7-24-24S10.7 80 24 80l8 0 48 0 13.8 0 36.7-55.1C140.9 9.4 158.4 0 177.1 0l93.7 0c18.7 0 36.2 9.4 46.6 24.9zM80 128l0 304c0 17.7 14.3 32 32 32l224 0c17.7 0 32-14.3 32-32l0-304L80 128zm80 64l0 208c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-208c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0l0 208c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-208c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0l0 208c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-208c0-8.8 7.2-16 16-16s16 7.2 16 16z"/></svg>'), bootscore_kses_allowed_svg( wp_kses_allowed_html( 'post' ) ) ) . '</a>',
                esc_url(wc_get_cart_remove_url($cart_item_key)),
                /* translators: %s is the product name */
                esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
                esc_attr($product_id),
                esc_attr($cart_item_key),
                esc_attr($_product->get_sku()),
                /* translators: %s is the product name */
                esc_attr(sprintf(__('&ldquo;%s&rdquo; has been removed from your cart', 'woocommerce'), wp_strip_all_tags($product_name)))
              ),
              $cart_item_key
            );
            ?>

          </div>

        </div><!--row-->

      </div>
    <?php
  }
