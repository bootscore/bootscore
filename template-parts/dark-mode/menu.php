<?php
/*
 * Dark Mode Menu Template
 *
 * Output by the [bs-dark-mode] shortcode of the bs Dark Mode plugin.
 * Override it in the child theme: child-theme/template-parts/dark-mode/menu.php
 *
 * @package Bootscore
 * @version 7.0.0
*/


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Unique id, so the menu can be used more than once on a page (e.g. navbar and drawer)
$text_id = wp_unique_id('bs-theme-toggler-text-');

?>

<div class="dark-mode-menu">
  <button class="<?= esc_attr( apply_filters('bootscore/class/dark-mode/menu/button', 'btn-solid') ); ?> bs-theme-toggler" type="button" aria-expanded="false" data-bs-toggle="menu" aria-label="Toggle theme (auto)">
    <span class="light icon"><?php bootscore_icon('sun'); ?></span>
    <span class="dark icon"><?php bootscore_icon('moon'); ?></span>
    <span class="auto icon"><?php bootscore_icon('circle-half-stroke'); ?></span>
    <span id="<?= esc_attr($text_id); ?>" class="<?= esc_attr( apply_filters('bootscore/class/dark-mode/menu/button/text', 'visually-hidden') ); ?> bs-theme-toggler-text"><?= esc_html( apply_filters('bootscore/dark-mode/menu/button/text', __('Toggle theme', 'bootscore')) ); ?></span>
  </button>
  <div class="menu <?= esc_attr( apply_filters('bootscore/class/dark-mode/menu/menu', '') ); ?>" aria-labelledby="<?= esc_attr($text_id); ?>">
    <button type="button" class="menu-item" data-bs-theme-value="light" aria-pressed="false">
      <span class="light icon menu-item-icon"><?php bootscore_icon('sun'); ?></span>
      <span class="<?= esc_attr( apply_filters('bootscore/class/dark-mode/menu/item/description', 'menu-item-description') ); ?>"><?php esc_html_e('Light', 'bootscore'); ?></span>
    </button>
    <button type="button" class="menu-item" data-bs-theme-value="dark" aria-pressed="false">
      <span class="dark icon menu-item-icon"><?php bootscore_icon('moon'); ?></span>
      <span class="<?= esc_attr( apply_filters('bootscore/class/dark-mode/menu/item/description', 'menu-item-description') ); ?>"><?php esc_html_e('Dark', 'bootscore'); ?></span>
    </button>
    <button type="button" class="menu-item active" data-bs-theme-value="auto" aria-pressed="true">
      <span class="auto icon menu-item-icon"><?php bootscore_icon('circle-half-stroke'); ?></span>
      <span class="<?= esc_attr( apply_filters('bootscore/class/dark-mode/menu/item/description', 'menu-item-description') ); ?>"><?php esc_html_e('Auto', 'bootscore'); ?></span>
    </button>
  </div>
</div>