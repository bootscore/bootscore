<?php

/**
 * The template for displaying preloader
 * Template Version: 7.0.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<div id="preloader" class="<?= esc_attr( apply_filters('bootscore/class/preloader/bg', 'bg-body d-flex align-items-center justify-content-center position-fixed top-0 end-0 bottom-0 start-0 z-toast') ); ?>">
  <div id="status" class="<?= esc_attr( apply_filters('bootscore/class/preloader/spinner', 'spinner-border fg-primary fs-2xl') ); ?>" role="status">
    <?php do_action( 'bootscore_preloader_status' ); ?>
    <span class="visually-hidden"><?php _e('Loading...', 'bootscore'); ?></span>
  </div>
</div>
