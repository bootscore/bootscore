jQuery(function ($) {

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
                    $(".woocommerce-notices-wrapper").html(successMessage);
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

        if (!username) {
            $(".woocommerce-notices-wrapper").html(
                "<div class='woocommerce-error'><strong>" +
                __('Error:', 'bootscore') +
                "</strong> " +
                __('Username or email is required.', 'bootscore') +
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
                woocommerceLoginNonce: nonce
            },
            success: function (response) {
                if (!response) return;

                if (response.data.isLoggedIn) {
                    //We did it this way to avoid race-conditions on the user session

                    contentForm(
                        "<div class='woocommerce-message'>" + response.data.message + "</div>"
                    );
                    $(document.body).trigger('wc_fragment_refresh');
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

});
