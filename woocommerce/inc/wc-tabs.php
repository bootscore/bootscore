<?php

/**
 * WooCommerce single product tabs
 *
 * Bootstrap .nav-tabs via the pluggable woocommerce_output_product_data_tabs().
 * WooCommerce (single-product.js) still does the switching: it shows the panel, sets aria-selected,
 * handles the URL hash, the review link and keyboard navigation. woocommerce.js only syncs .active.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


if (!function_exists('woocommerce_output_product_data_tabs')) :
  function woocommerce_output_product_data_tabs() {
    $product_tabs = apply_filters('woocommerce_product_tabs', []);

    if (empty($product_tabs)) {
      return;
    }

    $first = array_key_first($product_tabs);
    ?>
    <div class="woocommerce-tabs wc-tabs-wrapper <?= esc_attr(apply_filters('bootscore/class/woocommerce/tabs', 'mb-12')); ?>">

      <div class="overflow-x-auto mb-5">
        <ul class="tabs wc-tabs <?= esc_attr(apply_filters('bootscore/class/woocommerce/tabs/nav', 'nav nav-tabs flex-nowrap')); ?>" role="tablist">
          <?php foreach ($product_tabs as $key => $product_tab) : ?>
            <li role="presentation" class="<?= esc_attr($key); ?>_tab nav-item" id="tab-title-<?= esc_attr($key); ?>">
              <a class="nav-link<?= $key === $first ? ' active' : ''; ?>" href="#tab-<?= esc_attr($key); ?>" role="tab" aria-controls="tab-<?= esc_attr($key); ?>" aria-selected="<?= $key === $first ? 'true' : 'false'; ?>"<?= $key === $first ? '' : ' tabindex="-1"'; ?>>
                <?= wp_kses_post(apply_filters('woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key)); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <?php foreach ($product_tabs as $key => $product_tab) : ?>
        <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?= esc_attr($key); ?> panel entry-content wc-tab" id="tab-<?= esc_attr($key); ?>" role="tabpanel" aria-labelledby="tab-title-<?= esc_attr($key); ?>"<?= $key === $first ? '' : ' style="display: none;"'; ?>>
          <?php
          if (isset($product_tab['callback'])) {
            call_user_func($product_tab['callback'], $key, $product_tab);
          }
          ?>
        </div>
      <?php endforeach; ?>

      <?php do_action('woocommerce_product_after_tabs'); ?>
    </div>
    <?php
  }
endif;


/**
 * Review count in the Reviews tab title as badge, "Reviews (2)" becomes "Reviews <span class="badge">2</span>"
 */
function bootscore_wc_reviews_tab_title($title, $key) {
  $badge = apply_filters('bootscore/class/woocommerce/tabs/badge', 'badge badge-subtle theme-primary');

  return preg_replace('/\s*\((\d+)\)/', ' <span class="' . esc_attr($badge) . '">$1</span>', $title);
}
add_filter('woocommerce_product_reviews_tab_title', 'bootscore_wc_reviews_tab_title', 10, 2);
