<?php
/**
 * Show error messages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/notices/error.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $notices ) || ! is_array( $notices ) ) {
	return;
}

$multiple = count( $notices ) > 1;

?>
<div class="alert alert-danger alert-icon alert-danger-icon" role="alert" <?php echo $multiple ? '' : wc_get_notice_data_attr( $notices[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="wc-block-components-notice-banner__content">
		<?php if ( $multiple ) { ?>
			<p class="wc-block-components-notice-banner__summary"><?php esc_html_e( 'The following problems were found:', 'woocommerce' ); ?></p>
			<ul>
			<?php foreach ( $notices as $notice ) : ?>
				<li<?php echo wc_get_notice_data_attr( $notice ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php echo wc_kses_notice( $notice['notice'] ); ?>
				</li>
			<?php endforeach; ?>
			</ul>
			<?php
		} else {
			echo wc_kses_notice( $notices[0]['notice'] );
		}
		?>
	</div>
</div>
<?php
