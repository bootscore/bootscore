jQuery(document).ready(function ($) {

    // Workaround icon in offcanvas toggler https://github.com/twbs/bootstrap/issues/33457
    $('.cart-toggler').append('<div class="toggler-overlay position-absolute top-0 end-0 bottom-0 start-0" data-bs-target="#offcanvas-cart"></div>');
    $('.user-toggler').addClass('position-relative');
    $('.user-toggler').append('<div class="toggler-overlay position-absolute top-0 end-0 bottom-0 start-0" data-bs-target="#offcanvas-user"></div>');


    // Single add to cart button
    $(".single_add_to_cart_button:not(.product_type_variable):not(.product_type_external):not(.product_type_grouped)").attr("data-bs-toggle","offcanvas").attr("data-bs-target","#offcanvas-cart");


    // Review Checkbox Products
    $('.comment-form-cookies-consent').addClass('form-check');
    $('#wp-comment-cookies-consent').addClass('form-check-input');
    $('.comment-form-cookies-consent label').addClass('form-check-label');
    // Review Checkbox End


    // Custom Checkout checkbox validation
    // .form-row was used for validation, .form-row is removed in Bootstrap 5, use .custom-validation instead
    if (typeof wc_checkout_params === 'undefined') {
        return false;
    }

    $.blockUI.defaults.overlayCSS.cursor = 'default';

    var wc_checkout_form = {
        updateTimer: false,
        dirtyInput: false,
        selectedPaymentMethod: false,
        xhr: false,
        $order_review: $('#order_review'),
        $checkout_form: $('form.checkout'),
        init: function () {
            $(document.body).bind('update_checkout', this.update_checkout);
            $(document.body).bind('init_checkout', this.init_checkout);

            // Inline validation
            this.$checkout_form.on('input validate change', 'input:checkbox', this.validate_field);
        },

        validate_field: function (e) {
            var $this = $(this),
                $parent = $this.closest('.custom-validation'),
                validated = true,
                validate_required = $parent.is('.validate-required'),
                event_type = e.type;

            if ('input' === event_type) {
                $parent.removeClass('woocommerce-invalid woocommerce-invalid-required-field woocommerce-validated'); // eslint-disable-line max-len
            }

            if ('validate' === event_type || 'change' === event_type) {

                if (validate_required) {
                    if ('checkbox' === $this.attr('type') && !$this.is(':checked')) {
                        $parent.removeClass('woocommerce-validated').addClass('woocommerce-invalid woocommerce-invalid-required-field');
                        validated = false;
                    } else if ($this.val() === '') {
                        $parent.removeClass('woocommerce-validated').addClass('woocommerce-invalid woocommerce-invalid-required-field');
                        validated = false;
                    }
                }

            }
        },

    };

    wc_checkout_form.init();
    // Custom Checkout checkbox validation End

}); // jQuery End
