<?php

/**
 * WooCommerce loop
 *
 * Shop loop without template overrides. WooCommerce outputs ul.products with one li per product or category.
 * The ul becomes a .row, each li a .col with a card inside, built with the loop actions.
 * The title link is a .stretched-link, so the whole card is clickable and the HTML stays valid.
 *
 * Plugins that render their own loop (bs Loop, bs Load More) use woocommerce_product_loop_start()/_end()
 * and set the row and col classes via the filters below while rendering.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Row and col classes
 *
 * Row: ul.products, col: each li. Only between loop start and loop end, so the single product
 * and product cards outside a loop don't get the col classes.
 * Change via filter, 2nd argument is the loop name, e.g. 'related', 'up-sells', 'cross-sells' or '' (shop).
 */
function bootscore_wc_loop_start($html) {
  $GLOBALS['bootscore_wc_in_loop'] = true;

  $row = apply_filters('bootscore/class/woocommerce/loop/row', 'list-unstyled row g-7 mb-7 row-cols-1 md:row-cols-2 lg:row-cols-3 2xl:row-cols-4 bs-loop-grid', wc_get_loop_prop('name'));

  return preg_replace('/class="products\b/', 'class="products ' . esc_attr($row), $html, 1);
}
// Priority 1, before WooCommerce adds the categories to the loop start (woocommerce_maybe_show_product_subcategories)
add_filter('woocommerce_product_loop_start', 'bootscore_wc_loop_start', 1);

function bootscore_wc_loop_end($html) {
  $GLOBALS['bootscore_wc_in_loop'] = false;

  return $html;
}
add_filter('woocommerce_product_loop_end', 'bootscore_wc_loop_end');

function bootscore_wc_loop_col_class($classes) {
  if (!empty($GLOBALS['bootscore_wc_in_loop'])) {
    $classes = array_merge($classes, explode(' ', apply_filters('bootscore/class/woocommerce/loop/col', 'col bs-loop-grid-item', wc_get_loop_prop('name'))));
  }

  return $classes;
}
add_filter('woocommerce_post_class', 'bootscore_wc_loop_col_class');
add_filter('product_cat_class', 'bootscore_wc_loop_col_class');


/**
 * Card
 *
 * Products and categories: card > image > card-body > title, rating, price, button.
 */
function bootscore_wc_loop_card_open() {
  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/product/card', 'card h-100 text-center')) . '">';
}

function bootscore_wc_loop_card_body_open() {
  echo '<div class="' . esc_attr(apply_filters('bootscore/class/woocommerce/product/card/card-body', 'card-body')) . '">';
}

function bootscore_wc_loop_div_close() {
  echo '</div>';
}

// Products
add_action('woocommerce_before_shop_loop_item', 'bootscore_wc_loop_card_open', 5);
add_action('woocommerce_before_shop_loop_item_title', 'bootscore_wc_loop_card_body_open', 99);
add_action('woocommerce_after_shop_loop_item', 'bootscore_wc_loop_div_close', 98); // .card-body
add_action('woocommerce_after_shop_loop_item', 'bootscore_wc_loop_div_close', 99); // .card

// Categories
add_action('woocommerce_before_subcategory', 'bootscore_wc_loop_card_open', 5);
add_action('woocommerce_before_subcategory_title', 'bootscore_wc_loop_card_body_open', 99);
add_action('woocommerce_after_subcategory', 'bootscore_wc_loop_div_close', 98); // .card-body
add_action('woocommerce_after_subcategory', 'bootscore_wc_loop_div_close', 99); // .card


/**
 * Product link on the title (.stretched-link) instead of around image, title and price
 */
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);

remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action('woocommerce_shop_loop_item_title', 'bootscore_wc_loop_product_title', 10);

function bootscore_wc_loop_product_title() {
  global $product;

  if (!($product instanceof WC_Product)) {
    return;
  }

  $link = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product);

  echo '<h2 class="' . esc_attr(apply_filters('woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title')) . '">';
  echo '<a href="' . esc_url($link) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link ' . esc_attr(apply_filters('bootscore/class/woocommerce/loop/title-link', 'stretched-link fg-body text-decoration-none')) . '">' . get_the_title() . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
  echo '</h2>';
}


// Title size via the WooCommerce title class filter
function bootscore_wc_loop_product_title_classes($classes) {
  return $classes . ' ' . apply_filters('bootscore/class/woocommerce/loop/title', 'fs-lg mb-0');
}
add_filter('woocommerce_product_loop_title_classes', 'bootscore_wc_loop_product_title_classes');


/**
 * Category link on the title button (.stretched-link) instead of around image and title
 */
remove_action('woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10);
remove_action('woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10);

if (!function_exists('woocommerce_template_loop_category_title')) :
  function woocommerce_template_loop_category_title($category) {
    ?>
    <h2 class="woocommerce-loop-category__title <?= esc_attr(apply_filters('bootscore/class/woocommerce/loop/category-title', 'mt-auto mb-0')); ?>">
      <a href="<?= esc_url(get_term_link($category, 'product_cat')); ?>" class="<?= esc_attr(apply_filters('bootscore/class/woocommerce/loop/category-link', 'btn-solid theme-primary w-100 stretched-link')); ?>">
        <?php
        echo esc_html($category->name);

        if ($category->count > 0) {
          echo wp_kses_post(apply_filters('woocommerce_subcategory_count_html', ' <mark class="count badge bg-white fg-black">' . esc_html($category->count) . '</mark>', $category));
        }
        ?>
      </a>
    </h2>
    <?php
  }
endif;


/**
 * Product image with card-img-top class
 */
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'bootscore_loop_product_thumbnail', 10);

function bootscore_loop_product_thumbnail() {
  global $product;
  $classes = apply_filters('bootscore/class/woocommerce/product/loop/img', 'card-img-top');

  echo $product ? $product->get_image('woocommerce_thumbnail', array('class' => $classes)) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}


/**
 * Category image with card-img-top class
 */
remove_action('woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10);
add_action('woocommerce_before_subcategory_title', 'bootscore_loop_category_thumbnail', 10);

function bootscore_loop_category_thumbnail($category) {
  $classes      = apply_filters('bootscore/class/woocommerce/category/loop/img', 'card-img-top');
  $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);

  if ($thumbnail_id) {
    echo wp_get_attachment_image($thumbnail_id, 'woocommerce_thumbnail', false, array('class' => $classes));
  } else {
    echo '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr__('Placeholder', 'woocommerce') . '" class="' . esc_attr($classes) . '" />';
  }
}


/**
 * Add to cart button: container at the bottom of the card, above the stretched title link
 */
function bootscore_loop_add_to_cart_link($html) {
  $classes = apply_filters('bootscore/class/woocommerce/loop/add-to-cart', 'add-to-cart-container position-relative z-2 mt-auto');

  return '<div class="' . esc_attr($classes) . '">' . $html . '</div>';
}
add_filter('woocommerce_loop_add_to_cart_link', 'bootscore_loop_add_to_cart_link');


/**
 * Bootstrap button classes for the add to cart button
 */
function bootscore_loop_add_to_cart_args($args) {
  $button = apply_filters('bootscore/class/woocommerce/loop/add-to-cart/button', 'btn-solid theme-primary d-block');

  if (empty($args['class']) || !is_string($args['class'])) {
    $args['class'] = $button;
    return $args;
  }

  // Remove the WooCommerce .button class, it gets the .btn-outline styles from _wc-buttons.scss
  $classes       = array_diff(explode(' ', $args['class']), array('button'));
  $args['class'] = implode(' ', $classes) . ' ' . $button;

  return $args;
}
add_filter('woocommerce_loop_add_to_cart_args', 'bootscore_loop_add_to_cart_args');
