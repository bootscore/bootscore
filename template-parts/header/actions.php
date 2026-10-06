<?php

/**
 * Template part for displaying the header-actions
 * Template Version: 7.0.0
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */


// Exit if accessed directly
defined('ABSPATH') || exit;

?>


<!-- Searchform large -->
<?php if (apply_filters('bootscore/sidebar/has_widgets', is_active_sidebar('top-nav-search'), 'top-nav-search')) : ?>
  <div class="d-none <?= esc_attr(apply_filters('bootscore/class/header/search/breakpoint', 'lg')); ?>:d-block <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 md:ms-3', 'searchform')); ?> nav-search-lg">
    <?php dynamic_sidebar('top-nav-search'); ?>
  </div>
<?php endif; ?>

<!-- Search menu mobile -->
<?php if (apply_filters('bootscore/sidebar/has_widgets', is_active_sidebar('top-nav-search'), 'top-nav-search')) : ?>
  <div class="<?= esc_attr(apply_filters('bootscore/class/header/search/breakpoint', 'lg')); ?>:d-none <?= esc_attr(apply_filters('bootscore/class/header/action/spacer', 'ms-1 md:ms-3', 'search-toggler')); ?>">
    <button class="<?= esc_attr(apply_filters('bootscore/class/header/button', 'btn', 'search-toggler')); ?> search-toggler" type="button" data-bs-toggle="menu" data-bs-placement="bottom-end" aria-expanded="false">
      <?php bootscore_icon('search'); ?><span class="visually-hidden"><?php esc_html_e('Search', 'bootscore'); ?></span>
    </button>
    <div class="menu <?= esc_attr(apply_filters('bootscore/class/header/search/menu', 'search-menu p-3')); ?>">
      <?php dynamic_sidebar('top-nav-search'); ?>
    </div>
  </div>
<?php endif; ?>
