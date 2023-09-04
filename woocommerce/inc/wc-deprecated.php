<?php

/**
 * WooCommerce deprecated scripts
 *
 * @package Bootscore
 * @version 5.3.3
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/**
 * Remove cross-sells at cart
 */
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');


/**
 * Remove this when deleting terms.php
 * Correct hooked checkboxes in checkout
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
 * Remove this when deleting terms.php
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
