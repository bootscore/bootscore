<?php

/**
 * WooCommerce Product Filters Block Widget
 *
 * Bootstrap classes for the product filters block (woocommerce/product-filters) and its inner blocks:
 * active filters (removable chips), clear button, price slider, checkbox lists (rating, taxonomy, status, attribute)
 * and chips. WooCommerce's filter stylesheets are dequeued, the theme styles the block (_wc-block-widgets.scss).
 * The mobile overlay is not used, filters are always shown inline (the sidebar has its own drawer on mobile).
 *
 * Classes are also added inside <template> elements, which the Interactivity API clones for items rendered in the browser.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Product Filters Block
 */
if (!function_exists('bootscore_wc_block_widget_product_filter_classes')) {
  /**
   * Adds Bootstrap classes to the product filters block widget.
   *
   * @param string $block_content The block content.
   * @param array  $block         The full block, including name and attributes.
   * @return string The filtered block content.
   */
  function bootscore_wc_block_widget_product_filter_classes($block_content, $block) {
    if ('' === trim($block_content)) {
      return $block_content;
    }

    // WooCommerce's stylesheets for the filter blocks (enqueued when they render) are unlayered and beat the theme's
    // layered CSS. The inner blocks render first, so all of them are enqueued here
    foreach (wp_styles()->queue as $handle) {
      if (0 === strpos($handle, 'wc-blocks-style-product-filter')) {
        wp_dequeue_style($handle);
      }
    }

    // Classes per WooCommerce class. Filter names: bootscore/class/woocommerce/block/product-filters/<key>
    $classes = array(
      'wc-block-product-filter-removable-chips__items'      => array('chips', 'list-unstyled d-flex flex-wrap gap-2 m-0'),
      'wc-block-product-filter-removable-chips__item'       => array('chip', 'badge badge-subtle theme-primary d-inline-flex align-items-center gap-1 text-wrap text-start'),
      'wc-block-product-filter-removable-chips__remove'     => array('chip/remove', 'btn-close'),
      'wc-block-product-filter-checkbox-list__input'        => array('checkbox', 'check'),
      'wc-block-product-filter-checkbox-list__count'        => array('count', 'badge badge-subtle theme-primary'),
      'wc-block-product-filter-checkbox-list__show-more-button' => array('show-more', 'btn-link btn-sm p-0'),
      'wc-block-product-filter-chips__item'                 => array('chip-button', 'btn-outline theme-secondary btn-sm'),
      'wc-block-product-filter-chips__count'                => array('chip-button/count', 'badge badge-subtle theme-secondary'),
      'wc-block-product-filter-chips__show-more'            => array('show-more', 'btn-link btn-sm p-0'),
    );

    $tags     = new WP_HTML_Tag_Processor($block_content);
    $in_clear = false;

    while ($tags->next_tag()) {
      // Clear filters: core button (classes from block-buttons.php) as small outline danger button
      if ($tags->has_class('wp-block-woocommerce-product-filter-clear-button')) {
        $in_clear = true;
        continue;
      }

      if ($in_clear && $tags->has_class('wp-block-buttons')) {
        $tags->remove_class('mb-5');
        continue;
      }

      if ($in_clear && 'BUTTON' === $tags->get_tag()) {
        foreach (array('btn-solid', 'btn-outline', 'theme-primary') as $class) {
          $tags->remove_class($class);
        }
        $tags->add_class(apply_filters('bootscore/class/woocommerce/block/product-filters/clear', 'btn-outline theme-danger btn-sm'));
        $in_clear = false;
        continue;
      }

      // Price inputs (min and max, type text) as form controls
      if ('INPUT' === $tags->get_tag() && 'text' === $tags->get_attribute('type') && ($tags->has_class('min') || $tags->has_class('max'))) {
        $tags->add_class(apply_filters('bootscore/class/woocommerce/block/product-filters/price-input', 'form-control'));
        continue;
      }

      foreach ($classes as $wc_class => $filter) {
        if ($tags->has_class($wc_class)) {
          $tags->add_class(apply_filters('bootscore/class/woocommerce/block/product-filters/' . $filter[0], $filter[1]));
          break;
        }
      }
    }

    $block_content = $tags->get_updated_html();

    // Counts as badges without WooCommerce's parentheses: (<span data-wp-text="context.item.count">3</span>)
    $block_content = preg_replace('/(class="[^"]*__count[^"]*"[^>]*>)\s*\(\s*(<span[^>]*>.*?<\/span>)\s*\)/s', '$1$2', $block_content);

    return apply_filters('bootscore/woocommerce/block/product-filters/content', $block_content, $block);
  }
}
add_filter('render_block_woocommerce/product-filters', 'bootscore_wc_block_widget_product_filter_classes', 10, 2);
