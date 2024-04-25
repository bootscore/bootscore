<?php

/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bootscore
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


if (!is_active_sidebar('sidebar-1')) {
  return;
}
?>
<div class="<?= apply_filters('bootscore/class/sidebar/col', 'col-lg-3 order-first order-lg-2'); ?>">
  <aside id="secondary" class="widget-area">

    <button class="<?= apply_filters('bootscore/class/sidebar/button', 'd-lg-none btn btn-outline-primary w-100 mb-4 d-flex justify-content-between align-items-center'); ?>" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
      <?php esc_html_e('Open side menu', 'bootscore'); ?> <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>

    <div class="<?= apply_filters('bootscore/class/sidebar/offcanvas', 'offcanvas-lg offcanvas-end'); ?>" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
      <div class="offcanvas-header">
        <span class="h5 offcanvas-title" id="sidebarLabel"><?php esc_html_e('Sidebar', 'bootscore'); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body flex-column">
        <?php dynamic_sidebar('sidebar-1'); ?>
      </div>
    </div>

  </aside><!-- #secondary -->
</div>
