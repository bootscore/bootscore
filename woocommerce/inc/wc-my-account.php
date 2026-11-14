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
 */
function bootscore_wc_my_account_columns($output, $tag) {
  if ('woocommerce_my_account' !== $tag || false === strpos($output, 'woocommerce-MyAccount-navigation')) {
    return $output;
  }

  $row     = apply_filters('bootscore/class/woocommerce/my-account/row', 'row g-7');
  $content = apply_filters('bootscore/class/woocommerce/my-account/content', 'md:col-8 lg:col-9');

  $output = preg_replace('/<div class="woocommerce">/', '<div class="woocommerce ' . esc_attr($row) . '">', $output, 1);
  $output = preg_replace('/<div class="woocommerce-MyAccount-content">/', '<div class="woocommerce-MyAccount-content ' . esc_attr($content) . '">', $output, 1);

  return $output;
}
add_filter('do_shortcode_tag', 'bootscore_wc_my_account_columns', 10, 2);
