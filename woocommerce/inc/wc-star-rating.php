<?php

/**
 * WooCommerce star rating
 *
 * WooCommerce draws the stars with its own icon font (CSS), which is not loaded.
 * Replaced by 5 SVG icons from inc/icons.php, rounded to half stars.
 * Used everywhere WooCommerce shows a rating: loop, single product, reviews, widgets.
 * The wrapper div.star-rating keeps role="img" and the aria-label "Rated x out of 5".
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


function bootscore_wc_star_rating_html($html, $rating, $count) {
  $rating = round((float) $rating * 2) / 2; // Half stars
  $full   = (int) floor($rating);
  $half   = ($rating - $full) >= 0.5 ? 1 : 0;
  $empty  = 5 - $full - $half;

  $stars  = str_repeat(bootscore_icon('star', false), $full);
  $stars .= str_repeat(bootscore_icon('star-half', false), $half);
  $stars .= str_repeat(bootscore_icon('star-empty', false), max(0, $empty));

  return '<span class="' . esc_attr(apply_filters('bootscore/class/woocommerce/star-rating', 'd-inline-flex gap-1 fg-body')) . '" aria-hidden="true">' . $stars . '</span>';
}
add_filter('woocommerce_get_star_rating_html', 'bootscore_wc_star_rating_html', 10, 3);


/**
 * Wrapper div.star-rating from wc_get_rating_html() as flex container.
 * Otherwise the inline-flex stars sit on the text baseline of a line box and look too high next to text.
 */
function bootscore_wc_star_rating_wrapper($html) {
  return str_replace('class="star-rating"', 'class="star-rating ' . esc_attr(apply_filters('bootscore/class/woocommerce/star-rating/wrapper', 'd-flex')) . '"', $html);
}
add_filter('woocommerce_product_get_rating_html', 'bootscore_wc_star_rating_wrapper');
