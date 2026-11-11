/**
 * AJAX Login JS - Bootscore v7.0.0
 */


jQuery(function ($) {

  const {
    __
  } = wp.i18n;

  const drawerAccount = document.getElementById('drawer-account');
  if (!drawerAccount) return;

  const $content = $(drawerAccount).find('.drawer-account-content');

  // Loader from wc-ajax-login.php, toggled with the hidden attribute (no CSS needed)
  function showLoader(show) {
    const loader = drawerAccount.querySelector('.ajax-login-loader');
    if (loader) {
      loader.hidden = !show;
    }
  }

  // Load the account form or menu once, when the drawer opens for the first time
  // Bootstrap 6 events are native events, so addEventListener instead of jQuery .on()
  drawerAccount.addEventListener('show.bs.drawer', function () {
    showLoader(true);
    accountContent();
  }, { once: true });

  // Function to load account menu content via AJAX
  function accountContent(successMessage = "") {
    $.ajax({
      url: bootscoreTheme.ajaxurl,
      type: 'POST',
      data: {
        action: 'bootscore_load_account',
      },
      complete: function (response) {

        response = response.responseJSON;

        if (!response) return;

        $content.html(response.data.menu_html);

        if (successMessage !== '') {
          $content.find('.woocommerce-notices-wrapper').html(successMessage);
        }

        showLoader(false);

        // Initialize the login form if the user is not logged in
        if (!response.data.isLoggedIn) {
          $content.find('.woocommerce-form-login__submit').on('click', (e) => ajax_submit_login(e));
          init_pw_hide_show_toggle($content);
        }
      }
    });
  }

  function ajax_submit_login(e) {
    e.preventDefault();

    let form = $content.find('form.login');
    let username = form.find('#username').val().trim();
    let password = form.find('#password').val().trim();
    let nonce = form.find('input[name="woocommerce-login-nonce"]').val();
    let rememberMe = form.find('input.woocommerce-form__input-checkbox').is(":checked");

    let inlineErrorMessage = '';

    if (!username && !password) {
      inlineErrorMessage = __('Username or email and password are required.', 'bootscore');
    } else if (!username) {
      inlineErrorMessage = __('Username or email is required.', 'bootscore');
    } else if (!password) {
      inlineErrorMessage = __('Password is required.', 'bootscore');
    }

    if (inlineErrorMessage !== '') {
      $content.find('.woocommerce-notices-wrapper').html(
        "<div class='woocommerce-error'>" + "<strong>" + __('Error:', 'bootscore') + "</strong> " +
        inlineErrorMessage + "</div>");
      return;
    } else {
      $content.find('.woocommerce-notices-wrapper').empty();
    }

    showLoader(true);

    $.ajax({
      url: bootscoreTheme.ajaxurl,
      type: 'POST',
      data: {
        action: 'bootscore_ajax_login',
        username: username,
        password: password,
        woocommerceLoginNonce: nonce,
        rememberMe: rememberMe
      },
      success: function (response) {
        if (!response) return;

        if (response.data.isLoggedIn) {
          accountContent(
            "<div class='woocommerce-message'>" + response.data.message + "</div>"
          );

          setTimeout(function () {
            $(document.body).trigger('wc_fragment_refresh');
          }, 100);
        }
      },
      error: function (response) {

        let data = response.responseJSON.data;

        let outputHtml;
        if (response.status === 400 || response.status === 401) {
          outputHtml = data.message;
        } else {
          outputHtml = __('Technical error', 'bootscore') + ' ' + '(' + response.status + ')';
        }
        $content.find('.woocommerce-notices-wrapper').html(
          "<div class='woocommerce-error'>" + outputHtml + "</div>"
        );

        showLoader(false);
      }
    });
  }

  function init_pw_hide_show_toggle($wrapper) {

    $wrapper.find('.woocommerce-Input[type="password"]').each(function () {
      const describedBy = $(this).attr('id');
      const input = $(this);

      // Create the button
      const button = $('<button type="button" class="show-password-input" aria-label="' +
          woocommerce_params.i18n_password_show +
          '" aria-describedBy="' +
          describedBy +
          '"></button>');

      // Wrap the input in a span and append the button to that span
      input.wrap('<span class="password-input"></span>');
      input.parent().append(button);
    });

    // Form adorn classes and icon (woocommerce.js)
    bootscorePasswordInputs($wrapper);

    $wrapper.find('.show-password-input').on('click', function (event) {
      event.preventDefault();

      if ($(this).hasClass('display-password')) {
        $(this).removeClass('display-password');
        $(this).attr(
            'aria-label',
            woocommerce_params.i18n_password_show
        );
      } else {
        $(this).addClass('display-password');
        $(this).attr(
            'aria-label',
            woocommerce_params.i18n_password_hide
        );
      }
      if ($(this).hasClass('display-password')) {
        $(this)
            .siblings(['input[type="password"]'])
            .prop('type', 'text');
      } else {
        $(this)
            .siblings('input[type="text"]')
            .prop('type', 'password');
      }

      $(this).siblings('input').focus();
    });
  }

}); // jQuery End
