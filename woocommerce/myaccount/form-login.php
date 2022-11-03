<?php

/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 7.0.1
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

do_action('woocommerce_before_customer_login_form'); ?>

<?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>

  <div class="row" id="customer_login">

    <div class="col-md-6">

    <?php endif; ?>




    <h2><?php esc_html_e('Login', 'woocommerce'); ?></h2>

    <div class="card mt-3 mb-4">

      <div class="card-body">

        <form class="woocommerce-form woocommerce-form-login login" method="post">

          <?php do_action('woocommerce_login_form_start'); ?>

          <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="username"><?php esc_html_e('Username or email address', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text form-control" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine 
                                                                                                                                                                                                                                                                      ?>
          </p>
          <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
            <input class="woocommerce-Input woocommerce-Input--text input-text form-control" type="password" name="password" id="password" autocomplete="current-password" />
          </p>

          <?php do_action('woocommerce_login_form'); ?>

          <p class="form-check mb-3">
            <input name="rememberme" type="checkbox" class="form-check-input" id="rememberme" value="forever" />
            <label class="form-check-label" for="rememberme"><?php _e('Remember me', 'woocommerce'); ?></label>
          </p>

          <p class="form-row">
            <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
            <button type="submit" class="btn btn-outline-primary woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
          </p>
          <p class="woocommerce-LostPassword lost_password mb-0 mt-3">
            <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'woocommerce'); ?></a>
          </p>

          <?php do_action('woocommerce_login_form_end'); ?>

        </form>

      </div>

    </div>

    <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>

    </div>

    <div class="col-md-6">

      <h2><?php esc_html_e('Register', 'woocommerce'); ?></h2>

      <div class="card mt-3">

        <form method="post" class="card-body" <?php do_action('woocommerce_register_form_tag'); ?>>

          <?php do_action('woocommerce_register_form_start'); ?>

          <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
              <label for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
              <input type="text" class="woocommerce-Input woocommerce-Input--text input-text form-control" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine                                                                                                                                                                                                                                                                ?>
            </p>

          <?php endif; ?>

          <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text form-control" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine                                                                                                                                                                                                                                              ?>
          </p>

          <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-3">
              <label for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
              <input type="password" class="woocommerce-Input woocommerce-Input--text input-text form-control" name="password" id="reg_password" autocomplete="new-password" />
            </p>

          <?php else : ?>

            <p><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?></p>

          <?php endif; ?>

          <?php do_action('woocommerce_register_form'); ?>

          <p class="woocommerce-form-row form-row mb-0">
            <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
            <button type="submit" class="btn btn-outline-primary<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
          </p>

          <?php do_action('woocommerce_register_form_end'); ?>

        </form>

      </div>

    </div>

  </div>
<?php endif; ?>

<?php do_action('woocommerce_after_customer_login_form'); ?>