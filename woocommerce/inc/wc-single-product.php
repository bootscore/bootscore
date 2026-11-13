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
  return str_replace('class="reset_variations"', 'class="reset_variations ' . esc_attr(apply_filters('bootscore/class/woocommerce/single-product/reset-variations', 'btn-outline theme-secondary btn-sm')) . '"', $link);
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
