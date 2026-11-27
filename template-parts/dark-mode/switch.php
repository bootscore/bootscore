<?php

/**
 * Dark Mode Switch Template
 * Template Version: 7.0.0
 *
 * Output by the [bs-dark-mode] shortcode of the bs Dark Mode plugin.
 * Override it in the child theme: child-theme/template-parts/dark-mode/switch.php
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<label class="dark-mode-switch switch <?= esc_attr( apply_filters('bootscore/class/dark-mode/switch/label', '') ); ?>">
  <input class="bs-theme-switcher cursor-pointer" type="checkbox">
  <span class="visually-hidden"><?php esc_html_e('Toggle theme', 'bootscore'); ?></span>
</label>
