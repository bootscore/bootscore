<?php
/*
    https://aceplugins.com/ajax-add-to-cart-button-on-the-product-page-woocommerce/
*/



// JS for AJAX Add to Cart handling
function bootscore_product_page_ajax_add_to_cart_js() {
    ?><script type="text/javascript" charset="UTF-8">
    jQuery(function($) {

        $('form.cart').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            form.block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });

            var formData = new FormData(form[0]);
            formData.append('add-to-cart', form.find('[name=add-to-cart]').val());

            // Ajax action.
            $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'ace_add_to_cart'),
                data: formData,
                type: 'POST',
                processData: false,
                contentType: false,
                complete: function(response) {
                    response = response.responseJSON;

                    if (!response) {
                        return;
                    }

                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    }

                    // Redirect to cart option
                    if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
                        window.location = wc_add_to_cart_params.cart_url;
                        return;
                    }

                    var $thisbutton = form.find('.single_add_to_cart_button'); //

                    // Trigger event so themes can refresh other areas.
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);

                    // Remove existing notices
                    $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();

                    // Add new notices
                    form.closest('.product').before(response.fragments.notices_html)

                    form.unblock();
                }
            });
        });
    });

</script><?php
}
add_action( 'wp_footer', 'bootscore_product_page_ajax_add_to_cart_js' );
// JS for AJAX Add to Cart handling End


// Add to cart handler
function bootscore_ajax_add_to_cart_handler() {
	WC_Form_Handler::add_to_cart_action();
	WC_AJAX::get_refreshed_fragments();
}
add_action( 'wc_ajax_ace_add_to_cart', 'bootscore_ajax_add_to_cart_handler' );
add_action( 'wc_ajax_nopriv_ace_add_to_cart', 'bootscore_ajax_add_to_cart_handler' );

// Remove WC Core add to cart handler to prevent double-add
remove_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ), 20 );
// Add to cart handler End


// Add fragments for notices
function bootscore_ajax_add_to_cart_add_fragments( $fragments ) {
	$all_notices  = WC()->session->get( 'wc_notices', array() );
	$notice_types = apply_filters( 'woocommerce_notice_types', array( 'error', 'success', 'notice' ) );

    // Comment or delete this to hide Alerts
    ob_start();
	foreach ( $notice_types as $notice_type ) {
		if ( wc_notice_count( $notice_type ) > 0 ) {
			wc_get_template( "notices/{$notice_type}.php", array(
				'notices' => array_filter( $all_notices[ $notice_type ] ),
			) );
		}
	}
	$fragments['notices_html'] = ob_get_clean();
    // Comment or delete this to hide Alerts
    
	wc_clear_notices();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'bootscore_ajax_add_to_cart_add_fragments' );
// Add fragments for notices End
