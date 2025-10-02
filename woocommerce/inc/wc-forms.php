<?php

/**
 * WooCommerce Forms
 *
 * @package Bootscore
 * @version 6.0.4
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Remove CSS and/or JS for Select2
 * https://gist.github.com/ontiuk/72f6ac868d397678fb8b31df2b22e32a?permalink_comment_id=5320497#gistcomment-5320497
 */
function bootscore_dequeue_styles_and_scripts_select2() {
  // Dequeue and deregister Select2 and related scripts/styles
  wp_dequeue_style('select2');
  wp_deregister_style('select2');

  wp_dequeue_script('selectWoo');
  wp_deregister_script('selectWoo');

  // Dequeue and deregister the country select script. Needed for blockified classic checkout.
  // Bug with stripe checkout payment. See:
  // Issue: https://github.com/bootscore/bootscore/issues/918
  // PR: https://github.com/bootscore/bootscore/pull/919
  // Revert: https://github.com/bootscore/bootscore/pull/926
  //wp_dequeue_script('wc-country-select');
  //wp_deregister_script('wc-country-select');
}
add_action('wp_enqueue_scripts', 'bootscore_dequeue_styles_and_scripts_select2', 100);


/**
 * Checkout form fields
 */
/*
function bootscore_wc_bootstrap_form_field_args($args, $key, $value) {
  $args['input_class'][] = 'form-control';
  return $args;
}
add_filter('woocommerce_form_field_args', 'bootscore_wc_bootstrap_form_field_args', 10, 3);
*/

/**
 * Quantity input
 */
function bootscore_quantity_input_classes( $classes ) {
  $classes[] = 'form-control';
  return $classes;
}
add_filter( 'woocommerce_quantity_input_classes', 'bootscore_quantity_input_classes' );



/**
 * WooCommerce Bootstrap 5 form field integration
 */

/**
 * Add Bootstrap 5 classes to WooCommerce form fields.
 */
/*
if ( ! function_exists( 'bs_wc_form_field_args' ) ) {
    function bs_wc_form_field_args( $args, $key, $value ) {

        // Wrapper always gets margin
        $args['class'][] = 'mb-3';

        switch ( $args['type'] ) {
            case 'country':
                $args['class'][] = 'single-country';
                break;

            case 'state':
                $args['custom_attributes']['data-plugin']      = 'select2';
                $args['custom_attributes']['data-allow-clear'] = 'true';
                $args['custom_attributes']['aria-hidden']      = 'true';
                $args['input_class'][] = 'form-control';
                break;

            case 'checkbox':
                // Wrap label text in span for styling
                if ( '' !== $args['label'] ) {
                    $args['label'] = '<span class="form-check-label">' . $args['label'] . '</span>';
                }
                $args['label_class'][] = 'form-check';
                $args['input_class'][] = 'form-check-input';
                break;

            case 'select':
                $args['input_class'][] = 'form-select';
                $args['custom_attributes']['data-plugin']      = 'select2';
                $args['custom_attributes']['data-allow-clear'] = 'true';
                break;

            case 'radio':
                $args['label_class'][] = 'form-check-label';
                $args['input_class'][] = 'form-check-input';
                break;

            default:
                $args['input_class'][] = 'form-control';
        }

        return $args;
    }
    add_filter( 'woocommerce_form_field_args', 'bs_wc_form_field_args', 10, 3 );
}
*/




/**
 * WooCommerce Bootstrap 5 form field integration
 * - removes input-text everywhere
 * - ensures selects use form-select (not form-control)
 */
if ( ! function_exists( 'bs_wc_form_field_args' ) ) {
    function bs_wc_form_field_args( $args, $key, $value ) {

        // Wrapper always gets margin
        $args['class'][] = 'mb-3';

        // Reset to avoid WC defaults sneaking in
        $args['input_class'] = [];

        switch ( $args['type'] ) {
            case 'country':
                $args['class'][] = 'single-country';
                $args['input_class'][] = 'form-select';
                break;

            case 'state':
                $args['custom_attributes']['data-plugin']      = 'select2';
                $args['custom_attributes']['data-allow-clear'] = 'true';
                $args['custom_attributes']['aria-hidden']      = 'true';
                $args['input_class'][] = 'form-select'; // ✅ Bootstrap 5
                break;

            case 'checkbox':
                if ( '' !== $args['label'] ) {
                    $args['label'] = '<span class="form-check-label">' . $args['label'] . '</span>';
                }
                $args['label_class'][] = 'form-check';
                $args['input_class'][] = 'form-check-input';
                break;

            case 'select':
                $args['input_class'][] = 'form-select'; // ✅ Bootstrap 5
                $args['custom_attributes']['data-plugin']      = 'select2';
                $args['custom_attributes']['data-allow-clear'] = 'true';
                break;

            case 'radio':
                $args['label_class'][] = 'form-check-label';
                $args['input_class'][] = 'form-check-input';
                break;

            default:
                $args['input_class'][] = 'form-control';
        }

        return $args;
    }
    add_filter( 'woocommerce_form_field_args', 'bs_wc_form_field_args', 10, 3 );
}

/**
 * Cleanup: remove unwanted WC default classes from final field HTML.
 */

if ( ! function_exists( 'bs_wc_strip_input_text_class' ) ) {
    function bs_wc_strip_input_text_class( $field, $key, $args, $value ) {
        // Remove "input-text" everywhere
        $field = str_replace( 'input-text', '', $field );

        // For selects: remove form-control if it sneaks in
        if ( $args['type'] === 'select' || $args['type'] === 'country' || $args['type'] === 'state' ) {
            $field = str_replace( 'form-control', '', $field );
        }

        return $field;
    }
    add_filter( 'woocommerce_form_field', 'bs_wc_strip_input_text_class', 10, 4 );
}






/**
 * Adjust radio fields for Bootstrap 5.
 */
if ( ! function_exists( 'bs_wc_form_field_radio' ) ) {
    function bs_wc_form_field_radio( $field, $key, $args, $value ) {
        $wrapper_classes = 'form-check';
        $label_class     = 'form-check-label';

        // Remove duplicate label_class from first label if present
        if ( '' !== $args['label'] && ! empty( $args['label_class'] ) ) {
            $strpos = strpos( $field, $label_class );
            if ( false !== $strpos ) {
                $field = substr_replace( $field, '', $strpos, strlen( $label_class ) );
                $field = str_replace( 'class=""', '', $field );
            }
        }

        // Wrap each radio in .form-check
        $field = str_replace( '<input', "<div class=\"{$wrapper_classes}\"><input", $field );
        $field = str_replace( '</label>', '</label></div>', $field );

        // Remove closing div after the main label if exists
        if ( '' !== $args['label'] ) {
            $strpos = strpos( $field, '</label>' ) + strlen( '</label>' );
            $field  = substr_replace( $field, '', $strpos, strlen( '</div>' ) );
        }

        return $field;
    }
    add_filter( 'woocommerce_form_field_radio', 'bs_wc_form_field_radio', 10, 4 );
}

