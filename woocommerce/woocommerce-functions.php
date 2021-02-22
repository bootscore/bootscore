<?php
/**
 * Woocommerce functions and definitions
 *
 * @package Bootscore
 */


// Woocommerce Templates
function mytheme_add_woocommerce_support() {
add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );
// Woocommerce Templates END


// Woocommerce Lightbox
add_action( 'after_setup_theme', 'bootscore' );

function bootscore() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
// Woocommerce Lightbox End


// Register Ajax Cart
function register_ajax_cart(){
    require_once('ajax-cart/ajax-add-to-cart.php');
}
add_action( 'after_setup_theme', 'register_ajax_cart' );
// Register Ajax Cart End


//Scripts and Styles
function wc_scripts() {

	// WooCommerce CSS	
	wp_enqueue_style( 'woocommerce', get_template_directory_uri() . '/woocommerce/css/woocommerce-style.css');
	
	// WooCommerce JS
	wp_enqueue_script( 'woocommerce-script', get_template_directory_uri() . '/woocommerce/js/woocommerce.js', array(), '20151215', true );
   
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wc_scripts' );
//Scripts and styles End


// Minicart Header
if ( ! function_exists( 'bs_mini_cart' ) ) :

    function bs_mini_cart( $fragments ) {

        ob_start();
        $count = WC()->cart->cart_contents_count;
        ?><span class="cart-content"><?php
        if ( $count > 0 ) {
            ?>
        <span class="cart-content-count badge bg-danger"><?php echo esc_html( $count ); ?></span><span class="cart-total ms-1 d-none d-md-inline"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
        <?php            
        }
            ?></span><?php

        $fragments['span.cart-content'] = ob_get_clean();

        return $fragments;
    }
    add_filter( 'woocommerce_add_to_cart_fragments', 'bs_mini_cart' );

endif;
// Minicrt Header End


// Forms

/**
 * Filter hook function monkey patching form classes
 * Author: Adriano Monecchi http://stackoverflow.com/a/36724593/307826
 *
 * @param string $args Form attributes.
 * @param string $key Not in use.
 * @param null   $value Not in use.
 *
 * @return mixed
 */
if ( ! function_exists( 'bootscore_wc_form_field_args' ) ) {
	function bootscore_wc_form_field_args( $args, $key, $value = null ) {
		// Start field type switch case.
		switch ( $args['type'] ) {
			/* Targets all select input type elements, except the country and state select input types */
			case 'select':
				// Add a class to the field's html element wrapper - woocommerce
				// input types (fields) are often wrapped within a <p></p> tag.
				$args['class'][] = 'form-group';
				// Add a class to the form input itself.
				$args['input_class']       = array( 'form-control', 'input-lg' );
				$args['label_class']       = array( 'control-label' );
				$args['custom_attributes'] = array(
					'data-plugin'      => 'select2',
					'data-allow-clear' => 'true',
					'aria-hidden'      => 'true',
					// Add custom data attributes to the form input itself.
				);
				break;
			// By default WooCommerce will populate a select with the country names - $args
			// defined for this specific input type targets only the country select element.
			case 'country':
				$args['class'][]     = 'form-group single-country';
				$args['label_class'] = array( 'control-label' );
				break;
			// By default WooCommerce will populate a select with state names - $args defined
			// for this specific input type targets only the country select element.
			case 'state':
				// Add class to the field's html element wrapper.
				$args['class'][] = 'form-group';
				// add class to the form input itself.
				$args['input_class']       = array( '', 'input-lg' );
				$args['label_class']       = array( 'control-label' );
				$args['custom_attributes'] = array(
					'data-plugin'      => 'select2',
					'data-allow-clear' => 'true',
					'aria-hidden'      => 'true',
				);
				break;
			case 'password':
			case 'text':
			case 'email':
			case 'tel':
			case 'number':
				$args['class'][]     = 'form-group';
				$args['input_class'] = array( 'form-control', 'input-lg' );
				$args['label_class'] = array( 'control-label' );
				break;
			case 'textarea':
				$args['input_class'] = array( 'form-control', 'input-lg' );
				$args['label_class'] = array( 'control-label' );
				break;
			case 'checkbox':
				$args['label_class'] = array( 'custom-control custom-checkbox' );
				$args['input_class'] = array( 'custom-control-input', 'input-lg' );
				break;
			case 'radio':
				$args['label_class'] = array( 'custom-control custom-radio' );
				$args['input_class'] = array( 'custom-control-input', 'input-lg' );
				break;
			default:
				$args['class'][]     = 'form-group';
				$args['input_class'] = array( 'form-control', 'input-lg' );
				$args['label_class'] = array( 'control-label' );
				break;
		} // end switch ($args).
		return $args;
	}
}

if ( ! is_admin() && ! function_exists( 'wc_review_ratings_enabled' ) ) {
	/**
	 * Check if reviews are enabled.
	 *
	 * Function introduced in WooCommerce 3.6.0., include it for backward compatibility.
	 *
	 * @return bool
	 */
	function wc_reviews_enabled() {
		return 'yes' === get_option( 'woocommerce_enable_reviews' );
	}

	/**
	 * Check if reviews ratings are enabled.
	 *
	 * Function introduced in WooCommerce 3.6.0., include it for backward compatibility.
	 *
	 * @return bool
	 */
	function wc_review_ratings_enabled() {
		return wc_reviews_enabled() && 'yes' === get_option( 'woocommerce_enable_review_rating' );
	}
}

// Forms end


// WooCommerce Breadcrumb
if ( ! function_exists( 'bs_woocommerce_breadcrumbs' ) ) :
    add_filter( 'woocommerce_breadcrumb_defaults', 'bs_woocommerce_breadcrumbs' );
    function bs_woocommerce_breadcrumbs() {
        return array(
                'delimiter'   => ' &nbsp;&#47;&nbsp; ',
                'wrap_before' => '<nav class="breadcrumb mb-4 mt-2 bg-light py-1 px-2 rounded" itemprop="breadcrumb">',
                'wrap_after'  => '</nav>',
                'before'      => '',
                'after'       => '',
                'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
            );
    }
endif;
// WooCommerce Breadcrumb End


// Optional Telephone
if (!function_exists( 'evolution_phone_no_pflicht' ) ) :

function evolution_phone_no_pflicht( $address_fields ) {
    $address_fields['billing_phone']['required'] = false;
    return $address_fields;
}
add_filter( 'woocommerce_billing_fields', 'evolution_phone_no_pflicht', 10, 1 );
endif;
// Optional Telephone End


// Bootstrap Billing forms
function iap_wc_bootstrap_form_field_args ($args, $key, $value) { 
  
  $args['input_class'][] = 'form-control'; 
  return $args; 
}
add_filter('woocommerce_form_field_args','iap_wc_bootstrap_form_field_args', 10, 3);
// Bootstrap Billing forms End


// Ship to a different address closed by default
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );
// Ship to a different address closed by default End


// Remove cross-sells at cart
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
// Remove cross-sells at cart End


// Remove CSS and/or JS for Select2 used by WooCommerce, see https://gist.github.com/Willem-Siebe/c6d798ccba249d5bf080.
add_action( 'wp_enqueue_scripts', 'wsis_dequeue_stylesandscripts_select2', 100 );

function wsis_dequeue_stylesandscripts_select2() {
    if ( class_exists( 'woocommerce' ) ) {
        wp_dequeue_style( 'selectWoo' );
        wp_deregister_style( 'selectWoo' );

        wp_dequeue_script( 'selectWoo');
        wp_deregister_script('selectWoo');
    } 
} 
// Remove CSS and/or JS for Select2 END


// Mini cart widget buttons
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );

function my_woocommerce_widget_shopping_cart_button_view_cart() {
    echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="btn btn-outline-primary d-block mb-2">' . esc_html__( 'View cart', 'woocommerce' ) . '</a>';
}
function my_woocommerce_widget_shopping_cart_proceed_to_checkout() {
    echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="btn btn-primary d-block">' . esc_html__( 'Checkout', 'woocommerce' ) . '</a>';
}
add_action( 'woocommerce_widget_shopping_cart_buttons', 'my_woocommerce_widget_shopping_cart_button_view_cart', 10 );
add_action( 'woocommerce_widget_shopping_cart_buttons', 'my_woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
// Mini cart widget buttons End


// Cart empty message alert
remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
add_action( 'woocommerce_cart_is_empty', 'custom_empty_cart_message', 10 );

function custom_empty_cart_message() {
    $html  = '<div class="cart-empty alert alert-info">';
    $html .= wp_kses_post( apply_filters( 'wc_empty_cart_message', __( 'Your cart is currently empty.', 'woocommerce' ) ) );
    echo $html . '</div>';
}
// Cart empty message alert End



