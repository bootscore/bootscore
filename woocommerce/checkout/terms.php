<?php

/**
 * Checkout terms and conditions area.
 *
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

if (apply_filters('woocommerce_checkout_show_terms', true) && function_exists('wc_terms_and_conditions_checkbox_enabled'))
{
  function getCorrectedTerms($html) {
    $doc = new DOMDocument();
    if ( !empty($html) && $doc->loadHtml($html))
    {
      $documentElement = $doc->documentElement; // Won't find the right child-notes without that line. ads html and body tag as a wrapper
      $somethingWasCorrected = false;
      foreach ($documentElement->childNodes[0]->childNodes as $mainNode) {
        if($mainNode->childNodes->length && strpos($mainNode->getAttribute("class"), "form-row") !== false)
        {
          if(strpos($mainNode->getAttribute("class"), "required") !== false)
          {
            $mainNode->setAttribute("class", "form-row validate-required"); // You could try to keep the original class and only add the string, but I think that could ruin the design
          }
          else
          {
            $mainNode->setAttribute("class", "form-row woocommerce-validated");
          }
          $nodesLabel = $mainNode->getElementsByTagName("label");
          if ($nodesLabel->length) {
            $nodesLabel[0]->setAttribute("class", "woocommerce-form__label woocommerce-form__label-for-checkbox checkbox form-check display-inline-block d-inline-block");
          }
          $nodesInput = $mainNode->getElementsByTagName( "input" );
          if ($nodesInput->length ) {
            $nodesInput[0]->setAttribute("class", "woocommerce-form__input woocommerce-form__input-checkbox input-checkbox form-check-input");
          }
          $somethingWasCorrected = true;
        }
      }
      if($somethingWasCorrected) {
        return $doc->saveHTML();
      }
      else
      {
        return $html;
      }
    }
    else
    {
      //error maybe return $html?
    }
  }
  
  function captureHookOutput($hookName) {
    ob_start(); // start capturing output.
    do_action($hookName);
    $hookContent = ob_get_contents(); // the actions output will now be stored in the variable as a string!
    ob_end_clean(); // never forget this or you will keep capturing output.
    return $hookContent;
  }
  echo getCorrectedTerms(captureHookOutput('woocommerce_checkout_before_terms_and_conditions'));
  ?>
  <div class="woocommerce-terms-and-conditions-wrapper">
    <?php
    /**
     * Terms and conditions hook used to inject content.
     *
     * @since 3.4.0.
     * @hooked wc_checkout_privacy_policy_text() Shows custom privacy policy text. Priority 20.
     * @hooked wc_terms_and_conditions_page_content() Shows t&c page content. Priority 30.
     */

    echo getCorrectedTerms(captureHookOutput('woocommerce_checkout_terms_and_conditions'));
    ?>
    <?php if (wc_terms_and_conditions_checkbox_enabled()) : ?>
      <p class="form-row validate-required">
        <span class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox form-check">
          <input type="checkbox" id="terms" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox form-check-input" name="terms" <?php checked(apply_filters('woocommerce_terms_is_checked_default', isset($_POST['terms'])), true); // WPCS: input var ok, csrf ok. ?> />
          <label class="woocommerce-terms-and-conditions-checkbox-text form-check-label" for="terms"><?php wc_terms_and_conditions_checkbox_text(); ?>&nbsp;<span class="required">*</span></label>
        </span>
        <input type="hidden" name="terms-field" value="1" />
      </p>
    <?php endif; ?>
  </div>
  <?php
    echo getCorrectedTerms(captureHookOutput('woocommerce_checkout_after_terms_and_conditions'));
}
