<?php

/**
 * WooCommerce forms
 *
 * Form fields are styled in SCSS (bootscore-woocommerce/_wc-forms.scss), except where a filter is available.
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Remove Select2 (selectWoo), use native selects instead
 *
 * Also before the footer scripts print: the Classic Checkout block (and other blocks or shortcodes rendered in the content)
 * enqueue WooCommerce's scripts while the page renders, after wp_enqueue_scripts. country-select.js only uses Select2
 * when selectWoo is loaded, otherwise the selects stay native.
 *
 * @link https://gist.github.com/ontiuk/72f6ac868d397678fb8b31df2b22e32a?permalink_comment_id=5320497#gistcomment-5320497
 */
function bootscore_wc_remove_select2() {
  foreach (['select2'] as $style) {
    wp_dequeue_style($style);
    wp_deregister_style($style);
  }

  foreach (['selectWoo', 'select2', 'wc-select2'] as $script) {
    wp_dequeue_script($script);
    wp_deregister_script($script);
  }
}
add_action('wp_enqueue_scripts', 'bootscore_wc_remove_select2', 100);
add_action('wp_footer', 'bootscore_wc_remove_select2', 1);


/**
 * Quantity input, also used in the mini cart outside .woocommerce
 */
function bootscore_wc_quantity_input_classes($classes) {
  $classes[] = 'form-control';
  $classes[] = 'text-center';
  return $classes;
}
add_filter('woocommerce_quantity_input_classes', 'bootscore_wc_quantity_input_classes');


/**
 * Product search form (product-searchform.php, e.g. the classic product search widget) like the theme's searchform.php:
 * input group, form-control, outline button with the search icon. The button text stays for screen readers.
 */
function bootscore_wc_product_search_form($form) {
  if (!is_string($form) || '' === $form) {
    return $form;
  }

  $tags = new WP_HTML_Tag_Processor($form);

  while ($tags->next_tag()) {
    if ('FORM' === $tags->get_tag()) {
      $tags->add_class('input-group');
    } elseif ('LABEL' === $tags->get_tag() || ('INPUT' === $tags->get_tag() && 'hidden' === $tags->get_attribute('type'))) {
      // Screen reader label before and hidden post_type input after: ignored for the input group's rounded corners
      $tags->add_class('input-group-ignore');
    } elseif ($tags->has_class('search-field')) {
      $tags->add_class('form-control');
    } elseif ('BUTTON' === $tags->get_tag()) {
      $tags->remove_class('wp-element-button');
      $tags->add_class('input-group-btn ' . apply_filters('bootscore/class/search/button', 'btn-outline theme-secondary'));
    }
  }

  return preg_replace_callback('/(<button[^>]*>)(.*?)(<\/button>)/s', function ($m) {
    return $m[1] . bootscore_icon('search', false) . '<span class="visually-hidden">' . esc_html(trim(wp_strip_all_tags($m[2]))) . '</span>' . $m[3];
  }, $tags->get_updated_html(), 1);
}
add_filter('get_product_search_form', 'bootscore_wc_product_search_form');
