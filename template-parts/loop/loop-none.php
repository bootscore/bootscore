<?php
/**
 * Template part for displaying no content
 * Template Version: 7.0.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;
?>


<div class="alert theme-info mb-5" role="alert">
  <?= bootscore_alert_content(esc_html__('No content found.', 'bootscore')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
