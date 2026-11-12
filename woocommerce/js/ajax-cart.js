/**
 * AJAX Cart JS - Bootscore v7.0.0
 *
 * Consists of 4 parts
 * 1. Handle Ajax Add to cart
 *   1.1 Single product page
 *   1.2 Product loop
 * 2. Quantity update in the mini cart
 * 3. Drawer cart behaviour
 * 4. Browser fixes
 *
 */


jQuery(function ($) {

  // Loading state of add to cart buttons: .loading class and the .btn-loader spinner (hidden attribute, no CSS needed)
  function bootscoreButtonLoading(button, loading) {
    button.toggleClass('loading', loading);
    button.find('.btn-loader').prop('hidden', !loading);
  }

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

      bootscoreButtonLoading(button, true);

      // Ajax action
      $.ajax({
        url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'ace_add_to_cart'),
        data: formData,
        type: 'POST',
        processData: false,
        contentType: false,
        complete: function (response) {
          bootscoreButtonLoading(button, false);
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

  // Spinner for the single product button, loop buttons get it in PHP (wc-ajax-cart.php)
  $('.single_add_to_cart_button').prepend('<span class="btn-loader spinner-border" role="status" hidden><span class="visually-hidden">Loading...</span></span>');

  // 1.2 Enable AJAX add to cart on loop items
  // Function allows readding the handler also on dynamic elements after initial pageload
  // if a target is a jQuery object, use it directly, otherwise find it within the context
  function bootscoreInitLoopAjaxAddToCart(target, context = null) {
    const contextTarget = (context && context.jquery) ? context : $(context);
    let buttonTarget;

    if (target && target.jquery) {
      buttonTarget = target;
    } else if (typeof target === 'string' && context) {
      buttonTarget = contextTarget.find(target);
    } else {
      buttonTarget = $(target);
    }

    buttonTarget.not('.bs-ajax-cart-btn-initialized').addClass('bs-ajax-cart-btn-initialized').on('click.bootscoreAjaxAddToCart', function (e) {
      e.preventDefault();

      // Add new 'should_send_ajax_request.adding_to_cart' event to prevent standard WooCommerce Add To Cart AJAX request
      $(document.body).on('should_send_ajax_request.adding_to_cart', function (event, $button) {
        return false;
      });

      let button = $(this);

      const product_id = parseInt((button.attr('href') || '').match(/[?&]add-to-cart=(\d+)/)?.[1], 10) || null;      //parse as float
      let quantity = parseInt(button.attr('data-quantity'), 10) || 1;
      let data = {
        'add-to-cart': product_id,
        'quantity': quantity,
      };

      // Interesting section here. it seems that the 'adding_to_cart' event removes the loading class from the button.
      // Therefore this approach is needed because it adds the loading after the removal. I'm not sure if this is the best way to do it.'
      // Function to add the 'loading' class to the a.ajax_add_to_cart button
      function addLoadingClass(e, fragments, cart_hash, button) {
        bootscoreButtonLoading(button, true);
      }

      // Add new 'ajax_request_not_sent.adding_to_cart' event to trigger 'addLoadingClass' function
      $(document.body).on('ajax_request_not_sent.adding_to_cart', addLoadingClass);

      $(document.body).trigger('adding_to_cart', [button, data]);

      $.ajax({
        type: 'POST',
        url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'ace_add_to_cart'),
        data: data,
        complete: function (response) {
          bootscoreButtonLoading(button.addClass('added'), false);

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
  }

  window.bootscoreAjaxCart = window.bootscoreAjaxCart || {};
  window.bootscoreAjaxCart.initLoopAjaxAddToCart = bootscoreInitLoopAjaxAddToCart;
  window.bootscoreAjaxCart.initLoopAjaxAddToCart('a.ajax_add_to_cart[href*="add-to-cart="]');

  // 2. Quantity update in the mini cart

  // Cart item key and product ID come from the remove link, which WooCommerce outputs on every mini cart item
  function bootscoreCartItem(el) {
    const item = $(el).closest('.woocommerce-mini-cart-item');
    const removeLink = item.find('.remove_from_cart_button');

    return {
      item: item,
      key: removeLink.data('cart_item_key'),
      productId: removeLink.data('product_id'),
      nonce: $('input[name="bootscore_update_cart_nonce"]').val(),
    };
  }

  // Plus and minus buttons
  $(document.body).on('click', '.woocommerce-mini-cart-item .plus, .woocommerce-mini-cart-item .minus', function () {
    const $qty = $(this).closest('.quantity').find('.qty');
    let currentVal = parseInt($qty.val(), 10) || 0;
    let step = parseInt($qty.attr('step'), 10) || 1;

    currentVal = $(this).is('.plus') ? currentVal + step : currentVal - step;

    bootscore_quantity_update(bootscoreCartItem(this), currentVal);
  });

  // Capture the old value on focus
  $(document.body).on('focus', '.woocommerce-mini-cart-item .qty', function () {
    $(this).data('prevValue', $(this).val());
  });

  // Update on blur
  $(document.body).on('blur', '.woocommerce-mini-cart-item .qty', function () {
    const input = $(this);
    const currentValue = input.val();
    let intValue = Math.max(Math.ceil(parseInt(currentValue, 10)), 0);

    if (currentValue === '' || Number.isNaN(intValue)) {
      intValue = 1;
    }

    input.val(intValue);

    // Same value as before, no update
    if (input.data('prevValue') === currentValue) {
      return false;
    }

    // Invalid value, show the browser validation message
    if (!this.checkValidity()) {
      this.reportValidity();
      return false;
    }

    input.data('prevValue', currentValue);

    bootscore_quantity_update(bootscoreCartItem(this), intValue);
  });

  // Remove item: replace the WooCommerce handler with a quantity update to 0
  $(document.body).off('click', '.remove_from_cart_button');

  $(document.body).on('click', '.remove_from_cart_button', function (e) {
    e.preventDefault();

    bootscore_quantity_update(bootscoreCartItem(this), 0);
  });

  function bootscore_quantity_update(cartItem, number) {
    $.ajax({
      url: bootscoreTheme.ajaxurl,
      type: 'POST',
      data: {
        action: 'bootscore_qty_update',
        key: cartItem.key,
        number: number,
        nonce: cartItem.nonce,
      },
      beforeSend: function () {
        // Loader on the item
        cartItem.item.append('<div class="blockUI blockOverlay"></div>');
      },
      success: function (res) {
        const cart_res = res.data || {};

        // Full refresh, e.g. after WPML replaced the cart item or the cart is empty
        if (cart_res.force_fragments_refresh) {
          $(document.body).trigger('wc_fragment_refresh');
          return;
        }

        if (document.startViewTransition) {
          document.startViewTransition(() => updateCartFragments(cart_res, res.success, number));
        } else {
          updateCartFragments(cart_res, res.success, number);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        cartItem.item.find('.blockUI').remove();
        console.error('AJAX request failed: ' + textStatus + ', ' + errorThrown);
      },
    });
  }

  // Replace the fragments (mini cart, header badge, notices)
  function updateCartFragments(cart_res, success, number) {
    $.each(cart_res.fragments || {}, function (selector, content) {
      $(selector).replaceWith(content);
    });

    // Run the events here, because startViewTransition runs the update asynchronously
    if (success) {
      // Item removed: same event as WooCommerce, also stores the fragments for the next page load
      if (0 === number) {
        $(document.body).trigger('removed_from_cart', [cart_res.fragments, cart_res.cart_hash]);
      }
      $(document.body).trigger('qty_updated', [cart_res]);
    } else {
      $(document.body).trigger('qty_update_failed', [cart_res]);
    }
  }

  // 3. Drawer cart behaviour

  let toasts_to_be_hidden = null;

  function clearToastHideTimeout() {
    if (null !== toasts_to_be_hidden) {
      clearTimeout(toasts_to_be_hidden);
      toasts_to_be_hidden = null;
    }
  }

  // Open drawer when product is added to cart
  $(document.body).on('added_to_cart', function () {
    const drawerCart = document.getElementById('drawer-cart');
    if (drawerCart) {
      bootstrap.Drawer.getOrCreateInstance(drawerCart).show();
    }
  });

  // Remove notices when the drawer is closed, so they are gone on reopening
  // Bootstrap 6 events are native events, so addEventListener instead of jQuery .on()
  const drawerCartEl = document.getElementById('drawer-cart');
  if (drawerCartEl) {
    drawerCartEl.addEventListener('hidden.bs.drawer', function () {
      clearToastHideTimeout();

      drawerCartEl.querySelectorAll('.toast').forEach(function (toastEl) {
        bootstrap.Toast.getInstance(toastEl)?.dispose();
      });
    });
  }

  // Show notices as toasts in the drawer cart, hide them after 5 seconds
  $(document.body).on('added_to_cart qty_updated qty_update_failed wc_fragments_refreshed removed_from_cart', function () {
    // Clear any existing timeouts waiting for toast hide
    clearToastHideTimeout();

    // Timeout is needed because "on" the added_to_cart event the fragments are not yet updated.
    // wc_fragments_refreshed seems to not be triggered because the added_to_cart event already has the fragments retrieved by the ajax request.
    setTimeout(function () {
      const toastElList = document.querySelectorAll('#drawer-cart .toast');


      const toasts = [...toastElList].map((toastEl) => {
        const toast = bootstrap.Toast.getOrCreateInstance(toastEl, {
          animation: false,
          autohide: false,
        });
        toast.show();
        return {
          element: toastEl,
          instance: toast,
        };
      });

      // Hide all toasts after 5 seconds with View Transition
      toasts_to_be_hidden = setTimeout(() => {
        const hideToasts = () => {
          toasts.forEach(({ element, instance }) => {
            if (element && true === element.isConnected && instance) {
              instance.hide();
              instance.dispose();
            }
          });
        };

        if (document.startViewTransition) {
          document.startViewTransition(() => {
            hideToasts();
          });
        } else {
          hideToasts();
        }

        toasts_to_be_hidden = null;
      }, 5000);
    }, 100); // Small delay to ensure fragments are processed
  });

  // 4. Browser Fixes
  // Refresh fragments when restoring from back-forward cache (BFCache)
  $(window).on('pageshow', function (e) {
    if (e.originalEvent && true === e.originalEvent.persisted) {
      setTimeout(function () {
        $(document.body).trigger('wc_fragment_refresh');
      }, 100);
    }
  });
});
