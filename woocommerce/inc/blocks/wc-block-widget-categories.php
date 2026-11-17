<?php

/**
 * WooCommerce Product Categories Block Widget
 *
 * Bootstrap classes for the product categories block (woocommerce/product-categories), list and dropdown.
 * Like the core categories block widget (inc/blocks/block-widget-categories.php).
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Product Categories Block
 */
if (!function_exists('bootscore_wc_block_widget_categories_classes')) {
  /**
   * Adds Bootstrap classes to the product categories block widget.
   *
   * @param string $block_content The block content.
   * @param array  $block         The full block, including name and attributes.
   * @return string The filtered block content.
   */
  function bootscore_wc_block_widget_categories_classes($block_content, $block) {
    if ('' === trim($block_content)) {
      return $block_content;
    }

    $tags = new WP_HTML_Tag_Processor($block_content);

    // Dropdown: input group with select and button. The wrapper around label and select becomes d-contents,
    // so the select is a direct child of the input group
    if (false !== strpos($block_content, 'wc-block-product-categories__dropdown')) {
      while ($tags->next_tag()) {
        if ($tags->has_class('wc-block-product-categories') && $tags->has_class('is-dropdown')) {
          $tags->add_class(apply_filters('bootscore/class/woocommerce/block/categories/dropdown/group', 'input-group'));
        } elseif ($tags->has_class('wc-block-product-categories__dropdown')) {
          $tags->add_class('d-contents');
        } elseif ('SELECT' === $tags->get_tag()) {
          $tags->add_class(apply_filters('bootscore/class/woocommerce/block/categories/dropdown', 'form-control'));
        } elseif ($tags->has_class('wc-block-product-categories__button')) {
          $tags->add_class(apply_filters('bootscore/class/woocommerce/block/categories/dropdown/button', 'btn-outline theme-secondary input-group-btn'));
        }
      }

      return apply_filters('bootscore/woocommerce/block/categories/content', $tags->get_updated_html(), $block);
    }

    // List: list group, the link stretched over the item, count as badge, the current category active
    $current = is_tax('product_cat') ? get_term_link(get_queried_object()) : '';
    $current = is_string($current) ? $current : '';

    while ($tags->next_tag()) {
      $tag = $tags->get_tag();

      if ('UL' === $tag && $tags->has_class('wc-block-product-categories-list')) {
        $tags->add_class(apply_filters('bootscore/class/woocommerce/block/categories/list', 'list-group'));
      } elseif ('LI' === $tag && $tags->has_class('wc-block-product-categories-list-item')) {
        $tags->add_class(apply_filters('bootscore/class/woocommerce/block/categories/list-item', 'list-group-item list-group-item-action'));
        $tags->set_bookmark('item');
      } elseif ('A' === $tag) {
        $tags->add_class(apply_filters('bootscore/class/woocommerce/block/categories/link', 'stretched-link text-decoration-none fg-reset'));

        // Current category: active item, aria-current on the link
        if ($current && untrailingslashit((string) $tags->get_attribute('href')) === untrailingslashit($current)) {
          $tags->set_attribute('aria-current', 'page');
          $tags->seek('item');
          $tags->add_class('active');
          $tags->next_tag('a');
        }
      } elseif ($tags->has_class('wc-block-product-categories-list-item-count')) {
        $tags->add_class(apply_filters('bootscore/class/woocommerce/block/categories/count', 'badge badge-subtle theme-primary'));
      } elseif ('IMG' === $tag && null !== $tags->get_attribute('src')) {
        $tags->add_class(apply_filters('bootscore/class/woocommerce/block/categories/image', 'rounded'));
      }
    }

    $tags->release_bookmark('item');

    return apply_filters('bootscore/woocommerce/block/categories/content', $tags->get_updated_html(), $block);
  }
}
add_filter('render_block_woocommerce/product-categories', 'bootscore_wc_block_widget_categories_classes', 10, 2);
