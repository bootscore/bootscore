/**
 * AJAX Login JS
 *
 * @package Bootscore
 * @version 6.2.0
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

        offCanvasWrapper.find(".woocommerce-form-login__submit").on("click", (e) => ajax_submit_login(e));
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

}); // jQuery End
