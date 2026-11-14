<?php

/**
 * WooCommerce my account
 *
 * Navigation and content in two columns without the my-account.php and navigation.php overrides.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Navigation as list group
 *
 * Pluggable woocommerce_account_navigation() instead of myaccount/navigation.php. Keeps WooCommerce's
 * item classes (woocommerce_account_menu_item_classes) on the links and both navigation hooks.
 */
if (!function_exists('woocommerce_account_navigation')) :
  function woocommerce_account_navigation() {
    do_action('woocommerce_before_account_navigation');
    ?>
    <nav class="woocommerce-MyAccount-navigation <?= esc_attr(apply_filters('bootscore/class/woocommerce/my-account/navigation', 'md:col-4 lg:col-3')); ?>" aria-label="<?php esc_attr_e('Account pages', 'woocommerce'); ?>">
      <div class="<?= esc_attr(apply_filters('bootscore/class/woocommerce/my-account/navigation/list', 'list-group')); ?>">
        <?php foreach (wc_get_account_menu_items() as $endpoint => $label) :
          $current = wc_is_current_account_menu_item($endpoint);
          $classes = wc_get_account_menu_item_classes($endpoint) . ' ' . apply_filters('bootscore/class/woocommerce/my-account/navigation/item', 'list-group-item list-group-item-action') . ($current ? ' active' : '');
          ?>
          <a href="<?= esc_url(wc_get_account_endpoint_url($endpoint)); ?>" class="<?= esc_attr($classes); ?>"<?= $current ? ' aria-current="page"' : ''; ?>>
            <?= esc_html($label); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </nav>
    <?php
    do_action('woocommerce_after_account_navigation');
  }
endif;


/**
 * Row and content column
 *
 * my-account.php prints the navigation and div.woocommerce-MyAccount-content directly into the shortcode
 * wrapper div.woocommerce, without a hook after the content and without class filters.
 * Logged in, the wrapper becomes the row and the content div the second column.
 * Below md the content comes first, so the page just opened is visible without scrolling past the navigation.
 */
function bootscore_wc_my_account_columns($output, $tag) {
  if ('woocommerce_my_account' !== $tag || false === strpos($output, 'woocommerce-MyAccount-navigation')) {
    return $output;
  }

  $row     = apply_filters('bootscore/class/woocommerce/my-account/row', 'row g-7');
  $content = apply_filters('bootscore/class/woocommerce/my-account/content', 'md:col-8 lg:col-9 order-first md:order-last');

  $output = preg_replace('/<div class="woocommerce">/', '<div class="woocommerce ' . esc_attr($row) . '">', $output, 1);
  $output = preg_replace('/<div class="woocommerce-MyAccount-content">/', '<div class="woocommerce-MyAccount-content ' . esc_attr($content) . '">', $output, 1);

  return $output;
}
add_filter('do_shortcode_tag', 'bootscore_wc_my_account_columns', 10, 2);


/**
 * Orders table in a responsive wrapper
 *
 * Opens before the table and closes before the pagination, only when there are orders
 * (without orders WooCommerce prints a notice and no pagination hook).
 */
add_action('woocommerce_before_account_orders', function ($has_orders) {
  if ($has_orders) {
    echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/my-account/orders/wrapper', 'table-responsive text-nowrap')) . '">';
  }
});

add_action('woocommerce_before_account_orders_pagination', function () {
  echo '</div>';
});


/**
 * Order status as badge
 *
 * Theme per status via filter, 2nd argument is the status (pending, processing, on-hold, completed, cancelled, refunded, failed, checkout-draft or a custom one).
 * Used in the orders table and in the status sentence of the single order view.
 */
function bootscore_wc_order_status_badge_html($status) {
  $themes = [
    'completed'  => 'theme-success',
    'processing' => 'theme-info',
    'on-hold'    => 'theme-warning',
    'cancelled'  => 'theme-danger',
    'failed'     => 'theme-danger',
  ];
  $classes = apply_filters('bootscore/class/woocommerce/my-account/order-status', 'badge badge-subtle ' . ($themes[$status] ?? 'theme-secondary'), $status);

  return '<span class="order-status ' . esc_attr($classes) . '">' . esc_html(wc_get_order_status_name($status)) . '</span>';
}

function bootscore_wc_order_status_badge($order) {
  echo bootscore_wc_order_status_badge_html($order->get_status()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action('woocommerce_my_account_my_orders_column_order-status', 'bootscore_wc_order_status_badge');


/**
 * Downloads table in a responsive wrapper (account downloads page, only with downloads)
 */
add_action('woocommerce_before_available_downloads', function () {
  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/my-account/downloads/wrapper', 'table-responsive text-nowrap')) . '">';
});

add_action('woocommerce_after_available_downloads', function () {
  echo '</div>';
});


/**
 * Download button with Bootstrap classes instead of .button.alt
 *
 * Column hook of order/order-downloads.php, so it applies to the account downloads and the order downloads.
 */
function bootscore_wc_download_file_button($download) {
  echo '<a href="' . esc_url($download['download_url']) . '" class="woocommerce-MyAccount-downloads-file ' . esc_attr(apply_filters('bootscore/class/woocommerce/download/button', 'btn-solid theme-primary btn-sm')) . '">' . esc_html($download['download_name']) . '</a>';
}
add_action('woocommerce_account_downloads_column_download-file', 'bootscore_wc_download_file_button');


/**
 * Single order view: "Order #123 was placed on … and is currently …"
 *
 * WooCommerce wraps number, date and status in <mark> (yellow highlight). Number and date become <strong>,
 * the status the same badge as in the orders table. woocommerce_order_details_status exists since WooCommerce 10.1.
 */
function bootscore_wc_order_details_status($text, $order) {
  $text = preg_replace('/<mark class="order-(number|date)">(.*?)<\/mark>/', '<strong class="order-$1">$2</strong>', $text);
  $text = preg_replace('/<mark class="order-status">.*?<\/mark>/', bootscore_wc_order_status_badge_html($order->get_status()), $text);

  return $text;
}
add_filter('woocommerce_order_details_status', 'bootscore_wc_order_details_status', 10, 2);
