<?php
/*
 * Dark Mode Switch Template
 *
 * Output by the [bs-dark-mode] shortcode of the bs Dark Mode plugin.
 * Override it in the child theme: child-theme/template-parts/dark-mode/sc-switch.php
 *
 * @package Bootscore
 * @version 7.0.0
*/


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<label class="dark-mode-switch switch <?= esc_attr( apply_filters('bootscore/class/dark-mode/switch/label', '') ); ?>">
  <input class="bs-theme-switcher cursor-pointer" type="checkbox">
  <span class="visually-hidden"><?php esc_html_e('Toggle theme', 'bootscore'); ?></span>
</label>
