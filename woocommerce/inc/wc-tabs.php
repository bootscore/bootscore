<?php

/**
 * WooCommerce Single-product tabs
 *
 * @package Bootscore 
 * @version 6.2.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Custom WooCommerce product data tabs with Bootstrap markupvia pluggable function.
 *
 * @link https://github.com/bootscore/bootscore/pull/1021
 * @link https://www.businessbloomer.com/woocommerce-explode-product-tabs/
 */
if ( ! function_exists( 'woocommerce_output_product_data_tabs' ) ) {

  function woocommerce_output_product_data_tabs() {
    $product_tabs = apply_filters( 'woocommerce_product_tabs', array() );
    if ( empty( $product_tabs ) ) return;
    ?>

    <div class="woocommerce-tabs wc-tabs-wrapper">
      <div class="d-flex text-nowrap overflow-x-auto scrollbar-none mb-3">
        <ul class="wc-tabs nav nav-tabs flex-nowrap flex-grow-1" role="tablist">
          <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
            <li role="presentation" class="<?php echo esc_attr( $key ); ?>_tab nav-item" id="tab-title-<?php echo esc_attr( $key ); ?>">
              <a class="nav-link" href="#tab-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
                <?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
        <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
          <?php
          if ( isset( $product_tab['callback'] ) ) {
            call_user_func( $product_tab['callback'], $key, $product_tab );
          }
          ?>
        </div>
      <?php endforeach; ?>

        <?php do_action( 'woocommerce_product_after_tabs' ); ?>
    </div>

    <?php
  }
  
}
