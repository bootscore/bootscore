<?php

/**
 * The sidebar containing the main widget area
 * Template Version: 6.3.1
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


if (!is_active_sidebar('sidebar-1')) {
  return;
}
?>
<div class="<?= esc_attr(apply_filters('bootscore/class/sidebar/col', 'col-lg-3 order-first order-lg-2')); ?>">
  <aside id="secondary" class="widget-area">

    <button class="<?= esc_attr(apply_filters('bootscore/class/sidebar/button', 'd-lg-none btn btn-outline-primary w-100 mb-4 d-flex justify-content-between align-items-center')); ?>" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
      <?= esc_html(apply_filters('bootscore/offcanvas/sidebar/button/text', __('Open side menu', 'bootscore'))); ?> <?= wp_kses_post(apply_filters('bootscore/icon/ellipsis-vertical', '<i class="fa-solid fa-ellipsis-vertical"></i>')); ?>
    </button>

    <div class="<?= esc_attr(apply_filters('bootscore/class/sidebar/offcanvas', 'offcanvas-lg offcanvas-end')); ?>" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
      <div class="offcanvas-header <?= esc_attr(apply_filters('bootscore/class/offcanvas/header', '', 'sidebar')); ?>">
        <span class="h5 offcanvas-title" id="sidebarLabel"><?= esc_html(apply_filters('bootscore/offcanvas/sidebar/title', __('Sidebar', 'bootscore'))); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body flex-column <?= esc_attr(apply_filters('bootscore/class/offcanvas/body', '', 'sidebar')); ?>">
        
        <?php do_action( 'bootscore_before_sidebar_widgets' ); ?>
        
        <?php dynamic_sidebar('sidebar-1'); ?>
        
        <?php do_action( 'bootscore_after_sidebar_widgets' ); ?>
        
      </div>
    </div>

  </aside><!-- #secondary -->
</div>
