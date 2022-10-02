<?php

/**
 * Woocommerce functions and definitions
 *
 * @package Bootscore
 */


// Woocommerce Templates
function mytheme_add_woocommerce_support() {
  add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');
// Woocommerce Templates END


// Woocommerce Lightbox
add_action('after_setup_theme', 'bootscore');

function bootscore() {
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');
}
// Woocommerce Lightbox End


// Register Ajax Cart
if (!function_exists('register_ajax_cart')) :

  function register_ajax_cart() {
    require_once('ajax-cart/ajax-add-to-cart.php');
  }
  add_action('after_setup_theme', 'register_ajax_cart');

endif;
// Register Ajax Cart End


//Scripts and Styles
function wc_scripts() {

  // Get modification time. Enqueue files with modification date to prevent browser from loading cached scripts and styles when file content changes. 
  $modificated_WooCommerceJS = date('YmdHi', filemtime(get_template_directory() . '/woocommerce/js/woocommerce.js'));

  // WooCommerce JS
  wp_enqueue_script('woocommerce-script', get_template_directory_uri() . '/woocommerce/js/woocommerce.js', array(), $modificated_WooCommerceJS, true);

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}
add_action('wp_enqueue_scripts', 'wc_scripts');
//Scripts and styles End


// Minicart Header
if (!function_exists('bs_mini_cart')) :
  function bs_mini_cart($fragments) {

    ob_start();
    $count = WC()->cart->cart_contents_count; ?>
    <span class="cart-content">
      <?php if ($count > 0) { ?>
        <span class="cart-content-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light"><?php echo esc_html($count); ?></span><span class="cart-total ms-1 d-none d-md-inline"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
      <?php } ?>
    </span>

  <?php
    $fragments['span.cart-content'] = ob_get_clean();

    return $fragments;
  }
  add_filter('woocommerce_add_to_cart_fragments', 'bs_mini_cart');

endif;
// Minicart Header End


// WooCommerce Breadcrumb
if (!function_exists('bs_woocommerce_breadcrumbs')) :
  add_filter('woocommerce_breadcrumb_defaults', 'bs_woocommerce_breadcrumbs');
  function bs_woocommerce_breadcrumbs() {
    return array(
      'delimiter'   => ' &nbsp;&#47;&nbsp; ',
      'wrap_before' => '<nav class="breadcrumb mb-4 mt-2 bg-light py-2 px-3 small rounded" itemprop="breadcrumb">',
      'wrap_after'  => '</nav>',
      'before'      => '',
      'after'       => '',
      'home'        => _x('Home', 'breadcrumb', 'woocommerce'),
    );
  }
endif;
// WooCommerce Breadcrumb End


// Bootstrap Billing forms
function iap_wc_bootstrap_form_field_args($args, $key, $value) {

  $args['input_class'][] = 'form-control';
  return $args;
}
add_filter('woocommerce_form_field_args', 'iap_wc_bootstrap_form_field_args', 10, 3);
// Bootstrap Billing forms End


// Ship to a different address closed by default
add_filter('woocommerce_ship_to_different_address_checked', '__return_false');
// Ship to a different address closed by default End


// Remove cross-sells at cart
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
// Remove cross-sells at cart End


// Remove CSS and/or JS for Select2 used by WooCommerce, see https://gist.github.com/Willem-Siebe/c6d798ccba249d5bf080.
add_action('wp_enqueue_scripts', 'wsis_dequeue_stylesandscripts_select2', 100);

function wsis_dequeue_stylesandscripts_select2() {
  if (class_exists('woocommerce')) {
    wp_dequeue_style('selectWoo');
    wp_deregister_style('selectWoo');

    wp_dequeue_script('selectWoo');
    wp_deregister_script('selectWoo');
  }
}
// Remove CSS and/or JS for Select2 END


// Mini cart widget buttons
remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10);
remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20);

function my_woocommerce_widget_shopping_cart_button_view_cart() {
  echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="btn btn-outline-primary d-block mb-2">' . esc_html__('View cart', 'woocommerce') . '</a>';
}
function my_woocommerce_widget_shopping_cart_proceed_to_checkout() {
  echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="btn btn-primary d-block">' . esc_html__('Checkout', 'woocommerce') . '</a>';
}
add_action('woocommerce_widget_shopping_cart_buttons', 'my_woocommerce_widget_shopping_cart_button_view_cart', 10);
add_action('woocommerce_widget_shopping_cart_buttons', 'my_woocommerce_widget_shopping_cart_proceed_to_checkout', 20);
// Mini cart widget buttons End


// Cart empty message alert
remove_action('woocommerce_cart_is_empty', 'wc_empty_cart_message', 10);
add_action('woocommerce_cart_is_empty', 'custom_empty_cart_message', 10);

function custom_empty_cart_message() {
  $html  = '<div class="cart-empty alert alert-info">';
  $html .= wp_kses_post(apply_filters('wc_empty_cart_message', __('Your cart is currently empty.', 'woocommerce')));
  echo $html . '</div>';
}
// Cart empty message alert End


// Add card-img-top class to product loop
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'custom_loop_product_thumbnail', 10);
function custom_loop_product_thumbnail() {
  global $product;
  $size = 'woocommerce_thumbnail';
  $code = 'class=card-img-top';

  $image_size = apply_filters('single_product_archive_thumbnail_size', $size);

  echo $product ? $product->get_image($image_size, $code) : '';
}
// Add card-img-top class to product loop End


// Category loop button and badge
if (!function_exists('woocommerce_template_loop_category_title')) :
  function woocommerce_template_loop_category_title($category) {
  ?>
    <h2 class="woocommerce-loop-category__title btn btn-primary w-100 mb-0">
      <?php
      echo $category->name;

      if ($category->count > 0)
        echo apply_filters('woocommerce_subcategory_count_html', ' <mark class="count badge bg-white text-dark">' . $category->count . '</mark>', $category);
      ?>
    </h2>
<?php
  }
endif;
// Category loop button and badge End


// Correct hooked checkboxes in checkout
/**
 * Get the corrected terms for Woocommerce.
 *
 * @param  string $html The original terms.
 * @return string The corrected terms.
 */
function bootscore_wc_get_corrected_terms($html) {
  $doc = new DOMDocument();
  if (!empty($html) && $doc->loadHtml($html)) {
    $documentElement = $doc->documentElement; // Won't find the right child-notes without that line. ads html and body tag as a wrapper
    $somethingWasCorrected = false;
    foreach ($documentElement->childNodes[0]->childNodes as $mainNode) {
      if ($mainNode->childNodes->length && strpos($mainNode->getAttribute("class"), "form-row") !== false) {
        if (strpos($mainNode->getAttribute("class"), "required") !== false) {
          $mainNode->setAttribute("class", "form-row validate-required"); // You could try to keep the original class and only add the string, but I think that could ruin the design
        } else {
          $mainNode->setAttribute("class", "form-row woocommerce-validated");
        }
        $nodesLabel = $mainNode->getElementsByTagName("label");
        if ($nodesLabel->length) {
          $nodesLabel[0]->setAttribute("class", "woocommerce-form__label woocommerce-form__label-for-checkbox checkbox form-check display-inline-block d-inline-block");
        }
        $nodesInput = $mainNode->getElementsByTagName("input");
        if ($nodesInput->length) {
          $nodesInput[0]->setAttribute("class", "woocommerce-form__input woocommerce-form__input-checkbox input-checkbox form-check-input");
        }
        $somethingWasCorrected = true;
      }
    }
    if ($somethingWasCorrected) {
      return $doc->saveHTML();
    } else {
      return $html;
    }
  } else {
    //error maybe return $html?
  }
}

/**
 * Capture the output of a hook.
 *
 * @param  string $hookName The name of the hook to capture.
 * @return string The output of the hook.
 */
function bootscore_wc_capture_hook_output($hookName) {
  ob_start();
  do_action($hookName);
  $hookContent = ob_get_contents();
  ob_end_clean();
  return $hookContent;
}
// Correct hooked checkboxes in checkout End


// Redirect to my-account if offcanvas login failed
add_action('woocommerce_login_failed', 'bootscore_redirect_on_login_failed', 10, 0);
function bootscore_redirect_on_login_failed() {
  // Logout user doesn't have session, we need this to display notices
  if (!WC()->session->has_session()) {
    WC()->session->set_customer_session_cookie(true);
  }
  wp_redirect(wp_validate_redirect(wc_get_page_permalink('myaccount')));
  exit;
}
// Redirect to my-account if offcanvas login failed End


// Redirect to home on logout
add_action('wp_logout', 'bootscore_redirect_after_logout');
function bootscore_redirect_after_logout() {
  wp_redirect(home_url());
  exit();
}
// Redirect to home on logout End


// Redirect to my-account after (un)sucessful registration
add_action('wp_loaded', 'bootscore_redirect_after_registration', 999);
function bootscore_redirect_after_registration() {
  $nonce_value = isset($_POST['_wpnonce']) ? wp_unslash($_POST['_wpnonce']) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
  $nonce_value = isset($_POST['woocommerce-register-nonce']) ? wp_unslash($_POST['woocommerce-register-nonce']) : $nonce_value; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
  if (isset($_POST['register'], $_POST['email']) && wp_verify_nonce($nonce_value, 'woocommerce-register')) {
    if (!WC()->session->has_session()) {
      WC()->session->set_customer_session_cookie(true);
    }
    wp_redirect(wp_validate_redirect(wc_get_page_permalink('myaccount')));
    exit;
  }
}
// Redirect to my-account after (un)sucessful registration End
