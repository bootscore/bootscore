/**
 * AJAX Cart JS - Bootscore v6.3.0
 *
 * Consists of 4 parts
 * 1. Handle Ajax Add to cart
 *   1.1 Single product page
 *   1.2 Product loop
 * 2. Quantity update Buttons
 * 3. General Offcanvas Cart behaviour
 * 4. Browser fixes
 *
 */


jQuery(function ($) {
  // 1 Handle AJAX Add To Cart

  // 1.1 Enable AJAX for single product pages
  $('form.cart').on('submit', function (e) {
    // Only apply to single product pages, not external products
    if (!$(this).closest('.product-type-external').length) {
      e.preventDefault();

      const form = $(this);
      var button = form.find('.single_add_to_cart_button');

      if (button.hasClass('disabled')) {
        return;
      }

      var formData = new FormData(form[0]);
      formData.append('add-to-cart', form.find('[name=add-to-cart]').val());

      // Add loading class
      button.addClass('loading');

      // Ajax action
      $.ajax({
        url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'ace_add_to_cart'),
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        complete: function (response) {
          button.removeClass('loading');
        },
        success: function (response) {
          if (response.error && response.product_url) {
            window.location = response.product_url;
            return;
          }

          // Redirect to cart option -- Not sure if necessary or it is right, because we have the "no cart" option in the theme?
          if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
            window.location = wc_add_to_cart_params.cart_url;
            return;
          }

          // Trigger event so themes can refresh other areas
          $(document.body).trigger('added_to_cart', [
            response.fragments,
            response.cart_hash,
            button
          ]);
        }
      });
    }
  });

  // Add loading spinner to add_to_cart_button // loop buttons are via a php filter in ajax-cart.php
  $('.single_add_to_cart_button').prepend('<div class="btn-loader"><span class="spinner-border spinner-border-sm"></span></div>');

  // 1.2 Enable AJAX add to cart on loop items
  $('a.ajax_add_to_cart[href*="?add-to-cart"]').on('click', function (e) {
    e.preventDefault();

    // Add new 'should_send_ajax_request.adding_to_cart' event to prevent standard WooCommerce Add To Cart AJAX request
    $(document.body).on('should_send_ajax_request.adding_to_cart', function (event, $button) {
      return false;
    });

    let button = $(this);
    let product_id = button.attr('href').split('=')[1];
    //parse as float
    let quantity = parseInt(button.attr('data-quantity')) || 1;
    let data = {
      "add-to-cart": product_id,
      "quantity": quantity,
    };

    // Interesting section here. it seems that the 'adding_to_cart' event removes the loading class from the button.
    // Therefore this approach is needed because it adds the loading after the removal. I'm not sure if this is the best way to do it.'
    // Function to add the 'loading' class to the a.ajax_add_to_cart button
    function addLoadingClass(e, fragments, cart_hash, button) {
      button.addClass('loading');
    }

    // Add new 'ajax_request_not_sent.adding_to_cart' event to trigger 'addLoadingClass' function
    $(document.body).on('ajax_request_not_sent.adding_to_cart', addLoadingClass);

    $(document.body).trigger('adding_to_cart', [button, data]);

    $.ajax({
      type: 'POST',
      url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'ace_add_to_cart'),
      data: data,
      complete: function (response) {
        button.addClass('added').removeClass('loading');

        // Remove 'should_send_ajax_request.adding_to_cart' and 'ajax_request_not_sent.adding_to_cart' events so they don't accumulate
        $(document.body).off('should_send_ajax_request.adding_to_cart');
        // Symptom of the addition of this event above..
        $(document.body).off('ajax_request_not_sent.adding_to_cart', addLoadingClass);
      },
      success: function (response) {
        if (response.error && response.product_url) { // I assume that it was a typo. if not please let me know
          console.log(response.error);
        } else {
          $(document.body).trigger('added_to_cart', [
            response.fragments,
            response.cart_hash,
            button
          ]);
        }
      }
    });
  });

  // 2. Quantity Update Buttons

  // Handling of quantity buttons in the ajax mini cart qty buttons
  $(document.body).on('click', '.item-quantity .plus, .item-quantity .minus', function () {
    let $qty = $(this).closest('.quantity').find('.qty'),
      currentVal = parseInt($qty.val()),
      max = parseInt($qty.attr('max')),
      min = parseInt($qty.attr('min')),
      step = $qty.attr('step'),
      nonce = $('input[name="bootscore_update_cart_nonce"]').val(),
      product_id = $(this).closest('.list-group-item').attr('data-bootscore_product_id');

    // Format values
    if (!currentVal || currentVal === '' || currentVal === 'NaN')
      currentVal = 0;
    if (max === '' || max === 'NaN') max = '';
    if (min === '' || min === 'NaN') min = 0;
    if (step === 'any' || step === '' || step === undefined || parseInt(step) === 'NaN') {
      step = 1;
    }

    // Perform the Quantity Update for Increment.
    if ($(this).is('.plus')) {
      currentVal += parseInt(step);
    } else {
      currentVal -= parseInt(step);
    }

    let wrap = $($(this)).closest('.woocommerce-mini-cart-item');
    bootscore_quantity_update(wrap, currentVal, nonce, product_id, max, step);
  });

  // Capture the old value on focus
  $('body').on('focus', '.item-quantity .quantity input', function () {
    $(this).data('prevValue', $(this).val());
  });

  // Trigger the function when the input is blurred
  $('body').on('blur', '.item-quantity .quantity input', function (e) {
    e.preventDefault();

    var input = $(this),
      max = input.attr('max'),
      currentValue = input.val(),
      intValue = Math.max(Math.ceil(parseInt(currentValue)), 0),
      nonce = $('input[name="bootscore_update_cart_nonce"]').val(),
      product_id = $(this).closest('.list-group-item').attr('data-bootscore_product_id');

    input.val(intValue);

    if (currentValue === '' || intValue === NaN) {
      input.val(1);
      intValue = 1;
    }

    let prevValue = input.data('prevValue');
    // If the new value is the same as the previous one, no need to re-render.
    if (prevValue === currentValue) {
      return false;
    }

    // Check if the submitted value is valid and return html error handling if not
    if (!this.checkValidity()) {
      this.reportValidity();
      return false;
    }

    // Update the previous value data attribute.
    input.data('prevValue', currentValue);

    // Perform the Quantity Update.
    let wrap = $(input).closest('.woocommerce-mini-cart-item');
    bootscore_quantity_update(wrap, intValue, nonce, product_id, max);
  });

  // Remove original handler:
  $(document.body).off('click', '.remove_from_cart_button');

  // Remove items with a modified update quantity method
  $('body').on('click', '.remove_from_cart_button', function (e) {
    e.preventDefault();

    var input = $(this),
        nonce = $('input[name="bootscore_update_cart_nonce"]').val(),
        product_id = $(this).closest('.list-group-item').attr('data-bootscore_product_id');

    console.log('remove_from_cart_button');

    // Perform the Quantity Update.
    let wrap = $(input).closest('.woocommerce-mini-cart-item');
    bootscore_quantity_update(wrap, 0, nonce, product_id);
  });

  function bootscore_quantity_update(wrap, number, nonce, product_id, max = -1, step = 1) {
    let key = $(wrap).data('key');
    let data = {
      action: 'bootscore_qty_update',
      key: key,
      number: number,
      step: step,
      nonce: nonce,
      product_id: product_id,
      max: max,
    };

    $.ajax({
      url: bootscoreTheme.ajaxurl,
      type: 'POST',
      data: data,
      beforeSend: function () {
        // Loader HTML
        let loader = `
                <div class="blockUI blockOverlay" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background-color: rgb(0, 0, 0); opacity: 0.6; cursor: wait; position: absolute;"></div>
                <div class="blockUI blockMsg blockElement" style="z-index: 1011; display: none; position: absolute; left: 199px; top: 52px;"></div>
                <div class="blockUI" style="display:none"></div>
              `;

        // Append the loader inside the item you click
        wrap.append(loader);
      },
      success: function (res) {
        let cart_res = res.data;
        if (res.success) {
          if (cart_res['force_fragments_refresh']) {
            $(document.body).trigger('wc_fragment_refresh');
            return;
          } else {
            console.log('in_update_js_sucess');
            $('#offcanvas-cart .cart-content span.woocommerce-Price-amount.amount').html(
              cart_res['total']
            );

            if (document.startViewTransition) {
              document.startViewTransition(() => updateCartFragments(cart_res));
            } else {
              updateCartFragments(cart_res);
            }

            // Dispatch the custom event
            $(document.body).trigger('qty_updated', [res]);
          }
        } else {
          wrap.find('.blockUI').remove();

          if (document.startViewTransition) {
            document.startViewTransition(() => updateCartFragments(cart_res));
          } else {
            updateCartFragments(cart_res);
          }

          $(document.body).trigger('qty_update_failed', [res]);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        setTimeout(function () {
          wrap.find('.blockUI').remove();
        }, 300);
        console.error(
          'AJAX request failed: ' + textStatus + ', ' + errorThrown
        );
      },
    });
  }

  function updateCartFragments(cart_res) {
    // Replace fragments
    $.each(cart_res['fragments_replace'], function (selector, content) {
      $(selector).replaceWith(content);
    });

    // Append fragments
    $.each(cart_res['fragments_append'], function (selector, content) {
      $(selector).append(content);
    });
  }


  // 3. General Offcanvas Cart behaviour

  // Open offcanvas when product is added to cart
  $(document.body).on('added_to_cart', function () {
    // This will throw an error if #offcanvas-cart doesn't exist
    $('#offcanvas-cart').offcanvas('show');
  });

  // Handle offcanvas closing - remove notices, so the cart is always empty on "reopening"
  // Use dispose to avoid flicker issues in navbar after closing the cart
  $('#offcanvas-cart').on('hidden.bs.offcanvas', function () {
    $('#offcanvas-cart .toast').each(function () {
      $(this).toast('dispose');
    });
  });

  // That function is not "filtered" at the moment, but should have no impact if there are no toasts in the offcanvas cart.
  $(document.body).on('added_to_cart qty_updated qty_update_failed wc_fragments_refreshed removed_from_cart', function () {
    // Timeout is needed because "on" the added_to_cart event the fragments are not yet updated.
    // wc_fragments_refreshed seems to not be triggered because the added_to_cart event already has the fragments retrieved by the ajax request.
    setTimeout(function () {
      const toastElList = document.querySelectorAll('#offcanvas-cart .toast');
      if (toastElList && toastElList.length > 0) {
        const toastList = [...toastElList].map(toastEl => {
          const toast = new bootstrap.Toast(toastEl, {
            animation: false,
            autohide: false,
          });
          toast.show();
          return toast; // Return the toast instance, not the result of show()
        });

        // Hide all toasts after 5 seconds with View Transition
        setTimeout(() => {
          if (document.startViewTransition) {
            document.startViewTransition(() => {
              toastList.map(toast => toast.hide());
            });
          } else {
            toastList.map(toast => toast.hide());
          }
        }, 5000);

      }
    }, 100); // Small delay to ensure fragments are processed
  });

  // 4. Browser Fixes
  // Chromium-specific fix for browser back button
  if (window.chrome) {
    $(window).on('pageshow', function (e) {
      if (e.originalEvent.persisted) {
        setTimeout(function () {
          $(document.body).trigger('wc_fragment_refresh');
        }, 100);
      }
    });
  }
});
