<?php

/**
 * WooCommerce floating labels
 *
 * Text inputs, selects and textareas with a visible label become Bootstrap floating labels:
 * label and field are wrapped in span.form-floating. Checkboxes, radios, hidden inputs and
 * screen reader labels stay as they are.
 *
 * Covered: woocommerce_form_field() (checkout, addresses, order notes), the account forms (login, register,
 * lost and reset password, account details), the shipping calculator, the review form and the variation selects.
 *
 * Turn off with add_filter('bootscore/woocommerce/floating-labels', '__return_false').
 *
 * @package Bootscore
 * @version 7.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Wraps label and field in span.form-floating
 *
 * Matches a label with for="id", optionally followed by WooCommerce's span.woocommerce-input-wrapper,
 * and the field with the same id. The wrapper stays (WooCommerce's scripts use it), span.form-floating goes inside it,
 * so a field description in the wrapper stays below the field.
 *
 * @param string $html Form HTML.
 * @return string Form HTML with floating labels.
 */
function bootscore_wc_floating_labels($html) {
  if (!is_string($html) || false === strpos($html, '<label') || !apply_filters('bootscore/woocommerce/floating-labels', true)) {
    return $html;
  }

  return preg_replace_callback(
    '/(<label\b[^>]*\bfor="([^"]+)"[^>]*>.*?<\/label>)(\s*<span class="woocommerce-input-wrapper">)?\s*(<(input|select|textarea)\b[^>]*\bid="\2"[^>]*>(?:.*?<\/\5>)?)/s',
    function ($m) {
      [$all, $label, $id, $wrapper, $field, $tag] = $m;

      // Screen reader labels (e.g. address line 2) and labels around checkboxes stay
      if (preg_match('/class="[^"]*\b(screen-reader-text|visually-hidden|checkbox)\b/', $label)) {
        return $all;
      }

      $type = 'input' === $tag && preg_match('/\btype="([^"]+)"/', $field, $t) ? $t[1] : 'text';

      if (in_array($type, array('checkbox', 'radio', 'hidden', 'submit', 'button', 'file', 'range'), true)) {
        return $all;
      }

      // :placeholder-shown needs a placeholder, an existing one shows on focus
      if ('select' !== $tag && !preg_match('/\bplaceholder="[^"]+"/', $field)) {
        $field = preg_replace('/\splaceholder="[^"]*"/', '', $field);
        $field = preg_replace('/^<' . $tag . '\b/', '<' . $tag . ' placeholder=" "', $field);
      }

      // .form-control on the field itself, except password inputs: WooCommerce wraps them in span.password-input,
      // which becomes the .form-control (woocommerce.js)
      if ('password' !== $type && !preg_match('/class="[^"]*\bform-control\b/', $field)) {
        $field = preg_match('/\bclass="/', $field)
          ? preg_replace('/\bclass="/', 'class="form-control ', $field, 1)
          : preg_replace('/^<' . $tag . '\b/', '<' . $tag . ' class="form-control"', $field);
      }

      // The label isn't a .form-label inside the floating wrapper
      $label = preg_replace('/(class="[^"]*)\bform-label\b\s*/', '$1', $label);

      return trim($wrapper) . '<span class="form-floating ' . esc_attr(apply_filters('bootscore/class/woocommerce/floating-labels', 'd-block')) . '">' . $label . $field . '</span>';
    },
    $html
  );
}


/**
 * woocommerce_form_field(): checkout, addresses, order notes
 *
 * Fields with a screen reader label and a placeholder instead (address line 2) get a visible label,
 * so they have the same height as the floating fields next to them.
 */
add_filter('woocommerce_form_field', function ($field) {
  if (is_string($field) && apply_filters('bootscore/woocommerce/floating-labels', true)
    && preg_match('/<label\b[^>]*class="[^"]*\bscreen-reader-text\b/', $field)
    && preg_match('/\bplaceholder="[^"]+"/', $field)) {
    $field = preg_replace('/(<label\b[^>]*class="[^"]*)\bscreen-reader-text\b/', '$1', $field, 1);
    $field = preg_replace('/\bplaceholder="[^"]+"/', 'placeholder=" "', $field, 1);
  }

  return bootscore_wc_floating_labels($field);
}, 99);


/**
 * Templates with fields in their own markup
 */
function bootscore_wc_floating_labels_templates() {
  return apply_filters('bootscore/woocommerce/floating-labels/templates', array(
    'myaccount/form-login.php',
    'myaccount/form-lost-password.php',
    'myaccount/form-reset-password.php',
    'myaccount/form-edit-account.php',
    'global/form-login.php',
    'cart/shipping-calculator.php',
  ));
}

add_action('woocommerce_before_template_part', function ($template_name) {
  if (in_array($template_name, bootscore_wc_floating_labels_templates(), true)) {
    ob_start();
  }
});

add_action('woocommerce_after_template_part', function ($template_name) {
  if (in_array($template_name, bootscore_wc_floating_labels_templates(), true)) {
    echo bootscore_wc_floating_labels(ob_get_clean()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce template output
  }
});


/**
 * Review form: author, email and review text. After the theme's classes (wc-single-product-reviews.php)
 */
add_filter('woocommerce_product_review_comment_form_args', function ($args) {
  foreach (array('author', 'email') as $field) {
    if (isset($args['fields'][$field])) {
      $args['fields'][$field] = bootscore_wc_floating_labels($args['fields'][$field]);
    }
  }

  if (!empty($args['comment_field'])) {
    $args['comment_field'] = bootscore_wc_floating_labels($args['comment_field']);
  }

  return $args;
}, 20);


/**
 * Variation selects: the label from the table head moves into the floating wrapper next to the select
 */
add_filter('woocommerce_dropdown_variation_attribute_options_html', function ($html, $args) {
  if (!apply_filters('bootscore/woocommerce/floating-labels', true) || empty($args['attribute'])) {
    return $html;
  }

  $id    = !empty($args['id']) ? $args['id'] : sanitize_title($args['attribute']);
  $label = wc_attribute_label($args['attribute'], $args['product'] ?? '');

  return '<span class="form-floating ' . esc_attr(apply_filters('bootscore/class/woocommerce/floating-labels', 'd-block')) . '"><label for="' . esc_attr($id) . '">' . esc_html($label) . '</label>' . $html . '</span>';
}, 20, 2);
