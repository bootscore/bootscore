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
 * @WooCommerce 10.0.0
 *
 * @package Bootscore
 * @version 6.2.2
 */

  // Exit if accessed directly
  defined('ABSPATH') || exit;

  $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
  $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

  if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
    /**
     * This filter is documented in woocommerce/templates/cart/cart.php.
     *
     * @since 2.1.0
     */
    $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
    $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
    ?>
      <div class="woocommerce-mini-cart-item list-group-item py-3 <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>"
           data-bootscore_product_id="<?php echo esc_attr($product_id); ?>" data-key="<?php echo $cart_item_key; ?>">

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
                <p class="cart-product-excerpt <?= apply_filters('bootscore/class/cart/product-excerpt', 'small text-body-secondary text-truncate mb-0'); ?>">
                  <?= get_the_excerpt($product_id); ?>
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
              <?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']); // PHPCS: XSS ok.
              ?>
            </div>

            <?php echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
              'woocommerce_cart_item_remove_link',
              sprintf(
                '<a role="button" href="%s" class="remove_from_cart_button link-danger" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s" data-success_message="%s">' . apply_filters('bootscore/icon/trash', '<i class="fa-regular fa-trash-can"></i>') . '</a>',
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
