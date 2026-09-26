<?php

/**
 * The sidebar containing the main widget area
 * Template Version: 7.0.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


if (!apply_filters('bootscore/sidebar/has_widgets', is_active_sidebar('sidebar-1'), 'sidebar-1')) {
  return;
}
?>
<div class="<?= esc_attr(apply_filters('bootscore/class/sidebar/col', 'lg:col-3 order-first lg:order-2')); ?>">
  <aside id="secondary" class="widget-area">

    <button class="<?= esc_attr(apply_filters('bootscore/sidebar/drawer/class/button', 'lg:d-none btn w-100 mb-7 d-flex justify-content-between align-items-center')); ?>" type="button" data-bs-toggle="drawer" data-bs-target="#sidebar" aria-controls="sidebar">
      <?= esc_html(apply_filters('bootscore/sidebar/drawer/button/text', __('Open side menu', 'bootscore'))); ?> <?php bootscore_icon('ellipsis-vertical'); ?>
    </button>

    <dialog class="<?= esc_attr(apply_filters('bootscore/sidebar/class/drawer', 'lg:drawer drawer-end')); ?>" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
      <div class="drawer-header <?= esc_attr(apply_filters('bootscore/sidebar/class/drawer/header', '', 'sidebar')); ?>">
        <span class="h5 drawer-title" id="sidebarLabel"><?= esc_html(apply_filters('bootscore/sidebar/drawer/title', __('Sidebar', 'bootscore'))); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="drawer" data-bs-target="#sidebar" aria-label="Close"></button>
      </div>
      <div class="drawer-body flex-column <?= esc_attr(apply_filters('bootscore/sidebar/class/drawer/body', '', 'sidebar')); ?>">
        
        <?php do_action( 'bootscore_before_sidebar_widgets' ); ?>
        
        <?php dynamic_sidebar('sidebar-1'); ?>
        
        <?php do_action( 'bootscore_after_sidebar_widgets' ); ?>
        
      </div>
    </dialog>

  </aside><!-- #secondary -->
</div>
