<?php

/**
 * Woocommerce functions and definitions
 *
 * @package Bootscore
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Load required files
 */
require_once('inc/wc-loop.php');
require_once('inc/wc-mini-cart.php');
require_once('inc/wc-qty-btn.php'); 
require_once('inc/wc-redirects.php'); 














// Woocommerce Templates
function bootscore_add_woocommerce_support() {
  add_theme_support('woocommerce');
}

add_action('after_setup_theme', 'bootscore_add_woocommerce_support');
// Woocommerce Templates END


// Woocommerce Lightbox
add_action('after_setup_theme', 'bootscore_wc_lightbox');

function bootscore_wc_lightbox() {
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
  
  // Enable fragments script (disabled in WooCommerce 7.8.0 if mini-cart widget is not in a widget position)
  // See https://developer.woocommerce.com/2023/05/24/woocommerce-7-8-beta-1-released/
  wp_enqueue_script('wc-cart-fragments');

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





// WooCommerce Breadcrumb
if (!function_exists('bs_woocommerce_breadcrumbs')) :
  add_filter('woocommerce_breadcrumb_defaults', 'bs_woocommerce_breadcrumbs');
  function bs_woocommerce_breadcrumbs() {
    return array(
      'delimiter'   => '',
      'wrap_before' => "<nav aria-label='breadcrumb' class='wc-breadcrumb breadcrumb-scroller mb-4 mt-2 py-2 px-3 bg-body-tertiary rounded'>
      <ol class='breadcrumb mb-0'>",
      'wrap_after'  => '</ol>
      </nav>',
      'before'      => '<li class="breadcrumb-item">',
      'after'       => '</li>',
      // Remove "Home" and add Fontawesome house icon (_wc_breadcrumb.scss)
      //'home'        => _x('Home', 'breadcrumb', 'woocommerce'),
      'home'        => ' ',
    );
  }
endif;
// WooCommerce Breadcrumb End


// Remove cross-sells at cart
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
// Remove cross-sells at cart End


// Remove CSS and/or JS for Select2 used by WooCommerce, see https://gist.github.com/Willem-Siebe/c6d798ccba249d5bf080.
add_action('wp_enqueue_scripts', 'bootscore_dequeue_stylesandscripts_select2', 100);

function bootscore_dequeue_stylesandscripts_select2() {
  if (class_exists('woocommerce')) {
    wp_dequeue_style('selectWoo');
    wp_deregister_style('selectWoo');

    wp_dequeue_script('selectWoo');
    wp_deregister_script('selectWoo');
  }
}
// Remove CSS and/or JS for Select2 END








// Correct hooked checkboxes in checkout
/**
 * Get the corrected terms for Woocommerce.
 *
 * @param string $html The original terms.
 *
 * @return string The corrected terms.
 */
function bootscore_wc_get_corrected_terms($html) {
  $doc = new DOMDocument();
  if (!empty($html) && $doc->loadHtml($html)) {
    $documentElement       = $doc->documentElement; // Won't find the right child-notes without that line. ads html and body tag as a wrapper
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
 * @param string $hookName The name of the hook to capture.
 *
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





