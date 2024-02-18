jQuery(document).ready(function($) {
    jQuery(document).on('change', '.widget_shopping_cart_content .quantity input', function(){
        var value = $(this).val();
        if (!isNaN(value) && value > 0) {
            var holder = $(this).closest('.woocommerce-mini-cart-item');
            if (holder.length) {
                var removeButton = $(holder).find('.remove_from_cart_button');
                if (removeButton.length) {
                    var itemHash = removeButton.attr('data-cart_item_key');
                    if (itemHash) {

                        holder.block({
                            message: null,
                            overlayCSS: {
                                opacity: 0.6
                            }
                        });

                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: cart_widget_qty_ajax.ajax_url,
                            data: {
                                action: 'change_widget_cart_qty',
                                hash: itemHash,
                                quantity: value,
                            },
                            success: function(response) {
                                if (response.data.subtotal_html) {
                                    var miniCart = $(holder).closest('.widget_shopping_cart_content');
                                    if (miniCart.length) {
                                        var subTotalHolder = $(miniCart).find('.woocommerce-mini-cart__total .woocommerce-Price-amount.amount');
                                        if (subTotalHolder) {
                                            subTotalHolder.replaceWith(response.data.subtotal_html);
                                        }
                                    }
                                }
                            },
                            complete: function() {
                                holder.css( 'opacity', '1' ).unblock();
                            },
                        });
                    }
                }

            }
        }
    });
});