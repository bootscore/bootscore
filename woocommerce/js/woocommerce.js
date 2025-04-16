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
      step = $qty.attr('step');

    // Format values
    if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
    if (max === '' || max === 'NaN') max = '';
    if (min === '' || min === 'NaN') min = 0;
    if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;

    if (typeof wc_add_to_cart_params === 'undefined' || wc_add_to_cart_params.ajax_url === '') {
      // Change the value
      if ($(this).is('.plus')) {
        if (max && currentVal >= max) {
          $qty.val(max);
        } else {
          $qty.val((currentVal + parseFloat(step)).toFixed(step.getDecimals()));
        }
      } else {
        if (min && currentVal <= min) {
          $qty.val(min);
        } else if (currentVal > 0) {
          $qty.val((currentVal - parseFloat(step)).toFixed(step.getDecimals()));
        }
      }
      $qty.trigger('change');
    }
  });


  //ajax login

  const { __ } = wp.i18n;

  let offcanvasUser = document.getElementById('offcanvas-user')
  offcanvasUser.addEventListener('show.bs.offcanvas', loadOffcanvasMyAccount);

  function loadOffcanvasMyAccount() {
    contentForm();
    offcanvasUser.removeEventListener('show.bs.offcanvas', loadOffcanvasMyAccount);
  }

  // Function to load account menu content via AJAX
  function contentForm(successMessage = "") {
    $.ajax({
      url: bootscoreTheme.ajaxurl,
      type: 'POST',
      data: {
        action: 'load_account_menu_html',
      },
      complete: function (response) {
        response = response.responseJSON;

        if (!response) {
          return;
        }

        $(".account_sidebar_content").html(response.data['menu_html']);

        // If login was successful, display the success message
        if(successMessage !== "") {
          $(".woocommerce-MyAccount-content").find(".woocommerce-notices-wrapper").html(successMessage);
        }

        $(".woocommerce-form-login__submit").on("click", (e) => ajax_submit_login(e));

      }
    });
  }

  function ajax_submit_login(e) {
    e.preventDefault();

    let form = $("#ajax_login_form_container");
    let username = form.find('#username').val().trim();
    let password = form.find('#password').val().trim();
    let nonce = form.find('input[name="woocommerce-login-nonce"]').val();
    let rememberme = form.find('input.woocommerce-form__input-checkbox').is(":checked");



    if (!username) {
      $(".woocommerce-notices-wrapper").html(
          "<div class='woocommerce-error'>" +
          "<strong>" + __('Error:', 'bootscore') + "</strong> " + __('Username or email is required.', 'bootscore') +
          "</div>"
      );
      return;
    }

    if (!password) {
      $(".woocommerce-notices-wrapper").html(
          "<div class='woocommerce-error'><strong>" +
          __('Error:', 'bootscore') +
          "</strong> " +
          __('Password is required.', 'bootscore') +
          "</div>"
      );
      return;
    }



    $(".woocommerce-notices-wrapper").empty();

    $.ajax({
      url: bootscoreTheme.ajaxurl,
      type: 'POST',
      data: {
        action: 'ajax_login',
        username: username,
        password: password,
        woocommerceLoginNonce: nonce,
        rememberme: rememberme
      },
      success: function (response) {
        if (!response) return;

        if (response.data.isLoggedIn) {
          //We did it this way to avoid race-conditions on the user session

          contentForm(
              "<div class='woocommerce-message'>" + response.data.message + "</div>"
          );
          setTimeout(function () {
            $(document.body).trigger('wc_fragment_refresh');
          }, 100);
        }
      },
      error: function (response) {
        data = response.responseJSON.data;

        let outputHtml;
        if (response.status === 400 || response.status === 401) {
          outputHtml = data.message;
        } else {
          outputHtml = __('Technical error', 'bootscore') + ' ' + '(' + response.status + ')';
        }
        $(".woocommerce-notices-wrapper").html("" +
            "<div class='woocommerce-error'>" + outputHtml + "</div>"
        );
      }
    });

  }
}); // jQuery End