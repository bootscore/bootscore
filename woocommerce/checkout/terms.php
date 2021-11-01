<?php

/**
 * Checkout terms and conditions area.
 *
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

if (apply_filters('woocommerce_checkout_show_terms', true) && function_exists('wc_terms_and_conditions_checkbox_enabled')) {

  echo bootscore_wc_get_corrected_terms(bootscore_wc_capture_hook_output('woocommerce_checkout_before_terms_and_conditions'));
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

    echo bootscore_wc_get_corrected_terms(bootscore_wc_capture_hook_output('woocommerce_checkout_terms_and_conditions'));
    ?>
    <?php if (wc_terms_and_conditions_checkbox_enabled()) : ?>
      <p class="form-row validate-required">
        <span class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox form-check">
          <input type="checkbox" id="terms" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox form-check-input" name="terms" <?php checked(apply_filters('woocommerce_terms_is_checked_default', isset($_POST['terms'])), true); // WPCS: input var ok, csrf ok. 
                                                                                                                                                          ?> />
          <label class="woocommerce-terms-and-conditions-checkbox-text form-check-label" for="terms"><?php wc_terms_and_conditions_checkbox_text(); ?>&nbsp;<span class="required">*</span></label>
        </span>
        <input type="hidden" name="terms-field" value="1" />
      </p>
    <?php endif; ?>
  </div>
<?php
  echo bootscore_wc_get_corrected_terms(bootscore_wc_capture_hook_output('woocommerce_checkout_after_terms_and_conditions'));
}
