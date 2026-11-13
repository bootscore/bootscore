<?php

/**
 * WooCommerce single product
 *
 * Two columns without a template override: the sale badge and gallery (woocommerce_before_single_product_summary)
 * go into the first column, the summary into the second. Tabs, up-sells and related products stay full width.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Open row, gallery column and gallery wrapper
 *
 * The wrapper is position: relative for the sale badge and the zoom trigger. It has no padding,
 * so both are placed from the image edges and not from the column gutter.
 */
function bootscore_wc_single_product_row_open() {
  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/single-product/row', 'row g-7 mb-12')) . '">';
  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/single-product/gallery-col', 'md:col-6')) . '">';
  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/single-product/gallery-wrapper', 'position-relative')) . '">';
}
add_action('woocommerce_before_single_product_summary', 'bootscore_wc_single_product_row_open', 1);


/**
 * Close gallery wrapper and column, open summary column
 */
function bootscore_wc_single_product_summary_col_open() {
  echo '</div>';
  echo '</div>';
  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/single-product/summary-col', 'md:col-6')) . '">';
}
add_action('woocommerce_before_single_product_summary', 'bootscore_wc_single_product_summary_col_open', 99);


/**
 * Close summary column and row
 */
function bootscore_wc_single_product_row_close() {
  echo '</div>';
  echo '</div>';
}
add_action('woocommerce_after_single_product_summary', 'bootscore_wc_single_product_row_close', 1);


/**
 * Price
 */
function bootscore_wc_single_product_price_class($class) {
  return $class . ' ' . apply_filters('bootscore/class/woocommerce/single-product/price', 'fs-lg');
}
add_filter('woocommerce_product_price_class', 'bootscore_wc_single_product_price_class');


/**
 * Variation selects
 */
function bootscore_wc_variation_select_class($args) {
  $args['class'] = trim(($args['class'] ?? '') . ' ' . apply_filters('bootscore/class/woocommerce/single-product/variation-select', 'form-control'));

  return $args;
}
add_filter('woocommerce_dropdown_variation_attribute_options_args', 'bootscore_wc_variation_select_class');


/**
 * Clear variations link
 */
function bootscore_wc_reset_variations_link($link) {
  return str_replace('class="reset_variations"', 'class="reset_variations ' . esc_attr(apply_filters('bootscore/class/woocommerce/single-product/reset-variations', 'btn-outline theme-secondary btn-xs')) . '"', $link);
}
add_filter('woocommerce_reset_variations_link', 'bootscore_wc_reset_variations_link');


/**
 * Gallery images
 *
 * Classes on the image wrapper instead of the img, so the hover zoom (an enlarged copy inside the wrapper)
 * is clipped to the rounded corners as well.
 */
function bootscore_wc_gallery_image_class($html) {
  return str_replace('class="woocommerce-product-gallery__image"', 'class="woocommerce-product-gallery__image ' . esc_attr(apply_filters('bootscore/class/woocommerce/single-product/gallery/image', 'rounded overflow-hidden')) . '"', $html);
}
add_filter('woocommerce_single_product_image_thumbnail_html', 'bootscore_wc_gallery_image_class');


/**
 * Gallery images fill the gallery width, also when the image size (woocommerce_single) is smaller than the column.
 * Otherwise the zoom trigger, placed from the gallery edge, sits outside the image.
 */
function bootscore_wc_gallery_img_class($params) {
  $params['class'] = trim(($params['class'] ?? '') . ' ' . apply_filters('bootscore/class/woocommerce/single-product/gallery/img', 'w-100 h-auto'));

  return $params;
}
add_filter('woocommerce_gallery_image_html_attachment_image_params', 'bootscore_wc_gallery_img_class');


/**
 * Grouped product list, flag while it renders (stock badge spacing in wc-stock-badge.php)
 */
add_action('woocommerce_grouped_product_list_before', function () {
  $GLOBALS['bootscore_wc_in_grouped_list'] = true;
});

add_action('woocommerce_grouped_product_list_after', function () {
  $GLOBALS['bootscore_wc_in_grouped_list'] = false;
});


/**
 * Variation price, shown below the selects when the variations have different prices.
 * Same classes as the product price (woocommerce_product_price_class, fs-lg from above).
 */
function bootscore_wc_variation_price_class($data) {
  if (!empty($data['price_html'])) {
    $data['price_html'] = str_replace('<span class="price">', '<span class="' . esc_attr(apply_filters('woocommerce_product_price_class', 'price')) . '">', $data['price_html']);
  }

  return $data;
}
add_filter('woocommerce_available_variation', 'bootscore_wc_variation_price_class');


/**
 * Rating in the summary: stars and review link in one row
 *
 * Pluggable woocommerce_template_single_rating() instead of single-product/rating.php.
 * WooCommerce's single-product.js opens the reviews tab on a click on .woocommerce-review-link.
 */
if (!function_exists('woocommerce_template_single_rating')) :
  function woocommerce_template_single_rating() {
    global $product;

    if (!post_type_supports('product', 'comments') || !is_a($product, \WC_Product::class) || !wc_review_ratings_enabled()) {
      return;
    }

    $rating_count = $product->get_rating_count();

    if ($rating_count < 1) {
      return;
    }

    $review_count = $product->get_review_count();
    ?>
    <div class="woocommerce-product-rating <?= esc_attr(apply_filters('bootscore/class/woocommerce/single-product/rating', 'd-flex flex-wrap align-items-center gap-3 mb-5')); ?>">
      <?= wc_get_rating_html($product->get_average_rating(), $rating_count); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
      <?php if (comments_open()) : ?>
        <a href="#reviews" class="woocommerce-review-link <?= esc_attr(apply_filters('bootscore/class/woocommerce/single-product/rating/link', 'fs-sm')); ?>" rel="nofollow">
          <?php
          /* translators: %s: review count */
          printf(_n('%s customer review', '%s customer reviews', $review_count, 'woocommerce'), '<span class="count">' . esc_html($review_count) . '</span>'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
          ?>
        </a>
      <?php endif; ?>
    </div>
    <?php
  }
endif;


/**
 * Product meta: SKU, categories and tags, each on its own line. Categories and tags as badges like in the blog.
 *
 * Pluggable woocommerce_template_single_meta() instead of single-product/meta.php.
 * Keeps .sku_wrapper and .sku, WooCommerce's variation script updates the SKU there.
 * Badge classes use the blog filters bootscore/class/badge/category and /tag with context 'woocommerce'.
 */
if (!function_exists('woocommerce_template_single_meta')) :
  function woocommerce_template_single_meta() {
    global $product;

    if (!is_a($product, \WC_Product::class)) {
      return;
    }

    $row = apply_filters('bootscore/class/woocommerce/single-product/meta/row', 'd-flex flex-wrap align-items-center gap-1');

    // Badge classes for the term links, only while the meta renders. No separator, the row has a gap
    $badge = function ($class, $icon = '') {
      return function ($links) use ($class, $icon) {
        return array_map(function ($link) use ($class, $icon) {
          return preg_replace('/<a /', '<a class="' . esc_attr($class) . '" ', preg_replace('/(<a [^>]*>)/', '$1' . $icon, $link, 1), 1);
        }, $links);
      };
    };
    $cat_badge = $badge(apply_filters('bootscore/class/badge/category', 'badge', 'woocommerce'));
    $tag_badge = $badge(apply_filters('bootscore/class/badge/tag', 'badge', 'woocommerce'), apply_filters('bootscore/show/tag/icon', true) ? bootscore_icon('tag', false) . ' ' : '');

    add_filter('term_links-product_cat', $cat_badge);
    add_filter('term_links-product_tag', $tag_badge);
    ?>
    <div class="product_meta <?= esc_attr(apply_filters('bootscore/class/woocommerce/single-product/meta', 'd-flex flex-column gap-3 fs-sm')); ?>">

      <?php do_action('woocommerce_product_meta_start'); ?>

      <?php if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) : ?>
        <span class="sku_wrapper"><?php esc_html_e('SKU:', 'woocommerce'); ?> <span class="sku"><?= ($sku = $product->get_sku()) ? esc_html($sku) : esc_html__('N/A', 'woocommerce'); ?></span></span>
      <?php endif; ?>

      <?php
      $orderby = apply_filters('woocommerce_product_meta_category_orderby', 'breadcrumb', $product);

      echo wc_get_product_category_list( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        $product->get_id(),
        '',
        '<span class="posted_in ' . esc_attr($row) . '">' . _n('Category:', 'Categories:', count($product->get_category_ids()), 'woocommerce') . ' ',
        '</span>',
        is_string($orderby) ? $orderby : ''
      );

      echo wc_get_product_tag_list( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        $product->get_id(),
        '',
        '<span class="tagged_as ' . esc_attr($row) . '">' . _n('Tag:', 'Tags:', count($product->get_tag_ids()), 'woocommerce') . ' ',
        '</span>'
      );
      ?>

      <?php do_action('woocommerce_product_meta_end'); ?>

    </div>
    <?php
    remove_filter('term_links-product_cat', $cat_badge);
    remove_filter('term_links-product_tag', $tag_badge);
  }
endif;


/**
 * Tab headings off, the active tab already names the content
 */
add_filter('woocommerce_product_description_heading', '__return_empty_string');
add_filter('woocommerce_product_additional_information_heading', '__return_empty_string');


/**
 * Additional information: attributes table (wc_display_product_attributes, priority 10) in a responsive wrapper
 */
add_action('woocommerce_product_additional_information', function () {
  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/single-product/attributes/wrapper', 'table-responsive')) . '">';
}, 9);

add_action('woocommerce_product_additional_information', function () {
  echo '</div>';
}, 11);
