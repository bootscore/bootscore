/**
 * AJAX Login JS - Bootscore v6.3.0
 */


jQuery(function ($) {

  const {
    __
  } = wp.i18n;

  let offcanvasUser = document.getElementById('offcanvas-user')
  if (!offcanvasUser) return;

  offcanvasUser.addEventListener('show.bs.offcanvas', loadOffcanvasMyAccount);

  function loadOffcanvasMyAccount() {
    // Add loader on initial load of the form / account menu
    $("#offcanvas-user").addClass("ajax-login"); // 👉 Add class while loading

    accountContent();
    // Be sure to only load it once after opening the offcanvas
    offcanvasUser.removeEventListener('show.bs.offcanvas', loadOffcanvasMyAccount);
  }

  // Function to load account menu content via AJAX
  function accountContent(successMessage = "") {
    $.ajax({
      url: bootscoreTheme.ajaxurl,
      type: 'POST',
      data: {
        action: 'load_account_menu_html',
      },
      complete: function (response) {

        response = response.responseJSON;

        if (!response) return;
        let offCanvasWrapper = $(".my-offcanvas-account");

        offCanvasWrapper.html(response.data['menu_html']);

        if (successMessage !== "") {
          offCanvasWrapper.find(".woocommerce-notices-wrapper").html(successMessage);
        }
        // Remove loader if retrieval of the account form/menu was successfull
        $("#offcanvas-user").removeClass("ajax-login");

        // Initialize the login form if the user is not logged in
        if(!response.data['is_logged_in']) {
          offCanvasWrapper.find(".woocommerce-form-login__submit").on("click", (e) => ajax_submit_login(e));
          init_pw_hide_show_toggle(offCanvasWrapper);
        }
      }
    });
  }

  function ajax_submit_login(e) {
    e.preventDefault();

    let form = $(".my-offcanvas-account form.login");
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
      $(".my-offcanvas-account").find(".woocommerce-notices-wrapper").html(
        "<div class='woocommerce-error'>" + "<strong>" + __('Error:', 'bootscore') + "</strong> " +
        inlineErrorMessage + "</div>");
      return;
    } else {
      $(".my-offcanvas-account").find(".woocommerce-notices-wrapper").empty();
    }

    // Add loader on submit of the form
    $("#offcanvas-user").addClass("ajax-login");

    $.ajax({
      url: bootscoreTheme.ajaxurl,
      type: 'POST',
      data: {
        action: 'ajax_login',
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
        $(".my-offcanvas-account").find(".woocommerce-notices-wrapper").html(
          "<div class='woocommerce-error'>" + outputHtml + "</div>"
        );

        // Remove loader on error
        $("#offcanvas-user").removeClass("ajax-login");
      }
    });
  }

  function init_pw_hide_show_toggle( offCanvasWrapper ) {

    offCanvasWrapper.find('.woocommerce-Input[type="password"]').each(function () {
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

    offCanvasWrapper.find('.show-password-input').on('click', function (event) {
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
