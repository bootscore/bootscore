jQuery(function ($) {

  // Single-product Tabs
  // First item active
  $('.wc-tabs .nav-item:first-child a').addClass('active');

  // Set active class to nav-link
  $('body').on('click', '.wc-tabs li a', function (e) {
    e.preventDefault();
    var $tab = $(this);
    var $tabs_wrapper = $tab.closest('.wc-tabs-wrapper, .woocommerce-tabs');
    var $tabs = $tabs_wrapper.find('.wc-tabs');

    $tabs.find('li a').removeClass('active');
    $tabs_wrapper.find('.wc-tab, .panel:not(.panel .panel)').hide();

    $tab.closest('li a').addClass('active');
    $tabs_wrapper.find($tab.attr('href')).show();
  });
  // Single-product Tabs End

  // WC Quantity Input
  if (!String.prototype.getDecimals) {
    String.prototype.getDecimals = function () {
      var num = this,
        match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
      if (!match) {
        return 0;
      }
      return Math.max(0, (match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0));
    };
  }
  // Quantity "plus" and "minus" buttons
  $(document.body).on('click', '.plus, .minus', function () {
    var $qty = $(this).closest('.quantity').find('.qty'),
      currentVal = parseFloat($qty.val()),
      max = parseFloat($qty.attr('max')),
      min = parseFloat($qty.attr('min')),
      step = $qty.attr('step'),
			nonce = $('input[name="bootscore_update_cart_nonce"]').val();

    // Format values
    if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
    if (max === '' || max === 'NaN') max = '';
    if (min === '' || min === 'NaN') min = 0;
    if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;

    // Change the value
    if ($(this).is('.plus')) {
      if (max && currentVal >= max) {
        $qty.val(max);
      } else {
        $qty.val((currentVal + parseFloat(step)).toFixed(step.getDecimals()));
      }

	    // Perform the Quantity Update for Increment.
      var currentValWithPlusStep = (currentVal + parseFloat(step)).toFixed(step.getDecimals());
      bootscore_quantity_update_buttons($(this), currentValWithPlusStep, step, nonce);
    } else {
      if (min && currentVal <= min) {
        $qty.val(min);
      } else if (currentVal > 0) {
        $qty.val((currentVal - parseFloat(step)).toFixed(step.getDecimals()));
      }
			// Perform the Quantity Update for Decrement.
			var currentValWithMinusStep = (currentVal - parseFloat(step)).toFixed(step.getDecimals())
			bootscore_quantity_update_buttons($(this), currentValWithMinusStep, step, nonce)
    }

    // Trigger change event
    $qty.trigger('change');
  });

	// Implement the change event.
	function bootscore_quantity_update_buttons(el, number, step, nonce) {
		var wrap = $(el).closest(".woocommerce-mini-cart-item"),
			key = $(wrap).data("key"),
			data = {
			action: "bootscore_qty_update",
			key: key,
			number: number,
			step: step,
			nonce: nonce,
		};

		// Perform Ajax Call.
		bootscore_perform_ajax_call( wrap, data );

	}

	jQuery(document).ready(function($) {
		// Trigger the function on page load.
		handleQuantityChange();

		// Trigger the function when the quantity input changes.
		$(document).on('change', '.quantity input[type="number"]', function() {
			handleQuantityChange();
		});

		// Trigger the function when the minus button is clicked.
		$(document).on('click', '.quantity .minus', function() {
			handleQuantityChange();
		});

		// Function to handle quantity change minus button enable/disable.
		function handleQuantityChange() {
			$('.mini_cart_item').each(function() {
				var quantityInput = $(this).find('.quantity input[type="number"]'),
					minusButton = $(this).find('.quantity .minus');

				if (parseInt(quantityInput.val()) <= 1) {
					minusButton.prop('disabled', true);
				} else {
					minusButton.prop('disabled', false); 
				}
			});
		}

		// Trigger the function when the input is blurred.
		$("body").on("blur", ".item-quantity .quantity input", function () {
			var input = $(this),
				currentValue = input.val(),
				intValue = Math.max(Math.ceil(parseFloat(currentValue)), 1),
				nonce = $('input[name="bootscore_update_cart_nonce"]').val();

			input.val(intValue);

			if (currentValue === '' || parseInt(currentValue) === '0' || intValue === NaN) {
				input.val( 1 );
				intValue = 1;
			}

			// If the new value is the same as the previous one, no need to re-render.
			if (input.data('prevValue') === currentValue) {
				return false;
			}

			// Update the previous value data attribute.
			input.data('prevValue', currentValue);

			// Perform the Quantity Update.
			bootscore_quantity_update_input_blue(input, intValue, nonce);
    });

		// Update cart on input blur.
		function bootscore_quantity_update_input_blue( input, number, nonce ) {
			var wrap = $( input ).closest(".woocommerce-mini-cart-item"),
				key = $(wrap).data("key"),
				data = {
				action: "bootscore_qty_update",
				key: key,
				number: number,
				nonce: nonce
			};

			// Perform Ajax Call.
			bootscore_perform_ajax_call( wrap, data );
		}
	});

	// Handle Ajax Call.
	function bootscore_perform_ajax_call(wrap, data) {
    $.ajax({
			url: '/wp-admin/admin-ajax.php',
			type: 'POST',
			data: data,
			beforeSend: function() {
				// Loader HTML
				let loader = `
					<div class="blockUI blockOverlay" style="z-index: 1000; border: none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background-color: rgb(0, 0, 0); opacity: 0.6; cursor: wait; position: absolute;"></div>
					<div class="blockUI blockMsg blockElement" style="z-index: 1011; display: none; position: absolute; left: 199px; top: 52px;"></div>
					<div class="blockUI" style="display:none"></div>
				`;

				// Append the loader inside the item you click
				wrap.append(loader);
			},
			success: function(res) {
				setTimeout(function() {
					wrap.find('.blockUI').remove();
				}, 300);

					let cart_res = res.data;
					$(".cart-content span.woocommerce-Price-amount.amount").html(cart_res["total"]);
					wrap.find(".bootscore-custom-render-total").html(cart_res["item_price"]);
					wrap.find('span.qty_text').html(cart_res['item_qty']);
					$('.cart-content-count').html(cart_res['total_items']);
					$('.woocommerce-mini-cart__total.total .amount').html(cart_res['total']);
					$('.cart-footer .amount').html(cart_res['total']);
					$('.cart-content .cart-total').html(cart_res['total']);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				setTimeout(function() {
					wrap.find('.blockUI').remove();
				}, 300);
				console.error("AJAX request failed: " + textStatus + ", " + errorThrown);
			}
    });
}
}); // jQuery End